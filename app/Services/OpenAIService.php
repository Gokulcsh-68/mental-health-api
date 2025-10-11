<?php

namespace App\Services;

use App\Entities\AiLog;
use App\Requests\DiagnoseRequest;
use GuzzleHttp\Client;

class OpenAIService extends BaseService
{
    public function diagnose(DiagnoseRequest $request)
    {
        $patient_id      = $request->input('patient_id');
        $description = $request->input('description');
        $question    = $request->input('question', 'find disease');
        $lang        = $request->input('lang', 'en');
        $timezone    = $request->input('timezone', 'Asia/Calcutta');
        $list        = $request->input('list', 5);
        $disease     = $request->input('disease', null);
        $type        = $request->input('type', 'human'); // Default is 'human'

        // Build the prompt
        $prompt = $this->buildPrompt($description, $question, $lang, $timezone, $list, $disease);
        $prompt = str_replace(["\n", "\t", "\\"], '', $prompt);
        $prompt = str_replace('"', '\"', $prompt);

        // Define system prompt based on type using classic switch
        switch ($type) {
            case 'vet':
                $systemPrompt = "You are a highly experienced veterinary diagnostic AI with 30 years of expertise diagnosing diseases in animals across all species. Given a brief case summary including animal type, age, symptoms, and history, analyze and respond as instructed.";
            break;
            
            case 'human':
            default:
                $systemPrompt = "You are a highly experienced medical diagnostic AI Doctor with 30 years of expertise across all specialties. Given a patient's short description including age, gender, symptoms, and history, your task is to analyze and respond as instructed.";
            break;
        }

        // Send to OpenAI
        $client = new Client();
        $model  = env('OPENAI_API_MODEL', 'gpt-3.5-turbo');
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        $content = $result['choices'][0]['message']['content'] ?? 'No response';


        // Clean raw AI response
        $cleanContent = trim($content);

        // Remove triple quotes or backticks if present
        $cleanContent = preg_replace('/^("{3}|`{3}json|`{3})/', '', $cleanContent);
        $cleanContent = preg_replace('/("{3}|`{3})$/', '', $cleanContent);
        $cleanContent = trim($cleanContent);

        // Decode into array
        $parsedContent = json_decode($cleanContent, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($parsedContent)) {
            $input = [
                'description' => $description,
                'question' => $question,
                'language' => $lang,
                'timezone' => $timezone,
                'list' => $list,
                'disease' => $disease
            ];
            
            $aiData = [
                'input' => $input,
                'response' => $parsedContent,
                'service' => 'OpenAIService',
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
            'message' => 'Try again - too many requests. Please wait a moment and retry.',
            'data' => [],
        ], 500);
    }

