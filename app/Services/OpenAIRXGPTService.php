<?php

namespace App\Services;

use App\Entities\AiLog;
use App\Requests\RxgptRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class OpenAIRXGPTService extends BaseService
{
    /**
     * Process medical query using Rx GPT.
     *
     * @param RxgptRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function diagnose(RxgptRequest $request)
    {
        // Extract validated inputs
        $patient_id = $request->input('patient_id');
        $query  = $request->input('query');
        $lang   = $request->input('lang', 'en');
        $limit  = $request->input('limit', 5);
        $type   = $request->input('type', 'human');
        $limit  = max(1, min($limit, 15)); // Ensure limit is between 1 and 15        
        $questionType = $request->input('questionType', 'general'); // Default to 'general' if not provided

        $prompt = null;
        // dd($questionType, $query, $lang, $limit, $type);
        // Build prompt based on question type

        switch($questionType){
            case 'symptoms':
                $prompt = $this->buildSymptomPrompt($query, $lang, $limit);
            break;

            case 'treatment':
                $prompt = $this->buildVetTreatmentPrompt($query, $lang, $limit);
            break;

            default:
                $prompt = $this->buildPrompt($query, $lang, $limit);
            break;
        }   
       
        $prompt = str_replace(["\n", "\t", "\\"], '', $prompt);
        $prompt = str_replace('"', '\"', $prompt);

        // Define system prompt based on type
        $systemPrompt = match ($type) {
            'vet' => "You are Rx GPT, a veterinary pharmacology AI with 30 years of expertise. Provide evidence-based answers to veterinary medical queries in JSON format, adhering to the latest veterinary guidelines and literature.",
            default => "You are Rx GPT, a medical pharmacology AI with 30 years of expertise. Provide evidence-based answers to medical queries in JSON format, adhering to the latest medical guidelines and literature."
        };

        // Send request to OpenAI API
        try {
            $client = new Client();
            $model  = env('OPENAI_API_MODEL', 'gpt-3.5-turbo');
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' =>  $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            $content = $result['choices'][0]['message']['content'] ?? null;

            // Attempt to parse JSON response
            $parsedContent = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsedContent)) {

                $input = [
                    'query' => $query,
                    'language' => $lang,
                    'limit' => $limit
                ];

                $aiData = [
                    'input' => $input,
                    'response' => $parsedContent,
                    'service' => 'OpenAIRXGPTService',
                    'method' => 'diagnose',
                    'model' => $model,
                ];

                AiLog::create([
                    'patient_id' => $patient_id, // Make sure $pet is available
                    'data' => $aiData,
                    'status' => 'success',
                    'created_by' => $request->user()->id, // Assuming you have user authentication
                ]);
                
                return response()->json([
                    'code' => 200,
                    'message' => 'Success',
                    'data' => $parsedContent,
                ], 200);
            }

            return response()->json([
                'code' => 500,
                'message' => 'Try again – too many requests. Please wait a moment and retry.',
                'data' => [],
            ], 500);
        } catch (RequestException $e) {
            Log::error('Rx GPT API request failed: ' . $e->getMessage());
            return response()->json([
                'code' => 500,
                'message' => 'Failed to communicate with AI service. Please try again later.',
                'data' => [],
            ], 500);
        }
    }

    /**
     * Build the prompt for the OpenAI API.
     *
     * @param string $query
     * @param string $lang
     * @param int $limit
     * @return string
     */
    private function buildSymptomPrompt(string $query, string $lang, int $limit): string
    {
        $query  = trim($query);
        $lang   = trim($lang);

        // dd($query, $lang);

        return <<<EOT
        You are Rx GPT, a clinical pharmacology AI assistant designed to generate evidence-based, guideline-driven, and patient-centered medical content.

        Analyze the query below and return a structured JSON response with the following schema:

        {
        "summary": "Concise overview of symptomatic treatment, emphasizing patient-specific factors and monitoring for complications.",
        "details": [
            {
            "title": "N. Title", // Replace N with number and title (e.g., "1. Fever and General Discomfort", "2.  Dry Cough (Non-productive)", "3. Supportive Care and Monitoring")
            "content": [
                // Bullet points: Each must be specific, actionable, and tailored to the patient’s condition as individual array items.
                // Examples based on potential titles:
                // If title is "1. Fever Management":
                // "Administer paracetamol (acetaminophen) 500-1000 mg orally every 6 hours as needed (maximum 4 g/day) to reduce fever.",
                // "Monitor for signs of liver toxicity in patients with liver disease or excessive alcohol use."
                // If title is "2. Dry Cough Treatment":
                // "Prescribe dextromethorphan 10-20 mg orally every 4 hours or 30 mg every 6-8 hours (maximum 120 mg/day) for dry cough.",
                // "Consider levocloperastine or noscapine as alternatives where available, avoiding use in patients with respiratory depression."
                // If title is "3. Supportive Care and Monitoring":
                // "Promote hydration with water, electrolyte drinks, or clear broths to prevent dehydration.",
                // "Escalate care if fever persists >3 days, or if shortness of breath, oxygen saturation <94%, chest pain, or hemoptysis is present."
            ]
            },
            // Exactly {$limit} objects in total.
        ],
        "references": [
            "Include 1-2 medical guidelines or high-quality sources (e.g., 'CDC Guidelines for Fever Management', 'WHO Acute Respiratory Infection Guidelines') with URLs if available."
        ],
        "follow_up": "A relevant next-step question (e.g., 'Would you like a tailored treatment plan for a suspected diagnosis (e.g., influenza, COVID-19, or viral upper respiratory tract infection)?')"
        }

        Instructions:
        - Use plain, professional language in {$lang}.
        - Each 'content' bullet must contain clinically relevant and actionable advice:
        - Drug names with dosages (e.g., paracetamol 500-1000 mg, dextromethorphan 10-20 mg) dont repeat the drugs if already mentioned.
        - Preferred delivery methods (e.g., oral tablet, syrup).
        - Risk factors and precautions (e.g., avoid in specific conditions, monitor for side effects).
        - Supportive care interventions (e.g., hydration, room humidifier, steam inhalation).
        - Red flags for escalation (e.g., fever >3 days, shortness of breath, oxygen saturation <94%).
        - Avoid vague or overly general advice (e.g., 'rest adequately', 'practice hygiene').
        - Do not repeat the 'title' content in the bullets.
        - Ensure proper JSON syntax — test output for validity.
        - For fever and cough queries:
        - Prioritize symptomatic treatment (e.g., paracetamol for fever, dextromethorphan for dry cough).
        - Include supportive care (e.g., hydration, humidifier use).
        - Highlight red flags for escalation (e.g., persistent fever, respiratory distress).
        - Avoid antibiotics unless bacterial infection is confirmed.
        - Assume no comorbidities or allergies unless specified, but include general precautions.

        Query: "{$query}"
        EOT;
    }


   private function buildVetTreatmentPromptOld(string $query, string $lang, int $limit): string
    {
        $query = trim($query);
        $lang  = trim($lang);

        return <<<EOT
        You are Rx GPT, a veterinary pharmacology expert. Based on the following case, return a JSON object listing exactly {$limit} appropriate medication options.

        Each medication object must contain the following fields:
        - "name": Generic name and purpose (e.g., "Amlodipine (for hypertension)")
        - "dose": Weight-based dose and frequency, using mg or mg/kg for a 30 kg dog (e.g., "0.2 mg/kg PO once daily; 6 mg/day").
        - "rationale": Why this drug is used in this specific dog (e.g., safe in renal disease, good for proteinuria, NSAID-allergy safe, etc.).
        - "monitoring": Parameters to monitor (e.g., BP, kidney function, electrolytes, side effects).

        Output structure:
        {
        "summary": "Short clinical summary highlighting key priorities (e.g., renal protection, BP control, OA pain relief) and allergies.",
        "medications": [
            {
            "name": "1. Drug Name (Purpose)",
            "dose": "Full dose and frequency for 30 kg dog",
            "rationale": "Why this drug is chosen in this case",
            "monitoring": "What to monitor while on this medication"
            }
            // Return exactly {$limit} medication objects
        ]
        }

        Guidelines:
        - Output in {$lang}
        - Use precise veterinary medication names and dosages
        - Ensure correct mg/kg conversions for a 30 kg dog
        - Do not include general advice or supplements unless drug-based
        - Avoid vague words like “supportive care” or “monitor closely”
        - Ensure valid JSON format

        Case Input: {$query}
        EOT;
    }


    private function buildVetTreatmentPrompt(string $query, string $lang, int $limit): string
    {
        $query = trim($query);
        $lang  = trim($lang);

        return <<<EOT
        You are Rx GPT, a veterinary pharmacology expert. Based on the following case, return a JSON object that includes:

        1. A "medications" array with exactly {$limit} appropriate medication options. Each object must include:
            - "drug_name": Generic name (e.g., "Amoxicillin")
            - "strength": Dosage strength (e.g., "250 mg")
            - "dosage_form": Form (e.g., "tablet", "oral suspension")
            - "route_of_administration": (e.g., "PO", "IV", "SC")
            - "dose": Dosage in mg or mg/kg and total dose based on the weight from the case
            - "frequency": How often the drug is given (e.g., "BID", "once daily")
            - "duration": Treatment duration (in days)
            - "special_instructions": Important administration tips or precautions
            - "mandatory_medication": true for one required option, false for others

        2. A "management_plan" object structured exactly like this:
        {
        "supportive_care_measures": {
            "title": "",
            "description": ""
        },
        "dietary_or_feeding_guidelines": {
            "title": "",
            "description": ""
        },
        "environmental_or_housing_considerations": {
            "title": "",
            "description": ""
        },
        "monitoring_recommendations": {
            "title": "",
            "description": ""
        },
        "preventive_care_or_adjunct_therapies": {
            "title": "",
            "description": ""
        },
        "activity_restriction_or_rehabilitation": {
            "title": "",
            "description": ""
        },
        "owner_instructions_for_home_care": {
            "title": "",
            "description": ""
        }
        }

        Guidelines:
        - Output the result in {$lang}
        - Ensure accurate mg/kg dosing based on the weight from the case
        - Use only evidence-based veterinary medications
        - Avoid supplements or vague terms like "supportive care" unless specified
        - Do not explain the case or justify choices outside of the required fields
        - The output must be valid JSON with no extra commentary

        Case Input: {$query}
        EOT;
    }




    /**
     * Build the prompt for the OpenAI API.
     *
     * @param string $query
     * @param string $lang
     * @param int $limit
     * @return string
     */
    private function buildPrompt(string $query, string $lang, int $limit): string
    {
        $query  = trim($query);
        $lang   = trim($lang);
    
        return <<<EOT
        Analyze the query below and return a structured JSON response with the following schema:
        
        {
        "summary": "Concise overview focusing on individualizing therapy and therapeutic goals.",
        "details": [
            {
            "title": "N. Title", // Replace N with number and title (e.g., "1. Age", "2. Comorbidities", "3. Severity")
            "content": [
                // Bullet points: Each should be specific, and actionable.
                // Format: not as a string block, but as individual array items.
                // Examples based on potential titles:
        
                // If title is "1. Age":
                // "Children (<5 years): Consider nebulized albuterol 2.5 mg every 20 minutes as needed for acute symptoms.",
                // "Adolescents: Instruct on the use of a dry powder inhaler (e.g., salmeterol 50 mcg twice daily) and emphasize adherence.",
                // "Older adults: Monitor for potential cardiovascular side effects when prescribing short-acting beta-agonists."
        
                // If title is "2. Comorbidities":
                // "Patients with asthma and GERD: Consider a higher dose inhaled corticosteroid or adding a leukotriene receptor antagonist (e.g., montelukast 10 mg daily).",
                // "Patients with asthma and diabetes: Educate on potential hyperglycemia with systemic corticosteroid use during exacerbations.",
                // "Patients with asthma and anxiety: Explore non-pharmacological strategies alongside asthma medications to improve overall well-being."
        
                // If title is "3. Severity":
                // "Intermittent asthma: Prescribe a short-acting beta-agonist (e.g., albuterol inhaler) for as-needed symptom relief.",
                // "Mild persistent asthma: Initiate low-dose inhaled corticosteroid therapy (e.g., fluticasone propionate 50 mcg twice daily).",
                // "Severe persistent asthma: Consider adding a long-acting beta-agonist (LABA) to a medium-to-high dose inhaled corticosteroid and evaluate for biologic therapies (e.g., omalizumab)."
            ]
            },
            // Exactly {$limit} objects in total.
        ],
        "references": [
            "Include 1-3 medical guidelines or high-quality sources (e.g., 'GINA 2024', 'NHLBI 2020') with proper URLs if possible."
        ],
        "follow_up": "A relevant next-step question (e.g., 'Would you like information on specific inhaler devices?')"
        }
        
        Instructions:
        - Use plain, professional language in {$lang}.
        - Each 'content' bullet must contain clinically relevant and actionable advice:
        - Drug names with dosages (e.g., albuterol 2.5 mg, fluticasone propionate 50 mcg, montelukast 10 mg, salmeterol 50 mcg)
        - Preferred delivery devices or methods (e.g., nebulizer, metered-dose inhaler, dry powder inhaler)
        - Risk factors and precautions (e.g., monitor for tachycardia, potential for hyperglycemia)
        - Monitoring or follow-up strategies (e.g., assess symptom control at follow-up visits)
        - Lifestyle or behavioral interventions (e.g., smoking cessation, anxiety management techniques)
        - Management of comorbidities (e.g., consider GERD in medication selection)
        - Avoid using overly general advice or vague terms like "depends on case."
        - Do not repeat the 'title' content inside the bullets.
        - Ensure proper JSON syntax — test output for validity.
        
        Query: "{$query}"
        EOT;
    }
    

    
}