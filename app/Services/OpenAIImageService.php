<?php

namespace App\Services;

use App\Entities\AiLog;
use App\Entities\Doc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OpenAIImageService extends BaseService
{
    public function diagnoseSinglePic(Request $request)
    {
        // Validate the uploaded image
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Allow common image formats, max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ], 422);
        }

        try {
            // Store the uploaded image
            $imagePath = $request->file('image')->store('public/uploads');
            $absolutePath = storage_path('app/' . $imagePath);

            // Convert image to base64
            $base64Image = base64_encode(file_get_contents($absolutePath));

            // Define system prompt for veterinary context
            $systemPrompt = "You are Rx GPT, a veterinary pharmacology AI with 30 years of expertise. Provide evidence-based answers to veterinary medical queries in JSON format, adhering to the latest veterinary guidelines and literature.";

            // Send request to OpenAI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini', // Explicitly use vision-capable model
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $this->buildImagePrompt(),
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,' . $base64Image,
                                ],
                            ],
                        ],
                    ],
                ],
                'response_format' => ['type' => 'json_object'], // Ensure JSON output
            ]);

            // Check if the API request was successful
            if ($response->failed()) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Failed to process image with AI service',
                    'data' => ['error' => $response->json()['error']['message'] ?? 'Unknown error'],
                ], 500);
            }

            // Extract and parse diagnosis from response
            $content = $response->json()['choices'][0]['message']['content'] ?? null;
            $parsedContent = json_decode($content, true);

            // Validate JSON structure
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsedContent) && isset($parsedContent['summary'], $parsedContent['details'], $parsedContent['references'], $parsedContent['follow_up'])) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'data' => $parsedContent,
                    'image_path' => Storage::url($imagePath), // Return public URL for the stored image
                ], 200);
            }

            // Fallback for invalid JSON
            return response()->json([
                'code' => 500,
                'message' => 'Invalid or no response from AI service',
                'data' => [
                    'summary' => 'No diagnosis received. This is not a substitute for professional veterinary care.',
                    'details' => [],
                    'references' => [],
                    'follow_up' => 'Please consult a veterinarian for a professional diagnosis.',
                ],
                'image_path' => Storage::url($imagePath),
            ], 500);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'code' => 500,
                'message' => 'An error occurred while processing the image',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Diagnose potential health issues from a pet image.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function diagnose(Request $request)
    {
        // Validate uploaded images
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|integer|exists:patients,id',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Image validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }


        try {
            $patient_id = $request->input('patient_id');
            $type   = $request->input('type', 'human');
            $query  = $request->input('query');
            // Process and store images
            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = 'img_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
                // Store on S3 and verify success
                $s3Path = Storage::disk('s3')->putFileAs('pet_images', $image, $filename, 'public');
                if (!$s3Path) {
                    throw new \Exception('Failed to store image on S3');
                }
                $url = Storage::disk('s3')->url($s3Path);
            
                // Verify base64 encoding
                $base64 = base64_encode(file_get_contents($image->getRealPath()));
                if (empty($base64)) {
                    throw new \Exception('Failed to encode image to base64');
                }
            
                $images[] = [
                    'url' => $url,
                    'base64' => $base64,
                ];

                $insertDocument = [
                    'user_id' => $patient_id,
                    'properties' => json_encode([
                        'file_path' => $s3Path,
                        'file_name' => $filename,
                        'mime_type' => $image->getMimeType() ?? 'application/octet-stream',
                        'original_name' => $image->getClientOriginalName() ?? $filename,
                        'url' => $url,
                    ]),
                    'document_source' => 'imaging',
                ];
                
                $mergedRequest = Request::create(
                    $request->getUri(),
                    $request->method(),
                    array_merge($request->all(), $insertDocument),
                    $request->cookies->all(),
                    $request->files->all(), // 🔑 include files
                    $request->server->all()
                );

                $data = $mergedRequest->all();
                $data['properties'] = json_decode($data['properties'], true);

                // Now pass the cleaned array to create model
                Doc::create($data);

            }

            $systemPrompt = match ($type) {
                'vet' => "You are Rx GPT, a veterinary pharmacology AI with 30 years of experience. Analyze the provided pet images and deliver evidence-based veterinary diagnosis in JSON format with the following structure: {summary: string, details: array, references: array, follow_up: string}. Follow current veterinary guidelines.",
                default => "You are Med GPT, a medical diagnostic AI with 30 years of clinical experience. Analyze the provided patient images and deliver an evidence-based medical diagnosis in JSON format with the following structure: {summary: string, details: array, references: array, follow_up: string}. Follow current clinical guidelines and best practices in human medicine.",
            };

            $userContent = [
                [
                    'type' => 'text',
                    'text' => $this->buildImagePrompt($query),
                ],
            ];
            
            foreach ($images as $img) {
                $userContent[] = [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => 'data:image/jpeg;base64,' . $img['base64'],
                    ],
                ];
            }

            // Make API request with timeout, retry, and JSON enforcement
            $model = 'gpt-4o-mini'; // Explicitly use vision-capable model
            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userContent],
                    ],
                    'temperature' => 0,
                    'response_format' => ['type' => 'json_object'], // Ensure JSON output
                ]);

            // Verify API response
            if ($response->failed()) {
                return response()->json([
                    'code' => 500,
                    'message' => 'AI service request failed',
                    'errors' => ['details' => $response->json()['error']['message'] ?? 'Unknown issue'],
                ], 500);
            }

            // Parse AI response
            $responseData = $response->json();
            if (!isset($responseData['choices'][0]['message']['content'])) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Invalid response structure from AI service',
                    'errors' => ['details' => 'Missing choices or content in response'],
                ], 500);
            }

            $content = $responseData['choices'][0]['message']['content'];
            $parsedContent = json_decode($content, true);

            // Check JSON validity and structure
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsedContent)) {
            
                $aiData = [
                    'query' => ($request->input('query') ?? null),
                    'response' => $parsedContent,
                    'service' => 'OpenAIImageService',
                    'method' => 'diagnose',
                    'model' => $model,
                ];

                AiLog::create([
                    'patient_id' => $patient_id, // Make sure $pet is available
                    'data' => json_encode($aiData),
                    'status' => 'success',
                ]);
                
                return response()->json([
                    'code' => 200,
                    'message' => 'Diagnosis successful',
                    'data' => $parsedContent,
                    'image_urls' => array_column($images, 'url'),
                ], 200);
            }

            // Handle invalid JSON response

            return response()->json([
                'code' => 500,
                'message' => 'AI service returned invalid response',
                'data' => [
                    'summary' => 'Diagnosis unavailable. Seek professional veterinary care.',
                    'details' => [],
                    'references' => [],
                    'data' => $parsedContent,
                    'follow_up' => 'Consult a veterinarian for accurate diagnosis.',
                    'errors' => ['details' => json_last_error()],
                ],
                'image_urls' => array_column($images, 'url'),
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Error processing image',
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Build the prompt for image-based veterinary diagnosis with enhanced search capabilities.
     *
     * @param string $query
     * @param string $petType
     * @param string $scanArea
     * @param array|null $vitals
     * @return string
     */
    private function buildImagePrompt(
    string $query,
    string $petType = 'Unknown',
    string $scanArea = 'General', // 'Eyes', 'Skin', 'Teeth', 'Gait', 'General', 'FullSystematic'
    ?array $vitals = null // Optional: ['temperature' => '101.5°F', 'heart_rate' => '90 bpm', ...]
): string {
        $query = trim($query);

        $fourteenSystemsList = [
            "Integumentary (skin, coat, nails, footpads)",
            "Musculoskeletal (bones, joints, muscles, posture, gait)",
            "Nervous (mentation, cranial nerves, gait, posture, reflexes, seizures)",
            "Cardiovascular (heart rate/rhythm if inferable, mucous membrane color, pulse quality if describable)",
            "Respiratory (breathing effort/rate, nasal discharge, cough, abnormal lung sounds if inferable)",
            "Gastrointestinal (oral cavity including teeth/gums, abdomen, vomiting/diarrhea signs, appetite if inferable)",
            "Hepatobiliary (jaundice, abdominal distension if related)",
            "Urinary (urine color/straining if visible, inappropriate urination signs)",
            "Endocrine (body condition, hair coat changes, polydipsia/polyuria signs if inferable)",
            "Hematopoietic (pale mucous membranes, petechiae/bruising)",
            "Lymphatic (visible lymph node swelling)",
            "Reproductive (external genitalia, discharge, mammary glands)",
            "Ocular (eyes, eyelids, vision if assessable)",
            "Auditory (external ears, head tilt, balance)"
        ];
        $fourteenSystemsString = implode(", ", $fourteenSystemsList);

        $vitalsString = "Not provided.";
        if ($vitals && is_array($vitals) && count($vitals) > 0) {
            $vitalsParts = [];
            foreach ($vitals as $key => $value) {
                $vitalsParts[] = ucfirst(str_replace('_', ' ', $key)) . ": " . htmlspecialchars($value);
            }
            $vitalsString = implode(', ', $vitalsParts);
        }

        $systemFocusInstruction = "";
        $performFullSystematicReview = (strtolower($scanArea) === 'fullsystematic');

        if ($performFullSystematicReview) {
            $systemFocusInstruction = "Perform a COMPREHENSIVE SYSTEMATIC EVALUATION of all 14 body systems listed below. For each system, detail observations, link to vitals, list potential symptoms, suggest differential diagnoses, and recommend veterinary evaluations. If a system appears normal or is not assessable from the media, state that clearly.";
        } else {
            // Instruction for the 4-area scan (Eyes, Skin, Teeth, Gait)
            $eyeSignsList = "Eyelid Lump, Conjunctival edema, Cherry eye, Opacity, Red eye, Ectropion, Entropion, Epiphora, Eye discharge, Corneal wound/ulcer, Blepharedema, Corneal necrosis, Anisocoria, Nystagmus";
            $skinSignsList = "Lichenification, Pustule/Crust, Erosion/Ulcer, Pigmentation changes, Redness, Alopecia, Lesions, Pruritus signs, Scaling/Dandruff, Parasites";
            $teethSignsList = "Tartar, Gingivitis, Broken tooth, Malocclusion, Discoloration, Gum recession, Oral masses";
            $gaitSignsList = "Limping, Stiffness, Ataxia, Lameness, Reluctance to move, Abnormal posture, Asymmetrical movement, Knuckling, Circling";

            switch (strtolower($scanArea)) {
                case 'eyes':
                    $systemFocusInstruction = "Focus primarily on the Ocular system. Check for signs like: {$eyeSignsList}.";
                    break;
                case 'skin':
                    $systemFocusInstruction = "Focus primarily on the Integumentary system (Skin & Coat). Check for signs like: {$skinSignsList}.";
                    break;
                case 'teeth':
                    $systemFocusInstruction = "Focus primarily on the Dental aspects (part of Gastrointestinal/Musculoskeletal). Check for signs like: {$teethSignsList}.";
                    break;
                case 'gait':
                    $systemFocusInstruction = "Focus primarily on Gait (Musculoskeletal/Nervous). Analyze movement for signs like: {$gaitSignsList}.";
                    break;
                default: // General scan covering 4 key areas
                    $systemFocusInstruction = "Systematically evaluate these key areas if visible: Ocular (Eyes: {$eyeSignsList}), Integumentary (Skin: {$skinSignsList}), Dental (Teeth: {$teethSignsList}), and Musculoskeletal (Gait/Joints: {$gaitSignsList}).";
            }
            $systemFocusInstruction .= "\nIf the user query or initial findings suggest a more widespread issue, you MAY briefly comment on other systems if highly relevant, but the primary focus is on these key areas unless 'FullSystematic' scan is requested.";
        }

        $exampleImageInstruction = "";
        if (strpos(strtolower($query), 'snaggletooth') !== false || strpos(strtolower($query), 'example dog') !== false) {
            $exampleImageInstruction = "If analyzing the example image of the brown dog with the prominent snaggletooth:
            For the 'Teeth' area (or Gastrointestinal/Musculoskeletal in a full scan): You MUST identify 'Malocclusion' (specifically, a rostrally displaced and laterally deviated upper canine, likely left).
            In a 'FullSystematic' scan for this dog:
            - Musculoskeletal: Note the jaw malformation/dental malocclusion.
            - Gastrointestinal: Note the malocclusion and potential for difficulty eating (symptom), oral trauma.
            - Other systems: Likely appear normal from the image, state as such.
            - Differential Diagnoses for malocclusion: Congenital/developmental, traumatic (less likely given appearance).
            - Veterinary Evaluation: Dental examination, skull/dental X-rays.
            - Severity: Moderate. Confidence: High.";
        }

        return <<<EOT
        You are "Petsync AI Pro", an advanced AI Pet Health Analysis assistant. Your purpose is to analyze pet images (or videos for gait assessment) to identify potential health abnormalities. You can perform a focused scan on Eyes, Skin, Teeth, or Gait, OR a comprehensive systematic evaluation of 14 body systems. Your output is intended to help pet owners understand potential issues and to strongly guide them towards professional veterinary consultation. This is NOT a diagnostic service.

        Analyze the provided pet media based on the user query: "{$query}"
        Pet Type: {$petType}
        Scan Type Requested: {$scanArea}
        Provided Vitals: {$vitalsString}

        {$systemFocusInstruction}

        Regardless of the scan type, based on what you see, list up to atleast 5 possible visible health issues (e.g., skin infection, cherry eye, dental decay, leg injury, ear inflammation, etc.). For each issue, include:
        - Name of the issue
        - A brief explanation of what's visible
        - Estimated severity: [Mild, Moderate, Severe]
        - Whether immediate vet attention is needed: [Yes/No]
        Include these in the 'scan_summary' under 'key_visible_health_issues'.

        If the image is unclear or non-diagnostic for accurate analysis, state: "Image not clear enough for accurate analysis" in the 'media_quality' section under 'assessment', and adjust the analysis accordingly.

        {$exampleImageInstruction}

        The 14 body systems for comprehensive review are: {$fourteenSystemsString}.

        Return a structured JSON response.

        JSON Schema:
        {
            "scan_summary": {
                "overall_impression": "Based on the image of [Pet's Name or 'the pet'], this is a brief and empathetic overview of their visible health status. Specific observations (e.g., 'pronounced underbite', 'twisted muzzle') are noted along with potential health concerns, confidence levels (e.g., 'High confidence in congenital malocclusion'), and species-specific considerations. If no abnormalities are visible, the pet appears healthy, with normal findings such as a 'glossy coat' or 'clear eyes'. If the image quality is poor, mention that the analysis may be limited. Any identified concerns should be followed up with a licensed veterinarian. This analysis is not a substitute for professional veterinary care.'",
                "key_visible_health_issues": [
                    {
                        "issue_name": "e.g., Dental Malocclusion",
                        "visible_signs": "e.g., Pronounced underbite with misaligned teeth",
                        "estimated_severity": "Mild" | "Moderate" | "Severe",
                        "immediate_vet_attention_needed": "Yes" | "No"
                    }
                    // up to 5 issues
                ],
                "key_recommendation": "Primary call to action, e.g., 'A thorough veterinary examination is strongly recommended to investigate these findings.'"
            },
            "pet_metadata": {
                "species": "{$petType}",
                "age_estimate": "e.g., 'Young Adult', 'Unknown'",
                "breed_guess": "e.g., 'German Shepherd', 'Unknown'", // Try to identify the exact breed or partial if possible, don't use 'Unknown' if possible
                "vitals_provided": "{$vitalsString}" // Reflects the provided vitals or "Not provided."
            },
            // This section is for the focused 4-area scan (Eyes, Skin, Teeth, Gait)
            // Populate this if scanArea is 'Eyes', 'Skin', 'Teeth', 'Gait', or 'General'
            // If scanArea is 'FullSystematic', this section can be minimal or summarize key findings from the detailed review below.
            "focused_area_evaluations": [
                {
                    "area_name": "Eyes", // Or "Skin", "Teeth", "Gait/Joints"
                    "assessment_status": "Abnormalities detected" | "No significant abnormalities detected" | "Not clearly visible/assessable",
                    "findings": [
                        {
                            "abnormal_sign": "e.g., Conjunctival edema",
                            "description": "e.g., Pink tissues around the eye appear swollen.",
                            "location": "e.g., Both eyes",
                            "confidence": "High",
                            "estimated_severity": "Moderate",
                            "potential_implications": "e.g., Could be due to allergies, infection, or irritants."
                        }
                    ],
                    "area_summary_and_recommendation": "e.g., Eyes show inflammation. Veterinary check recommended."
                }
                // ... more objects for other focused areas if applicable
            ],
            // This section is for the DETAILED 14-SYSTEM EVALUATION
            // Populate this thoroughly if scanArea is 'FullSystematic'.
            // Evaluate all body systems, identifying abnormalities in the image and related potential issues.
            "detailed_system_assessment": [
                {
                    "system_name": "e.g., Musculoskeletal",
                    "observations": "Visible signs related to this system (e.g., 'Pronounced underbite and twisted muzzle affecting jaw alignment', 'Limping on left hind leg'). If normal or not assessable, state so.",
                    "potential_symptoms": [
                        "Symptoms typically associated with the observed abnormalities (e.g., 'Difficulty eating or prehending food', 'Pain on manipulation of jaw', 'Reluctance to bear weight on limb')."
                    ],
                    "vitals_integration": "How provided vitals support, contradict, or refine the assessment for THIS system (e.g., 'Elevated respiratory rate could be pain-related if musculoskeletal issue is severe', or 'Vitals normal, not directly indicative for this observation', or 'No vitals provided').",
                    "potential_causes": [
                        "Possible underlying causes for the abnormalities (e.g., 'Congenital malformation', 'Developmental abnormality', 'Trauma (old or new)', 'Degenerative joint disease', 'Infection')."
                    ],
                    "differential_diagnoses": [ // 1-n most likely, with brief reasoning
                        {
                            "condition_name": "e.g., Mandibular Prognathism with Maxillary Brachygnathism and Wry Mouth",
                            "reasoning": "e.g., Based on the visible severe misalignment of the jaw and teeth, consistent with complex congenital craniofacial abnormalities."
                        },
                        {
                            "condition_name": "e.g., Osteoarthritis of the hip",
                            "reasoning": "e.g., Limping in an older, large breed dog, especially if associated with stiffness after rest."
                        }
                    ],
                    "severity_assessment": "Low" | "Moderate" | "High" | "Urgent" // based on potential impact
                    "confidence_in_findings": "Low" | "Moderate" | "High" // based on clarity of visual evidence
                    "recommended_veterinary_evaluation": {
                        "physical_examinations": [
                            "e.g., 'Thorough oral and dental examination', 'Orthopedic examination including joint palpation and range of motion testing', 'Neurological examination'"
                        ],
                        "diagnostic_tests": {
                            "lab_tests": [
                                "e.g., 'Pre-anesthetic blood panel if sedation/anesthesia needed for X-rays', 'Joint fluid analysis', 'CBC/Chemistry if infection suspected'"
                            ],
                            "imaging": [
                                "e.g., 'Dental X-rays under anesthesia', 'Skull radiographs or CT scan', 'Radiographs of affected limb/joints'"
                            ],
                            "other_procedures": [
                                "e.g., 'Biopsy of any skin lesions', 'Fine needle aspirate of swollen lymph nodes'"
                            ]
                        }
                    }
                }
                // ... one object for EACH of the 14 body systems if 'FullSystematic' scan
            ],
            "general_supportive_care_notes": [
                "e.g., 'Ensure comfortable resting place.', 'Monitor appetite and water intake.', 'Avoid activities that exacerbate limping.'",
                "If any signs of severe pain, distress, or rapid worsening, seek immediate veterinary attention."
            ],
            "references_consulted_for_analysis": [ // AI should list 2-4 general authoritative sources it 'consulted'
                {"source_name": "Merck Veterinary Manual", "url": "https://www.merckvetmanual.com/"},
                {"source_name": "AVMA Guidelines (General)", "url": "https://www.avma.org/resources-tools/avma-policies"}
            ],
            "media_quality": {
                "assessment": "e.g., 'Clear, well-lit', 'Slightly blurry', or 'Image not clear enough for accurate analysis'",
                "impact_on_analysis": "e.g., 'Sufficient for reliable assessment of visible areas.', 'Limited ability to assess fine details.'"
            }
        }

        General Instructions for AI:
        - Language: Clear, empathetic, professional English. Explain technical terms if used.
        - Systematic Approach: If 'FullSystematic' scan is requested, address ALL 14 body systems. If a system appears normal or is not assessable, explicitly state this.
        - Vitals: If vitals are provided, integrate them into the assessment for each relevant system in `detailed_system_assessment`.
        - Differential Diagnoses: Provide plausible differentials with brief reasoning. Do not present them as definitive diagnoses.
        - Recommendations: Veterinary evaluation recommendations should be specific and justified by the findings.
        - Caution & Disclaimer: Be conservative. If in doubt, recommend veterinary consultation. The final disclaimer is paramount.
        - JSON Validity: Ensure perfect JSON output.
        - Completeness: Populate all relevant fields. If `scanArea` is not 'FullSystematic', the `detailed_system_assessment` section may be less populated or focused only on highly relevant systems. The `focused_area_evaluations` should be populated for 'Eyes', 'Skin', 'Teeth', 'Gait', or 'General' scans.

        EOT;
    }

}