    private function buildPrompt(string $description, string $question, string $lang, string $timezone, int $list , ?string $disease = null): string
    {
        // dd($description, $question, $lang, $timezone, $list, $disease); // Debugging line to check the inputs before building the prompt

        switch ($question) {
            case 'find disease':
                return <<<EOT
                    Analyze the following patient description and provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An array of up to minimum {$list} possible diagnoses, each with:
                        - "diagnosis": The condition’s name.
                        - "description": A brief, clinical definition of the condition, focusing on its essential features. Exclude symptoms or risk factors, as they’ll be listed separately.
                        - "symptoms_in_common": Symptoms from the input matching the condition.
                        - "symptoms_not_in_common": Input symptoms not typically associated with the condition.
                    3. Use clear, direct language without unnecessary details.
                    4. Ensure the response is a valid JSON string.

                    Input:
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 0:
                return <<<EOT
                    Analyze the patient's description in relation to the specified disease: "{$disease}" to identify common symptoms associated with the disease, including those not mentioned in the patient's description.
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "type": "common_symptoms"
                    - "symptoms": An array of objects, each with:
                        - "name": A common symptom associated with "{$disease}", including those not mentioned in the patient's description.
                        - "present": A boolean indicating whether the symptom is mentioned in the patient's description (true if present, false if not).
                    Use clear, direct language for symptom names.
                    Ensure the response is a valid JSON string.
                    Include all common symptoms of "{$disease}", such as intense throbbing headache, sensitivity to light, sensitivity to sound, nausea, vomiting, visual disturbances, dizziness, fatigue, neck stiffness, difficulty concentrating, sensitivity to smells, speech disturbances, and tingling in face or extremities.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 1:
                return <<<EOT
                    Provide a comprehensive overview of the specified disease: "{$disease}" including its definition, symptoms, causes, triggers, diagnosis, treatment, prevention, and conclusion.
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "type": "disease_overview"
                    - "overview": An object with:
                        - "definition": A brief description of "{$disease}" as a neurological condition characterized by intense, debilitating headaches, often accompanied by nausea, vomiting, and sensitivity to light and sound, with severe pain that can last hours to days and interfere with daily activities.
                        - "symptoms": An array of common symptoms associated with "{$disease}", exactly matching: ["Severe, throbbing headache, usually on one side of the head", "Nausea and vomiting", "Sensitivity to light (photophobia) and sound (phonophobia)", "Visual disturbances or aura, such as flashes of light or blind spots", "Dizziness or lightheadedness", "Fatigue and irritability"].
                        - "causes": A description of the causes of "{$disease}", exactly matching: "The exact cause of migraines is not fully understood, but they are believed to be related to changes in the brainstem and its interactions with the trigeminal nerve, a major pain pathway. Imbalances in brain chemicals, including serotonin, which helps regulate pain in the nervous system, may also be involved."
                        - "triggers": An array of common triggers for "{$disease}", exactly matching: ["Hormonal changes in women, such as fluctuations in estrogen", "Certain foods and drinks, including aged cheeses, salty foods, and alcohol", "Stress and anxiety", "Changes in sleep patterns or lack of sleep", "Environmental factors, such as bright lights or loud noises", "Weather changes or barometric pressure changes"].
                        - "diagnosis": A description of how "{$disease}" is diagnosed, exactly matching: "Diagnosing migraines typically involves a review of medical history, symptoms, and a physical and neurological examination. There are no specific tests to diagnose migraines, but doctors may recommend tests to rule out other causes of headaches, such as imaging tests like MRI or CT scans."
                        - "treatment": An array of treatment options for "{$disease}", exactly matching: ["Over-the-counter pain relievers, such as ibuprofen or aspirin", "Prescription medications, including triptans and ergotamines", "Preventive medications, such as beta-blockers, antidepressants, or anti-seizure drugs", "Lifestyle changes, such as stress management techniques and regular sleep patterns", "Alternative therapies, such as acupuncture or biofeedback"].
                        - "prevention": An array of preventive strategies for "{$disease}", exactly matching: ["Identifying and avoiding known triggers", "Maintaining a regular sleep schedule", "Eating regular meals and staying hydrated", "Managing stress through relaxation techniques or therapy", "Regular physical activity"].
                        - "conclusion": A summary of the impact of "{$disease}", exactly matching: "Migraines are a common and often debilitating condition that can significantly impact quality of life. Understanding the symptoms, triggers, and treatment options can help manage and reduce the frequency of migraine attacks. Patients should work closely with their healthcare providers to develop a personalized treatment plan."
                    Use clear, direct language suitable for both medical professionals and patients.
                    Ensure the response is a valid JSON string.
                    Ensure the conclusion exactly matches the specified wording.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 2.1:
                return <<<EOT
                    Provide a diagnostic test for the specified disease: "{$disease}" to help identify potential symptoms based on patient self-assessment.
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "type": "diagnostic_test"
                    - "test": An object with:
                        - "title": A title for the diagnostic test, exactly matching: "What ever disease given as disease Diagnosis Test".
                        - "description": A brief overview of "{$disease}" and the purpose of the diagnostic test, exactly matching: "Migraines are a type of headache characterized by intense, throbbing pain, often accompanied by nausea, vomiting, and sensitivity to light and sound. Diagnosing migraines typically involves a clinical evaluation based on the patient's symptoms and medical history. Below is a simple diagnostic test to help identify potential migraine symptoms:".
                        - "instructions": Instructions for taking the test, exactly matching: "Read each statement below and consider whether it applies to your recent headache experiences. This test is not a substitute for professional medical advice. Please consult a healthcare provider for a comprehensive evaluation.".
                        - "questions": An array of diagnostic questions, exactly matching: [
                            "Do you experience moderate to severe headaches that last between 4 to 72 hours?",
                            "Is the headache pain typically on one side of your head?",
                            "Do you feel a pulsating or throbbing sensation during the headache?",
                            "Are your headaches accompanied by nausea or vomiting?",
                            "Do you experience sensitivity to light (photophobia) or sound (phonophobia) during a headache?",
                            "Do physical activities, such as walking or climbing stairs, worsen your headache?",
                            "Have you experienced visual disturbances, such as seeing flashing lights or zigzag patterns, before the headache begins (aura)?",
                            "Do you have a family history of migraines?"
                        ].
                        - "interpretation": Guidance on interpreting the test results, exactly matching: "If you answered 'yes' to several of these questions, you may be experiencing migraines. It is important to seek a professional medical evaluation to confirm the diagnosis and discuss appropriate treatment options.".
                    Use clear, direct language suitable for patients.
                    Ensure the response is a valid JSON string.
                    Ensure all fields exactly match the specified wording.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 2:
                return <<<EOT
                    You are a medical content generator.  
                    Your task is to create a **diagnostic test overview** for the specified disease.  

                    Output Format:  
                    Provide the response strictly in **valid JSON** with the structure:  
                    {
                    "result": "success",
                    "data": {
                        "type": "general",
                        "title": "Diagnosis Test for {disease}",
                        "sections": [
                        {
                            "section": "Section Name",
                            "points": [
                            "Complete professional diagnostic sentence 1.",
                            "Complete professional diagnostic sentence 2."
                            ]
                        }
                        ]
                    }
                    }  

                    Rules for Output:  
                    1. **Do not include HTML, Markdown, or notes.** Output must be **clean JSON only**.  
                    2. Always include minimum **5-7 sections**, depending on the disease.  
                    3. Each section must contain minimum **3-4 points**, and each point must be a **complete professional medical sentence**.  
                    - GOOD → "Review of symptoms such as fatigue, weight loss, and hyperpigmentation."  
                    - BAD → "Fatigue, weight loss, hyperpigmentation"  
                    4. Section names must be **concise and professional** (no numbering).  
                    Suggested sections (adapt dynamically section based to the disease type with priority to diagnosis-related sections like symptoms and signs):  
                    - Medical History and Physical Examination  (if applicable)  
                    - Blood Tests  (if applicable)  
                    - ACTH Stimulation Tests (if applicable)  
                    - Insulin-Induced Hypoglycemia Test (if applicable)  
                    - Imaging Tests (X-ray, CT, MRI, Ultrasound)   (if applicable)  
                    - Autoantibody Tests (if autoimmune-related) (if applicable)  
                    - Specialized / Confirmatory Tests (ELISA, PCR, antibody testing, etc.)  (if applicable)  
                    - Additional / Differential Diagnosis  (if applicable)  
                    5. For **tests or procedures**, include:  
                    - One point describing the **procedure or test performed**.  
                    - One point describing the **interpretation or clinical relevance**.  
                    6. If the input description includes a species (human, dog, cat, etc.), adapt the workflow accordingly:  
                    - Human cases → human diagnostic protocols.  
                    - Veterinary cases → veterinary diagnostic protocols (CBC, urinalysis, fecal ELISA, abdominal ultrasound, etc.).  
                    7. Do not include introductions, summaries, or conclusions. Output only the **structured JSON object**.  

                    Input Context:  
                    - Disease: "{$disease}"  
                    - Patient Description: "{$description}"  
                    - Language: "{$lang}"  
                    - Timezone: "{$timezone}"  
                EOT;

            break;




            case 3:
                return <<<EOT
                    Analyze the patient's description in relation to the specified disease: "{$disease}" to identify additional symptoms that could be checked for a differential diagnosis.
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "type": "differential"
                    - "symptoms": An array of objects, each with:
                        - "name": A symptom relevant to "{$disease}" or related conditions that could be checked to aid in differential diagnosis, not mentioned in the patient's description.
                        - "checked": A boolean set to false, indicating the symptom is not mentioned in the patient's description.
                    Use clear, direct language for symptom names.
                    Ensure the response is a valid JSON string.
                    Include all relevant symptoms that could help differentiate "{$disease}" from other conditions, excluding symptoms already mentioned in the description.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 4:
                return <<<EOT
                Analyze the patient's description in relation to the specified disease: "{$disease}".

                Provide a JSON response with:
                1. "result": "success" if one or more symptoms support the suspicion of "{$disease}", otherwise "failure".

                2. "data": An object containing:
                - "disease": "Reasons to Suspect {$disease}": A list of reasons from the patient's description that support the suspicion of "{$disease}". For each reason:
                    - "detail": The symptom, sign, or relevant history from the patient's description.
                    - "explanation": A medically accurate explanation using clinical language. For example, use "hallmark symptom" for defining features, or "chronic" for symptoms persisting over time.

                - "analysis": An object containing:
                    - "common_symptoms": A list of common symptoms of "{$disease}" that are explicitly mentioned in the patient's description. For each:
                    - "symptom": The symptom name.
                    - "note": A short explanation of its significance in diagnosing "{$disease}".
                    - "not_common_symptoms": A list of typical symptoms of "{$disease}" that are NOT mentioned in the patient's description. For each:
                    - "symptom": The missing symptom name.
                    - "note": A brief note about its diagnostic importance.

                Instructions:
                - Use standardized clinical terms.
                - Only include symptoms under "common_symptoms" if directly stated in the description.
                - Do not assume or infer symptoms not explicitly mentioned.
                - Always include at least 3 typical missing symptoms (if possible).
                - Output must be valid JSON.
                - All sections must be present in the response, even if empty.

                Input:
                - Disease: "{$disease}"
                - Description: "{$description}"
                - Language: "{$lang}"
                - Timezone: "{$timezone}"
                EOT;
            break;

            case 4.2:
                return <<<EOT
                    Analyze the patient's description in relation to the specified disease: "{$disease}".
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "disease": "{$disease}"
                    - "analysis": An object with:
                        - "reasons_for_suspecting": An array of objects, each with:
                            - "reason": A concise reason from the patient's description supporting the suspicion of "{$disease}".
                        - "symptoms_in_common": An array of objects, each with:
                            - "symptom": A symptom or relevant detail from the patient's description that is commonly associated with "{$disease}".
                        - "symptoms_not_present": An array of objects, each with:
                            - "symptom": A common symptom of "{$disease}" that is not mentioned in the patient's description.
                    Use clear, direct language.
                    Ensure the response is a valid JSON string.
                    Include all possible reasons and symptoms from the patient's description in the respective sections.
                    Place the "reasons_for_suspecting" section before the "symptoms_in_common" section in the analysis object.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            case 4.1:
                return <<<EOT
                    Analyze the patient's description in relation to the specified disease: "{$disease}".
                    Provide a JSON response with:
                    1. "result": "success" or "failure".
                    2. "data": An object containing:
                    - "disease": "{$disease}"
                    - "analysis": An object with:
                        - "reasons_for_suspecting": An array of objects, each with:
                            - "reason": A concise reason from the patient's description supporting the suspicion of "{$disease}".
                            - "explanation": A brief explanation of why this reason suggests "{$disease}".
                        - "symptoms_in_common": An array of objects, each with:
                            - "symptom": A symptom or relevant detail from the patient's description that is commonly associated with "{$disease}".
                            - "explanation": A brief explanation of how this symptom or detail relates to "{$disease}".
                        - "symptoms_not_present": An array of objects, each with:
                            - "symptom": A common symptom of "{$disease}" that is not mentioned in the patient's description.
                            - "explanation": A note indicating that this symptom is not present in the patient's description.
                    Use clear, direct language in the explanations.
                    Ensure the response is a valid JSON string.
                    Place the "reasons_for_suspecting" section before the "symptoms_in_common" section in the analysis object.

                    Input:
                    - Disease: "{$disease}"
                    - Description: "{$description}"
                    - Language: "{$lang}"
                    - Timezone: "{$timezone}"
                EOT;
            break;

            default:
                $operation = 'diagnose';
            break;
        }
        
    }

}
