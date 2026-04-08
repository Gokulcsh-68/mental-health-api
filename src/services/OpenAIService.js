const axios = require('axios');
const logger = require('../config/logger');
const config = require('../config/config');

class OpenAIService {
    constructor() {
        this.apiKey = process.env.OPENAI_API_KEY;
        this.model = config.OPENAI_MODEL || 'gpt-3.5-turbo';
        this.apiUrl = 'https://api.openai.com/v1/chat/completions';
        this.transcriptionUrl = 'https://api.openai.com/v1/audio/transcriptions';
    }

    /**
     * @desc    Extract structured clinical info from a patient narrative
     * @param   {String} narrative
     * @param   {Object} patient   - { age, gender } for demographic-aware analysis
     * @returns {Object} Structured clinical record with _meta audit fields
     */
    async extractClinicalInfo(narrative, patient = {}) {
        if (!this.apiKey) {
            logger.warn('OpenAI API Key is missing. Returning mock data.');
            return this.getMockExtraction(narrative);
        }

        const { age, gender } = patient;
        const demographicLine = (age || gender)
            ? `\nPATIENT DEMOGRAPHICS: ${age ? `Age: ${age} years` : ''} ${gender ? `| Gender: ${gender}` : ''}. Use this to inform age/gender-specific differential diagnosis.\n`
            : '';

        try {
            const prompt = `
You are a world-class Neuropsychiatrist and Diagnostic Expert. Your task is to perform a deep clinical extraction from a patient narrative, producing a comprehensive psychiatric intake record.
${demographicLine}
RULES:
1. RESPONSE FORMAT: JSON ONLY.
2. MSE (Mental Status Exam): Infer the patient's state from the narrative style and content.
   - Mood: Subjective report (e.g., "Depressed", "Anxious").
   - Affect: Observed tone (e.g., "Constricted", "Labile").
   - Speech: Characteristics (e.g., "Pressured", "Poverty of speech").
   - Thought Process: Logical vs Disorganized (e.g., "Linear", "Circumstantial").
3. STRESSORS & PROTECTION: Identify what is hurting the patient and what is helping them stay resilient.
4. RECOMMENDATIONS: Provide 2-3 immediate clinical next steps (e.g., "Safety planning", "Thyroid panel", "CBT referral").

Few-Shot Example:
Narrative: "I've been feeling really on edge for about a month. My heart races like crazy at work, I can't focus on anything. I'm not sleeping much, maybe 4 hours a night. I'm starting to wonder if life is worth it. My wife is the only thing keeping me going."
Output:
{
  "ai_summary": "Patient presents with acute-onset generalized anxiety and autonomic hyperactivity (tachycardia), significant occupational dysfunction, and severe sleep disruption. Passive suicidal ideation is present, mitigated currently by strong spousal support.",
  "duration": "1 month",
  "severity": "Severe",
  "onset_pattern": "Gradual",
  "onset_date": null,
  "triggers": ["Work environment"],
  "relieving_factors": ["Spousal support"],
  "aggravating_factors": ["Workplace attendance"],
  "associated_symptoms": ["Tachycardia", "Anxiety", "Psychomotor agitation", "Insomnia"],
  "affected_domains": { "sleep": true, "appetite": false, "work": true, "social": false, "self_care": false, "concentration": true, "physical_health": true },
  "functional_impairment": "Severe occupational impairment and disrupted circadian rhythm.",
  "clinical_impression": "Clinical presentation highly suggestive of Anxiety Disorder with secondary Major Depressive markers and active suicidal ideation.",
  "potential_diagnoses": ["Generalized Anxiety Disorder (F41.1)", "Panic Disorder (F41.0)"],
  "mse_observations": {
    "mood": "Anxious and hopeless",
    "affect": "Congruent to mood",
    "speech": "Linear and goal-directed",
    "thought_process": "Linear but ruminative",
    "insight_judgment": "Fair"
  },
  "psychosocial_stressors": ["Workplace stress", "Occupational pressure"],
  "protective_factors": ["Strong marital relationship"],
  "recommendations": ["Immediate suicide risk assessment", "Referral for CBT", "Consider SSRI initiation"],
  "risk_markers": {
    "self_harm_detected": true,
    "violence_detected": false,
    "psychosis_detected": false,
    "substance_use_detected": false,
    "keywords_found": ["wonder if life is worth it"],
    "risk_level": "High"
  },
  "hpi": {
    "onset": "Gradual",
    "duration": "1 month",
    "course": "Continuous",
    "mood_features": ["Hopelessness"],
    "anxiety_features": ["Panic", "Restlessness", "Somatic tension"],
    "psychotic_features": [],
    "sleep": "Insomnia",
    "appetite": "Normal",
    "energy": "Agitation",
    "cognitive": ["Poor concentration"],
    "suicidal_ideation": "Passive",
    "previous_episodes": "No",
    "treatment_response": "None",
    "dsm5_mapping": ["Psychomotor agitation", "Insomnia", "Recurrent thoughts of death"],
    "severity_index": 75,
    "color_code": "#E53935",
    "recommendations": ["Immediate suicide risk assessment", "Refer to psychiatrist urgently", "Consider CBT"]
  },
  "previous_episodes": { "has_occurred_before": false, "frequency": null, "last_episode_date": null, "hospitalized_before": false, "notes": null }
}

Patient Narrative to Process: "${narrative}"

Final Output Format (JSON ONLY):
{
  "ai_summary": String,
  "duration": String,
  "severity": "Mild" | "Moderate" | "Severe",
  "onset_pattern": "Acute" | "Gradual" | "Episodic" | "Chronic",
  "onset_date": String (ISO 8601) | null,
  "associated_symptoms": [String],
  "affected_domains": { "sleep": Boolean, "appetite": Boolean, "work": Boolean, "social": Boolean, "self_care": Boolean, "concentration": Boolean, "physical_health": Boolean },
  "functional_impairment": String,
  "clinical_impression": String,
  "potential_diagnoses": [String],
  "mse_observations": { "mood": String, "affect": String, "speech": String, "thought_process": String, "insight_judgment": String },
  "psychosocial_stressors": [String],
  "protective_factors": [String],
  "recommendations": [String],
  "risk_markers": { "self_harm_detected": Boolean, "violence_detected": Boolean, "psychosis_detected": Boolean, "substance_use_detected": Boolean, "keywords_found": [String], "risk_level": "None" | "Low" | "Moderate" | "High" },
  "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935",
  "hpi": {
    "onset": "Gradual" | "Acute" | null,
    "duration": String,
    "course": "Episodic" | "Continuous" | "Progressive" | null,
    "mood_features": [String],
    "anxiety_features": [String],
    "psychotic_features": [String],
    "sleep": "Insomnia" | "Hypersomnia" | "Normal" | null,
    "appetite": "Increased" | "Reduced" | "Normal" | null,
    "energy": "Fatigue" | "Agitation" | "Normal" | null,
    "cognitive": [String],
    "suicidal_ideation": "Passive" | "Active" | "Plan" | "None" | null,
    "previous_episodes": "Yes" | "No" | null,
    "treatment_response": "Good" | "Partial" | "Resistant" | "None" | null,
    "dsm5_mapping": [String],
    "severity_index": Number (0-100),
    "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935",
    "recommendations": [String]
  },
  "previous_episodes": { "has_occurred_before": Boolean, "frequency": String | null, "last_episode_date": String | null, "hospitalized_before": Boolean, "notes": String | null },
  "ros": {
    "psychiatric": {
      "depressed_mood": Boolean,
      "anxiety": Boolean,
      "mania": Boolean,
      "psychosis": Boolean,
      "ocd_symptoms": Boolean,
      "ptsd_symptoms": Boolean,
      "substance_use": Boolean,
      "cognitive_decline": Boolean
    },
    "medical": {
      "thyroid_symptoms": Boolean,
      "seizure_history": Boolean,
      "head_injury": Boolean,
      "chronic_illness": Boolean,
      "medication_history": Boolean,
      "hormonal_changes": Boolean
    },
    "organic_red_flags": [String],
    "medication_induced_risk": [String],
    "substance_induced_probability": "None" | "Low" | "Moderate" | "High",
    "ai_notes": String | null,
    "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935"
  }
}

RULES FOR HPI:
1. 'dsm5_mapping': List specific DSM-5 diagnostic criteria met by the narrative ( e.g., "Depressed mood most of the day", "Significant weight loss", "Fatigue or loss of energy").
2. 'severity_index': Calculate a score from 0-100 based on symptom intensity and occupational/social dysfunction.
3. DSM-5 ALIGNMENT: Ensure symptom clusters (Mood, Anxiety, Psychotic) are mapped strictly only if evidence exists in narrative.
4. 'color_code': Return a hex color based on clinical urgency:
   - '#4CAF50' (green)  = severity_index 0-30, no risk (monitor only)
   - '#FDD835' (yellow) = severity_index 31-50, mild impairment
   - '#FB8C00' (orange) = severity_index 51-75, moderate impairment or risk
   - '#E53935' (red)    = severity_index 76-100, severe/critical, suicidality, psychosis, or mania
5. 'recommendations': 2-4 specific, prioritized clinical next steps matching the severity and diagnosis.

RULES FOR ROS:
6. 'ros.psychiatric': Set each boolean to true ONLY if there is clear narrative evidence.
7. 'ros.organic_red_flags': List specific medical causes that could explain psychiatric symptoms (e.g., "Thyroid dysfunction", "Seizure activity", "Medication side effect").
8. 'ros.medication_induced_risk': Name specific medications mentioned and their known psychiatric side effects.
9. 'ros.substance_induced_probability': Rate ("None"/"Low"/"Moderate"/"High") based on substance use mention.
10. 'ros.color_code': Same hex scale used for HPI. Base on highest risk factor found.
`;

            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a specialized psychiatric diagnostic assistant. Always respond with valid JSON only.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' },
                temperature: 0.2
            }, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'Content-Type': 'application/json'
                }
            });

            const content = response.data.choices[0].message.content;
            const parsed = JSON.parse(content);

            // Attach audit metadata
            parsed._meta = { model: this.model, is_mock: false };
            return parsed;

        } catch (error) {
            logger.error('OpenAI API Error: %s', error.message);
            return this.getMockExtraction(narrative);
        }
    }

    /**
     * @desc    Analyze structured ROS checklist answers → AI-generated flags
     * @param   {Object} psychiatric  - Boolean checklist answers
     * @param   {Object} medical      - Boolean checklist answers
     * @param   {String} extra_notes  - Optional clinician notes
     * @returns {Object} AI-generated flags
     */
    async analyzeROS(psychiatric = {}, medical = {}, extra_notes = '', patient = {}) {
        if (!this.apiKey) {
            return this._mockROSAnalysis(psychiatric, medical);
        }

        const { age, gender } = patient;
        const demographicLine = (age || gender)
            ? `PATIENT: ${age ? `${age} years old` : ''} ${gender ? `| Gender: ${gender}` : ''}. Use age and gender for differential diagnosis context.\n`
            : '';

        const prompt = `
You are a world-class Neuropsychiatrist. A clinician has completed a detailed Review of Systems (ROS) with follow-up answers. Analyze and generate a clinical AI report.
${demographicLine}
PSYCHIATRIC ROS:
- Depressed mood: ${psychiatric.depressed_mood ? `YES (Duration: ${psychiatric.depressed_mood_duration || 'unknown'}, Severity: ${psychiatric.depressed_mood_severity || 'unknown'}/10)` : 'NO'}
- Anxiety: ${psychiatric.anxiety ? `YES (Type: ${psychiatric.anxiety_type || 'unknown'}, Panic attacks: ${psychiatric.panic_attacks ? 'YES' : 'NO'})` : 'NO'}
- Mania: ${psychiatric.mania ? `YES (Duration: ${psychiatric.mania_duration || 'unknown'}, Features: ${psychiatric.mania_features?.join(', ') || 'none reported'})` : 'NO'}
- Psychosis: ${psychiatric.psychosis ? `YES (Type: ${psychiatric.psychosis_type?.join(', ') || 'unknown'}, Trigger: ${psychiatric.psychosis_trigger || 'unknown'})` : 'NO'}
- OCD symptoms: ${psychiatric.ocd_symptoms ? `YES (Details: ${psychiatric.ocd_details || 'none'})` : 'NO'}
- PTSD symptoms: ${psychiatric.ptsd_symptoms ? `YES (Trauma: ${psychiatric.trauma_type || 'unknown'}, When: ${psychiatric.trauma_date || 'unknown'})` : 'NO'}
- Substance use: ${psychiatric.substance_use ? `YES (Substances: ${psychiatric.substance_types?.join(', ') || 'unknown'}, Frequency: ${psychiatric.substance_frequency || 'unknown'})` : 'NO'}
- Cognitive decline: ${psychiatric.cognitive_decline ? `YES (Domains: ${psychiatric.cognitive_domains?.join(', ') || 'unknown'}, Onset: ${psychiatric.cognitive_onset || 'unknown'})` : 'NO'}

MEDICAL ROS (organic rule-out):
- Thyroid symptoms: ${medical.thyroid_symptoms ? `YES (Type: ${medical.thyroid_type || 'unknown'}, Diagnosed: ${medical.thyroid_diagnosed ? 'YES' : 'NO'})` : 'NO'}
- Seizure history: ${medical.seizure_history ? `YES (Type: ${medical.seizure_type || 'unknown'}, On medication: ${medical.seizure_on_medication ? 'YES' : 'NO'})` : 'NO'}
- Head injury: ${medical.head_injury ? `YES (Severity: ${medical.head_injury_severity || 'unknown'}, When: ${medical.head_injury_date || 'unknown'})` : 'NO'}
- Chronic illness: ${medical.chronic_illness ? `YES (Details: ${medical.chronic_illness_details || 'not specified'})` : 'NO'}
- Medication history: ${medical.medication_history ? `YES (Medications: ${medical.medications_list || 'not listed'})` : 'NO'}
- Hormonal changes: ${medical.hormonal_changes ? `YES (Type: ${medical.hormonal_type || 'unknown'})` : 'NO'}

${extra_notes ? `Clinician Notes: "${extra_notes}"` : ''}

Return ONLY valid JSON:
{
  "organic_red_flags": [String],
  "medication_induced_risk": [String],
  "substance_induced_probability": "None" | "Low" | "Moderate" | "High",
  "ai_notes": String,
  "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935"
}

RULES:
1. 'organic_red_flags': List specific medical conditions that COULD CAUSE the checked psychiatric symptoms. Use the detailed answers to be precise.
2. 'medication_induced_risk': Use the specific medications listed to identify known psychiatric side effects.
3. 'substance_induced_probability': Rate based on substance types, frequency, and cognitive decline combination.
4. 'ai_notes': 1-2 sentence critical clinical summary using the detailed answers.
5. 'color_code': '#4CAF50'=no flags, '#FDD835'=1-2 mild, '#FB8C00'=multiple/moderate, '#E53935'=psychosis+organic or High substance risk.
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a psychiatric diagnostic assistant. Respond with valid JSON only.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' },
                temperature: 0.2
            }, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'Content-Type': 'application/json'
                }
            });

            return JSON.parse(response.data.choices[0].message.content);
        } catch (err) {
            logger.error('ROS Analysis Error: %s', err.message);
            return this._mockROSAnalysis(psychiatric, medical);
        }
    }

    _mockROSAnalysis(psychiatric, medical) {
        const flags = [];
        if (medical.thyroid_symptoms) flags.push('Hypothyroidism may be causing depressive symptoms');
        if (medical.head_injury) flags.push('Post-traumatic psychiatric symptoms possible');
        if (medical.seizure_history) flags.push('Peri-ictal psychiatric symptoms should be ruled out');
        if (medical.hormonal_changes) flags.push('Hormonal imbalance contributing to mood instability');
        return {
            organic_red_flags: flags,
            medication_induced_risk: medical.medication_history ? ['Medication review required'] : [],
            substance_induced_probability: psychiatric.substance_use ? 'Moderate' : 'None',
            ai_notes: 'Mock analysis — OpenAI key not configured.',
            color_code: flags.length > 2 ? '#E53935' : flags.length > 0 ? '#FB8C00' : '#4CAF50'
        };
    }

    /**
     * @desc    Extract structured Past History from a patient narrative
     * @param   {String} narrative
     * @param   {Object} patient - { age, gender }
     * @returns {Object} Structured A-Z Past History
     */
    async extractPastHistory(narrative, patient = {}) {
        if (!this.apiKey) return this._mockPastHistoryExtraction(narrative);

        const { age, gender } = patient;
        const prompt = `
You are a Senior Psychiatric Consultant. Extract a comprehensive "A to Z" Past Mental History from this narrative.
PATIENT: ${age ? `${age}yo` : ''} ${gender || ''}

EXTRACT INTO THESE SECTIONS:
1. PSYCHIATRIC: Previous diagnoses, medication trials (drug, dose, duration, response), suicide attempts (year, method, intent), hospitalizations (year, reason, location).
2. MEDICAL: Chronic conditions, surgeries, head injuries (with LOV), seizures.
3. FAMILY: Psychiatric/substance history in relatives (specify relative and condition).
4. SUBSTANCE: Alcohol, tobacco, illicit drugs (status, quantity, frequency, last use).
5. DEVELOPMENTAL: Pregnancy/birth complications, milestones, childhood behavior.
6. SOCIAL: Education, employment, marital status, living situation, legal history, spiritual beliefs.
7. TRAUMA: Physical/Emotional/Sexual abuse history, significant losses.

RULES:
- Return valid JSON matching the schema.
- If info is missing, use null or [].
- Be clinically precise.

Narrative: "${narrative}"

Output JSON:
{
  "psychiatric_history": {
    "previous_diagnosis": [String],
    "medication_trials": [ { "name": String, "dose": String, "duration": String, "response": String, "side_effects": String } ],
    "suicide_attempts": [ { "year": String, "method": String, "intent": String } ],
    "hospitalizations": [ { "year": String, "reason": String, "location": String, "duration": String } ],
    "psychotherapy_history": String
  },
  "medical_history": {
    "chronic_conditions": [String],
    "surgeries": [ { "procedure": String, "year": String } ],
    "head_injury": { "detected": Boolean, "loss_of_consciousness": Boolean, "details": String },
    "seizures": { "detected": Boolean, "frequency": String, "last_seizure": String },
    "allergies": [String]
  },
  "substance_use": {
    "alcohol": { "status": "Current"|"Past"|"Never", "quantity": String, "frequency": String, "last_use": String },
    "tobacco_nicotine": { "status": String, "type": String, "quantity": String },
    "illicit_drugs": [ { "drug": String, "status": String, "frequency": String, "last_use": String } ],
    "caffeine": String,
    "prescription_misuse": String
  },
  "family_history": {
    "conditions": [ { "relative": String, "condition": String, "outcome": String } ],
    "suicide_in_family": Boolean,
    "substance_abuse_in_family": Boolean
  },
  "developmental_history": {
    "pregnancy_complications": String,
    "delivery_type": String,
    "milestones": "On-time"|"Delayed"|"Early",
    "childhood_behavior": String,
    "school_performance": String
  },
  "social_history": {
    "education": String,
    "employment": String,
    "marital_status": String,
    "living_situation": String,
    "legal_history": { "legal_issues": Boolean, "legal_details": String },
    "spiritual_beliefs": String,
    "strengths_hobbies": String
  },
  "trauma_history": {
    "physical_abuse": Boolean,
    "emotional_abuse": Boolean,
    "sexual_abuse": Boolean,
    "significant_losses": String,
    "military_service": Boolean,
    "trauma_notes": String
  }
}
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [{ role: 'system', content: 'You are a psychiatric extraction engine. JSON ONLY.' }, { role: 'user', content: prompt }],
                response_format: { type: 'json_object' },
                temperature: 0
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}`, 'Content-Type': 'application/json' } });
            return JSON.parse(response.data.choices[0].message.content);
        } catch (err) {
            logger.error('PastHistory Extraction Error: %s', err.message);
            return this._mockPastHistoryExtraction(narrative);
        }
    }

    /**
     * @desc    Analyze structured Past History → AI risk analysis
     */
    async analyzePastHistory(psychiatric_history = {}, medical_history = {}, substance_use = {}, family_history = {}, trauma_history = {}, patient = {}) {
        if (!this.apiKey) return this._mockPastHistoryAnalysis(psychiatric_history, substance_use, family_history);

        const { age, gender } = patient;
        const demographicLine = (age || gender)
            ? `PATIENT: ${age ? `${age} years old` : ''} ${gender ? `| Gender: ${gender}` : ''}. Factor age and gender in risk assessment and genetic implications.\n`
            : '';

        const prompt = `
You are a Board-Certified Psychiatrist. A clinician has submitted a patient's full past history. Analyze it and return a clinical risk assessment.
${demographicLine}
- Previous diagnoses: ${psychiatric_history.previous_diagnosis?.join(', ') || 'None'}
- Prior hospitalizations: ${psychiatric_history.hospitalizations?.length || 0} times
- Suicide attempts: ${psychiatric_history.suicide_attempts?.length || 0} attempts
- Medication trials: ${psychiatric_history.medication_trials?.map(m => m.name).join(', ') || 'None'}

MEDICAL HISTORY:
- Chronic conditions: ${medical_history.chronic_conditions?.join(', ') || 'None'}
- Head injury: ${medical_history.head_injury?.detected ? 'YES' : 'NO'}
- Seizures: ${medical_history.seizures?.detected ? 'YES' : 'NO'}

SUBSTANCE USE:
- Alcohol: ${substance_use.alcohol?.status || 'Never'}
- Tobacco: ${substance_use.tobacco_nicotine?.status || 'Never'}
- Illicit drugs: ${substance_use.illicit_drugs?.map(d => d.drug).join(', ') || 'None'}

FAMILY HISTORY:
- Conditions: ${family_history.conditions?.map(c => `${c.relative}: ${c.condition}`).join('; ') || 'None'}
- suicide_in_family: ${family_history.suicide_in_family ? 'YES' : 'NO'}

TRAUMA HISTORY:
- Physical Abuse: ${trauma_history.physical_abuse ? 'YES' : 'NO'}
- Sexual Abuse: ${trauma_history.sexual_abuse ? 'YES' : 'NO'}
- Emotional Abuse: ${trauma_history.emotional_abuse ? 'YES' : 'NO'}

Return ONLY valid JSON:
{
  "risk_flags": [String],
  "treatment_resistance_risk": "None" | "Low" | "Moderate" | "High",
  "genetic_risk_summary": String,
  "ai_notes": String,
  "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935"
}

RULES:
1. 'risk_flags': List specific clinical risks (e.g., "High suicide re-attempt risk", "Treatment-resistant pattern", "Polysubstance use", "Severe trauma history").
2. 'treatment_resistance_risk': Assess based on number of failed medication trials and hospitalization history.
3. 'genetic_risk_summary': 1-sentence summary of family history implications for this patient.
4. 'ai_notes': 2-sentence critical clinical summary covering major findings across A-Z categories.
5. 'color_code': '#4CAF50'=low risk, '#FDD835'=mild, '#FB8C00'=moderate, '#E53935'=prior suicide attempt OR multiple hospitalizations OR High substance use OR Severe trauma.
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a psychiatric diagnostic assistant. Respond with valid JSON only.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' },
                temperature: 0.2
            }, {
                headers: { 'Authorization': `Bearer ${this.apiKey}`, 'Content-Type': 'application/json' }
            });
            return JSON.parse(response.data.choices[0].message.content);
        } catch (err) {
            logger.error('PastHistory Analysis Error: %s', err.message);
            return this._mockPastHistoryAnalysis(psychiatric_history, substance_use, family_history);
        }
    }

    _mockPastHistoryExtraction(narrative) {
        return {
            psychiatric_history: { previous_diagnosis: ['Depression (Mock)'], medication_trials: [] },
            medical_history: { chronic_conditions: [] },
            substance_use: { alcohol: { status: 'Never' } },
            family_history: { conditions: [] },
            developmental_history: { milestones: 'On-time' },
            social_history: { employment: 'Mocked' },
            trauma_history: { physical_abuse: false }
        };
    }

    _mockPastHistoryAnalysis(psychiatric_history, substance_use, family_history) {
        const flags = [];
        if (psychiatric_history.suicide_attempts?.length > 0) flags.push('Prior suicide attempt — elevated re-attempt risk');
        if (psychiatric_history.hospitalizations?.length > 1) flags.push('Recurrent hospitalizations — treatment-resistant pattern');
        if (substance_use.illicit_drugs?.length > 0) flags.push('Illicit drug use — complicates treatment');
        if (family_history.suicide_in_family) flags.push('Family suicide history — elevated genetic risk');
        return {
            risk_flags: flags,
            treatment_resistance_risk: psychiatric_history.medication_trials?.length > 2 ? 'High' : 'Low',
            genetic_risk_summary: 'Mock: Family history analysis unavailable.',
            ai_notes: 'Mock analysis — OpenAI key not configured.',
            color_code: flags.length > 1 ? '#E53935' : flags.length === 1 ? '#FB8C00' : '#4CAF50'
        };
    }

    /**
     * @desc    Analyze structured MSE findings → AI clinical analysis
     * @param   {Object} mse     - All MSE component findings
     * @param   {Object} patient - { age, gender } for demographic-specific diagnosis
     */
    async analyzeMSE(mse = {}, patient = {}) {
        if (!this.apiKey) return this._mockMSEAnalysis(mse);

        const { appearance, behavior, speech, mood, affect,
            thought_form, thought_content, perception, insight, judgment, cognition } = mse;

        const { age, gender } = patient;

        const prompt = `
You are a Board-Certified Psychiatrist conducting a Mental Status Examination (MSE) analysis.

PATIENT DEMOGRAPHICS:
- Age: ${age !== null && age !== undefined ? `${age} years old` : 'Not recorded'}
- Gender: ${gender || 'Not recorded'}

NOTE: Use age and gender to inform differential diagnosis — e.g., first-episode psychosis peaks in males 18-25, bipolar in 20s, late-onset depression more common in females 50+, dementia consideration for >60.

MSE FINDINGS:

APPEARANCE:
- Grooming: ${appearance.grooming || 'Not assessed'}
- Dress: ${appearance.dress || 'Not assessed'}
- Hygiene: ${appearance.hygiene || 'Not assessed'}
- Eye contact: ${appearance.eye_contact || 'Not assessed'}

BEHAVIOR & PSYCHOMOTOR:
- Attitude: ${behavior.attitude || 'Not assessed'}
- Psychomotor activity: ${behavior.psychomotor || 'Not assessed'}
- Mannerisms: ${behavior.mannerisms?.join(', ') || 'None'}

SPEECH:
- Rate: ${speech.rate || 'Not assessed'}
- Volume: ${speech.volume || 'Not assessed'}
- Articulation: ${speech.articulation || 'Not assessed'}
- Spontaneity: ${speech.spontaneity || 'Not assessed'}

MOOD & AFFECT:
- Mood (subjective): ${mood.subjective || 'Not reported'}
- Clinician observed: ${mood.clinician_observed || 'Not assessed'}
- Affect quality: ${affect.quality || 'Not assessed'}
- Affect range: ${affect.range || 'Not assessed'}
- Appropriateness: ${affect.appropriateness || 'Not assessed'}

THOUGHT FORM: ${thought_form.process || 'Not assessed'} / Coherence: ${thought_form.coherence || 'Not assessed'}

THOUGHT CONTENT:
- Delusions: ${thought_content.delusions ? `YES (${thought_content.delusion_types?.join(', ') || 'type unknown'})` : 'None'}
- Suicidal ideation: ${thought_content.suicidal_ideation || 'None'}
- Homicidal ideation: ${thought_content.homicidal_ideation || 'None'}
- Obsessions: ${thought_content.obsessions ? 'Present' : 'None'}

PERCEPTION:
- Hallucinations: ${perception.hallucinations ? `YES (${perception.hallucination_types?.join(', ') || 'type unknown'}) — ${perception.hallucination_details || 'no details'}` : 'None'}
- Depersonalization: ${perception.depersonalization ? 'YES' : 'NO'}
- Derealization: ${perception.derealization ? 'YES' : 'NO'}

INSIGHT: ${insight.level || 'Not assessed'}
JUDGMENT: ${judgment.level || 'Not assessed'}

COGNITION:
- Orientation: Person=${cognition.orientation?.person !== false ? 'Oriented' : 'Disoriented'}, Place=${cognition.orientation?.place !== false ? 'Oriented' : 'Disoriented'}, Time=${cognition.orientation?.time !== false ? 'Oriented' : 'Disoriented'}
- Memory: ${cognition.memory || 'Not assessed'}
- Concentration: ${cognition.concentration || 'Not assessed'}
- ${cognition.cognitive_test ? `${cognition.cognitive_test} Score: ${cognition.cognitive_score ?? 'N/A'}/${cognition.cognitive_max ?? 30}` : 'No formal cognitive test administered'}

Return ONLY valid JSON:
{
  "affect_recognition": String,
  "speech_tempo_analysis": String,
  "emotional_tone_mapping": [String],
  "psychomotor_markers": [String],
  "clinical_formulation": String,
  "diagnostic_impressions": [String],
  "color_code": "#4CAF50" | "#FDD835" | "#FB8C00" | "#E53935"
}

RULES:
1. 'affect_recognition': Describe the inferred emotional state from appearance + affect + mood combo.
2. 'speech_tempo_analysis': Interpret the clinical significance of the speech findings.
3. 'emotional_tone_mapping': List 2-4 detected emotional tones (e.g., "Underlying despair", "Suppressed anger").
4. 'psychomotor_markers': List specific psychomotor clinical findings and their possible significance.
5. 'clinical_formulation': 2-3 sentence psychiatric formulation based on the entire MSE.
6. 'diagnostic_impressions': List 2-3 possible diagnoses suggested by this MSE in order of likelihood.
7. 'color_code': '#E53935'=active SI/HI/psychosis, '#FB8C00'=poor insight+delusions, '#FDD835'=partial insight+mood, '#4CAF50'=intact.
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a psychiatric diagnostic expert. Respond with valid JSON only.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' },
                temperature: 0.2
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}`, 'Content-Type': 'application/json' } });
            return JSON.parse(response.data.choices[0].message.content);
        } catch (err) {
            logger.error('MSE Analysis Error: %s', err.message);
            return this._mockMSEAnalysis(mse);
        }
    }

    _mockMSEAnalysis(mse) {
        const tc = mse.thought_content || {};
        const isRed = tc.suicidal_ideation !== 'None' || mse.perception?.hallucinations;
        return {
            affect_recognition: 'Mock: Full assessment requires OpenAI key.',
            speech_tempo_analysis: `Speech rate: ${mse.speech?.rate || 'not assessed'}`,
            emotional_tone_mapping: ['Mock data'],
            psychomotor_markers: mse.behavior?.psychomotor ? [`Psychomotor: ${mse.behavior.psychomotor}`] : [],
            clinical_formulation: 'Mock clinical formulation — configure OpenAI key for real analysis.',
            diagnostic_impressions: ['Requires AI analysis'],
            color_code: isRed ? '#E53935' : '#4CAF50'
        };
    }

    /**
     * @desc    Analyze full clinical profile to generate AI differential diagnosis
     * @param   {Object} clinicalData - Aggregated data from CC, HPI, ROS, Past History, MSE
     * @param   {Object} patient      - Patient demographics
     */
    async analyzeClinicalInference(clinicalData, patient = {}) {
        if (!this.apiKey) return this._mockClinicalInference();

        const { age, gender } = patient;
        const demographicLine = (age || gender)
            ? `PATIENT: ${age ? `${age} years old` : ''} ${gender ? `| Gender: ${gender}` : ''}. Factor age and gender in differential diagnosis.\n`
            : '';

        const prompt = `
You are an expert Diagnostician and Board-Certified Psychiatrist. Your goal is to provide a precise psychiatric differential diagnosis based strictly on DSM-5 algorithmic clustering.

${demographicLine}
CLINICAL DATA AGGREGATION:
${JSON.stringify(clinicalData, null, 2)}

INSTRUCTIONS:
1. DIAGNOSIS: Provide a structured "diagnosis" object with:
   - "primary": { "condition": "string", "dsm5_code": "string", "specifiers": ["string"] }
   - "secondary": { "condition": "string", "dsm5_code": "string", "specifiers": ["string"] } (optional)
   - "stressors": ["string"]
   - "severity": "Mild" | "Moderate" | "Severe"
2. DIFFERENTIAL DIAGNOSIS: Ranked 2-4 likely diagnoses with probability % and brief rationale.
3. DSM-5 ALIGNMENT: 
   - 'criteria_matched': Symptoms clearly present.
   - 'criteria_missing': Missing confirmative symptoms.
4. ORGANIC RULE-OUTS: Conditions that MUST be evaluated.
5. SUGGESTED INVESTIGATIONS: 
   - Suggest ONLY if organic correlation >30% or urgent risk detected.
   - Categories: "Organic Exclusion Panel" (CBC, Thyroid [T3, T4, TSH], B12, Vit D, LFT, RFT) and "Additional" (Toxicology, MRI, EEG, HbA1c).
6. RED FLAG ALERTS: Critical clinical warnings.
7. RISK ASSESSMENT:
   - Provide "suicide_risk", "violence_risk" (Low | Moderate | High).
   - Provide "self_care" (Adequate | Compromised).
   - "emergency_needed": true if any risk is High.
   - If ANY risk is "High", "immediate_protocol" MUST include: ["Crisis intervention contact", "Emergency referral", "Family informed (as per consent)"].
8. RISK STRATIFICATION: 0-100 score + level (Low | Moderate | High | Critical).
9. PRESCRIPTION: If a likely diagnosis is found, provide a structured "prescription" object:
   - "patient_name": Full name if available.
   - "diagnosis_summary": Concise summary of primary diagnosis.
   - "medications": Array of { "name", "dose", "duration", "instructions" }.
   - "emergency_warning_signs": Crucial signs requiring immediate ER visit.
   - "follow_up_date": Suggested next appointment date/timeline.
   - "signature": { "name": "Psychiatrist AI Assistant", "role": "Specialist", "signed_at": "ISO Date" }.
10. FOLLOW-UP & MONITORING: Provide a structured "follow_up" object:
    - "review_timeline": "1 week" | "2 weeks" | "4 weeks" (based on severity/risk).
    - "digital_tools": Array containing suggestions from: ["PHQ-9 auto tracking", "GAD-7 monthly scoring", "Suicide alert trigger", "Medication adherence tracker"].

Return ONLY valid JSON:
{
  "diagnosis": {
    "primary": { "condition": "string", "dsm5_code": "string", "specifiers": ["string"] },
    "secondary": { "condition": "string", "dsm5_code": "string", "specifiers": ["string"] },
    "stressors": ["string"],
    "severity": "string"
  },
  "differential_diagnosis": [ { "condition": "string", "probability": 85, "rationale": "string" } ],
  "rule_outs": ["string"],
  "suggested_investigations": [ { "test_name": "string", "category": "string", "priority": "string", "rationale": "string" } ],
  "criteria_matched": ["string"], "criteria_missing": ["string"], "red_flag_alerts": ["string"],
  "risk_assessment": {
    "suicide_risk": "string", "violence_risk": "string", "self_care": "string",
    "emergency_needed": true, "immediate_protocol": ["string"]
  },
  "risk_stratification": { "score": 90, "level": "string", "primary_risk": "string" },
  "prescription": {
    "patient_name": "string",
    "diagnosis_summary": "string",
    "medications": [ { "name": "string", "dose": "string", "duration": "string", "instructions": "string" } ],
    "emergency_warning_signs": ["string"],
    "follow_up_date": "string",
    "signature": { "name": "string", "role": "string", "signed_at": "string" }
  },
  "follow_up": {
    "review_timeline": "string",
    "digital_tools": ["string"]
  }
}
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a psychiatric diagnostic expert. You MUST provide a structured Final Diagnosis (DSM-5) including codes and specifiers in your JSON response.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' },
                temperature: 0.1
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}`, 'Content-Type': 'application/json' } });

            const result = JSON.parse(response.data.choices[0].message.content);

            // Attach AI Transparency Metadata
            result._ai_metadata = {
                model: this.model,
                timestamp: new Date().toISOString(),
                version: "1.2.0-clinical-standard",
                provider: "OpenAI"
            };

            return result;
        } catch (err) {
            logger.error('Clinical Inference Analysis Error: %s', err.message);
            return this._mockClinicalInference();
        }
    }

    /**
     * @desc    Predict population relapse probability based on clinical trends
     * @param   {Array} history - Array of previous clinical summaries/consults
     * @returns {Object} Relapse prediction with risk score and rationale
     */
    async predictRelapse(history) {
        if (!this.apiKey) return this._mockRelapsePrediction();

        const prompt = `Analyze the following psychiatric clinical history trends and predict the probability of relapse (0-100).
        History: ${JSON.stringify(history)}
        
        Return JSON:
        {
          "relapse_probability": Number,
          "risk_level": "Low" | "Moderate" | "High" | "Critical",
          "primary_drivers": ["string"],
          "suggested_interventions": ["string"]
        }`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [{ role: 'user', content: prompt }],
                response_format: { type: 'json_object' }
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}` } });

            const result = JSON.parse(response.data.choices[0].message.content);
            result._ai_metadata = { model: this.model, timestamp: new Date().toISOString() };
            return result;
        } catch (err) {
            logger.error('Relapse Prediction Error: %s', err.message);
            return this._mockRelapsePrediction();
        }
    }

    _mockRelapsePrediction() {
        return {
            relapse_probability: 72,
            risk_level: "High",
            primary_drivers: ["Increasing sleep disturbance", "Missed medication adherence checks", "Rising anxiety scores"],
            suggested_interventions: ["Schedule urgent psychiatrist review", "Trigger medication adherence nudge", "Family notification recommended"],
            _ai_metadata: { model: "mock-prediction", is_mock: true }
        };
    }

    _mockClinicalInference() {
        const mockResult = {
            differential_diagnosis: [
                { condition: "Major Depressive Disorder", probability: 82, rationale: "Presents with pervasive low mood, anhedonia, and fatigue for >2 weeks." },
                { condition: "Generalized Anxiety Disorder", probability: 64, rationale: "Excessive worry and autonomic hyperactivity noted." },
                { condition: "Bipolar II Disorder", probability: 28, rationale: "History suggestive of hypomanic episodes." }
            ],
            rule_outs: ["Hypothyroidism (rule out)", "Substance-induced mood disorder"],
            suggested_investigations: [
                { test_name: "Thyroid function test (T3, T4, TSH)", category: "Organic Exclusion Panel", priority: "Urgent", rationale: "Rule out hypothyroidism as primary cause of depressive symptoms." },
                { test_name: "CBC", category: "Organic Exclusion Panel", priority: "Routine", rationale: "Rule out anemia contributing to fatigue." },
                { test_name: "Vitamin B12", category: "Organic Exclusion Panel", priority: "Routine", rationale: "Assess for nutritional deficiency causing cognitive/mood symptoms." }
            ],
            criteria_matched: ["Depressed mood most of the day", "Significant fatigue", "Diminished interest in activities"],
            criteria_missing: ["Significant weight change", "Psychomotor agitation/retardation observed by others"],
            red_flag_alerts: ["Passive suicidal ideation detected", "Significant functional impairment in workplace"],
            risk_assessment: {
                suicide_risk: "High",
                violence_risk: "Low",
                self_care: "Compromised",
                emergency_needed: true,
                immediate_protocol: [
                    "Crisis intervention contact",
                    "Emergency referral",
                    "Family informed (as per consent)",
                    "Increase frequency of monitoring",
                    "Safety planning"
                ]
            },
            risk_stratification: {
                score: 85,
                level: "High",
                primary_risk: "Suicide Risk"
            },
            diagnosis: {
                primary: {
                    condition: "Posttraumatic Stress Disorder",
                    dsm5_code: "309.81 (F43.10)",
                    specifiers: ["With dissociative symptoms", "Delayed expression"]
                },
                secondary: {
                    condition: "Major Depressive Disorder",
                    dsm5_code: "296.33 (F33.2)",
                    specifiers: ["With anxious distress", "With melancholic features"]
                },
                stressors: ["Occupational instability", "Recent motor vehicle accident", "Social isolation"],
                severity: "Severe"
            },
            prescription: {
                patient_name: "John Doe",
                diagnosis_summary: "Severe PTSD and Recurrent MDD with high suicide risk.",
                medications: [
                    {
                        name: "Sertraline (Zoloft)",
                        dose: "50mg once daily",
                        duration: "For 30 days",
                        instructions: "Take in the morning with food. Avoid alcohol."
                    },
                    {
                        name: "Prazosin",
                        dose: "1mg at bedtime",
                        duration: "For 14 days",
                        instructions: "For trauma-related nightmares. Monitor for dizziness."
                    }
                ],
                emergency_warning_signs: [
                    "Worsening suicidal thoughts",
                    "Inability to care for basic needs",
                    "Acute confusion or disorientation",
                    "Signs of severe allergic reaction (rash, difficulty breathing)"
                ],
                follow_up_date: "1 week from today",
                signature: {
                    name: "Psychiatrist AI Assistant",
                    role: "Lead Specialist",
                    signed_at: new Date().toISOString()
                }
            },
            follow_up: {
                review_timeline: "1 week",
                digital_tools: [
                    "PHQ-9 auto tracking",
                    "Suicide alert trigger",
                    "Medication adherence tracker"
                ]
            }
        };

        mockResult._ai_metadata = {
            model: "mock-provider",
            timestamp: new Date().toISOString(),
            version: "1.2.0-mock",
            is_mock: true
        };

        return mockResult;
    }

    /**
     * @desc    Transcribe audio file to text using OpenAI Whisper
     * @param   {ReadableStream} fileStream 
     * @param   {String} originalName
     * @returns {String} transcribed text
     */
    async transcribeAudio(fileBuffer, originalName) {
        if (!this.apiKey) {
            logger.warn('OpenAI API Key is missing. Transcription will fail.');
            throw new Error('OpenAI API Key is missing');
        }

        try {
            const FormData = require('form-data');
            const formData = new FormData();
            formData.append('file', fileBuffer, { filename: originalName });
            formData.append('model', 'whisper-1');

            const response = await axios.post(this.transcriptionUrl, formData, {
                headers: {
                    ...formData.getHeaders(),
                    'Authorization': `Bearer ${this.apiKey}`
                }
            });

            return response.data.text;
        } catch (error) {
            const errorData = error.response ? error.response.data : error.message;
            logger.error('OpenAI Transcription Error: %o', errorData);
            throw error;
        }
    }

    /**
     * @desc    Fallback mock extraction for testing / missing API key
     */
    getMockExtraction(narrative) {
        const text = narrative.toLowerCase();

        const hasSelfHarm = text.includes('kill') || text.includes('hurt') || text.includes('suicide') || text.includes('self-harm');
        const hasPsychosis = text.includes('voices') || text.includes('hallucin') || text.includes('paranoi');
        const hasSubstance = text.includes('alcohol') || text.includes('drug') || text.includes('substance');

        return {
            ai_summary: `Patient reports ${text.includes('sad') || text.includes('depress') ? 'depressive' : 'psychological'} symptoms present for ${text.match(/(\d+ weeks?|\d+ months?)/)?.[0] || 'an unspecified duration'}.Functional impact noted across multiple domains.Clinical review recommended.`,
            duration: text.match(/(\d+ weeks?|\d+ months?|long time)/)?.[0] || 'unknown',
            severity: text.includes('severe') || text.includes('extremely') ? 'Severe' : text.includes('mild') ? 'Mild' : 'Moderate',
            onset_pattern: text.includes('sudden') || text.includes('acute') ? 'Acute' : text.includes('episod') ? 'Episodic' : 'Gradual',
            onset_date: null,
            triggers: text.includes('work') ? ['Work-related stress'] : text.includes('relationship') ? ['Relationship issues'] : [],
            relieving_factors: text.includes('sleep') ? ['Rest / Sleep'] : [],
            aggravating_factors: text.includes('alone') || text.includes('isolat') ? ['Social isolation'] : [],
            associated_symptoms: [
                ...(text.includes('sleep') || text.includes('insomnia') ? ['Insomnia'] : []),
                ...(text.includes('anxious') || text.includes('anxiety') ? ['Anxiety'] : []),
                ...(text.includes('appetite') || text.includes('eating') ? ['Appetite changes'] : [])
            ],
            affected_domains: {
                sleep: text.includes('sleep') || text.includes('insomnia'),
                appetite: text.includes('eat') || text.includes('appetite'),
                work: text.includes('work') || text.includes('job') || text.includes('office'),
                social: text.includes('friend') || text.includes('social') || text.includes('avoid'),
                self_care: text.includes('bed') || text.includes('hygiene') || text.includes('shower'),
                concentration: text.includes('focus') || text.includes('concentrat') || text.includes('memory'),
                physical_health: text.includes('pain') || text.includes('tired') || text.includes('fatigue')
            },
            functional_impairment: text.includes('work') && text.includes('friend')
                ? 'Significant social and occupational impairment'
                : text.includes('work') ? 'Occupational impairment'
                    : text.includes('friend') || text.includes('social') ? 'Social impairment'
                        : 'Mild to moderate impairment in daily functioning',
            risk_markers: {
                self_harm_detected: hasSelfHarm,
                violence_detected: text.includes('violen') || text.includes('attack'),
                psychosis_detected: hasPsychosis,
                substance_use_detected: hasSubstance,
                keywords_found: [
                    ...(hasSelfHarm ? ['self-harm keyword detected'] : []),
                    ...(hasPsychosis ? ['psychosis keyword detected'] : []),
                    ...(hasSubstance ? ['substance use keyword detected'] : [])
                ],
                risk_level: hasSelfHarm ? 'High' : hasPsychosis ? 'Moderate' : 'None'
            },
            previous_episodes: {
                has_occurred_before: text.includes('before') || text.includes('again') || text.includes('previous'),
                frequency: null,
                last_episode_date: null,
                hospitalized_before: text.includes('hospital') || text.includes('admitted'),
                notes: null
            },
            _meta: { model: 'mock', is_mock: true }
        };
    }
    /**
     * @desc    AI-powered chatbot for patient queries with clinical context
     * @param   {Array}  messages        - Historical messages [{role, content}]
     * @param   {Object} clinicalContext - { chief_complaints, hpi }
     * @param   {Object} patientInfo     - { name, age, gender }
     */
    async chatWithPatient(messages = [], clinicalContext = {}, patientInfo = {}) {
        if (!this.apiKey) {
            return {
                role: 'assistant',
                content: "I'm sorry, I'm currently in offline mode. Please contact your doctor for medical advice. (Note: OpenAI API Key missing)"
            };
        }

        const { name, age, gender } = patientInfo;
        const { chief_complaint, hpi } = clinicalContext;

        const contextLine = `
CONTEXT:
Patient Name: ${name || 'Patient'}
Demographics: ${age ? `${age} years old` : ''} ${gender ? `| Gender: ${gender}` : ''}
Latest Chief Complaint: ${chief_complaint?.complaint_text || 'None recorded'}
Brief HPI Summary: ${hpi?.hpi_summary || 'None recorded'}
`;

        const systemPrompt = `
You are MindBalance AI, a highly intelligent and empathetic AI Health Assistant for a specialized mental health clinic.
Your goal is to help patients understand their health better based on their reported symptoms and doctor observations.

STRICT CONTEXTUAL ADHERENCE:
1. ONLY use information provided in the CLINICAL CONTEXT below. 
2. Do NOT hallucinate medical history, medications, or doctor notes that are not explicitly provided.
3. If information is missing, politely inform the patient that it isn't in their current record.

NEGATIVE CONSTRAINTS:
1. Do NOT provide medical diagnoses.
2. Do NOT prescribe or recommend specific dosages for medications.
3. Do NOT include "unwanted things" like generic advice that contradicts or isn't relevant to their specific clinical records.

INSTRUCTIONS:
1. Be professional, supportive, and clear.
2. Acknowledge symptoms/records provided in the context.
3. **CRITICAL SAFETY RULE**: Always include the disclaimer: "I am an AI assistant, not a doctor. This information is for educational purposes and should not replace professional medical advice."
4. If the patient mentions suicide, self-harm, or extreme distress, immediately provide standard crisis resources (988 Lifeline) and urge emergency services.

${contextLine}
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: systemPrompt },
                    ...messages
                ],
                temperature: 0.3
            }, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'Content-Type': 'application/json'
                }
            });

            return response.data.choices[0].message;
        } catch (error) {
            logger.error('Chatbot API Error: %s', error.message);
            throw new Error('Failed to get response from AI assistant');
        }
    }

    /**
     * @desc    AI Assistant for general mental health support and consultations
     * @param   {Array}  messages        - Historical messages [{role, content, sender_name, sender_role}]
     * @param   {Object} clinicalContext - { chief_complaint, hpi, mse } (optional)
     */
    async mentalHealthAssistant(messages = [], clinicalContext = {}) {
        if (!this.apiKey) {
            return {
                role: 'assistant',
                content: "Our AI Support Assistant is currently finishing a review. Please hold on or consult our human professionals."
            };
        }

        const { chief_complaint, hpi, mse } = clinicalContext;

        const contextFragment = (chief_complaint || hpi || mse) ? `
CLINICAL CONTEXT (Optional support):
- Complaint: ${chief_complaint?.complaint_text || 'N/A'}
- HPI: ${hpi?.hpi_summary || 'N/A'}
- MSE: ${mse?.clinical_formulation || 'N/A'}
` : '';

        const systemPrompt = `
You are MindBalance AI, a friendly and helpful Mental Health Support Assistant.

RESPONSE RULE — CRITICAL BREVITY:
1. Answer in MAXIMUM 2 short, simple sentences. This is a hard limit.
2. Use extremely simple words. Avoid all medical jargon.
3. No unnecessary filler or "unwanted" generic information.

MANDATORY RULES:
1. NON-DIAGNOSTIC: Provide support only. Never diagnose.
2. NO PRESCRIPTIONS: Refer all medication questions to a doctor.
3. FRIENDLY & SUPPORTIVE: Be kind and non-judgmental.
4. If a crisis (self-harm/suicide) is mentioned, provide the 988 Lifeline immediately.
5. END every non-crisis response with: "— MindBalance AI (For medical advice, please talk to a professional.)"

${contextFragment}
`;

        const formattedMessages = messages.map(m => ({
            role: m.sender_role === 'ai' ? 'assistant' : 'user',
            content: `[${m.sender_name} (${m.sender_role})]: ${m.content}`
        }));

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: systemPrompt },
                    ...formattedMessages
                ],
                temperature: 0.2
            }, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'Content-Type': 'application/json'
                }
            });

            return response.data.choices[0].message;
        } catch (error) {
            logger.error('Mental Health AI Error: %s', error.message);
            throw new Error('Our AI expert is currently unavailable.');
        }
    }

    /**
     * @desc    Generate a unique image using DALL-E 3 based on a prompt
     */

    /**
     * @desc    Curated list of high-quality mental health themed images
     */
    getMentalHealthImages() {
        return [
            { id: 0, category: 'Meditation', url: "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b", keywords: ['meditat', 'yoga', 'mindful', 'peace', 'calm', 'breath', 'zen', 'still'] },
            { id: 1, category: 'Nature', url: "https://images.unsplash.com/photo-1506126613408-eca07ce68773", keywords: ['nature', 'forest', 'tree', 'outdoor', 'green', 'fresh', 'wood', 'leaf'] },
            { id: 2, category: 'Sunrise', url: "https://images.unsplash.com/photo-1499209974431-9dac3dc5c27d", keywords: ['sunrise', 'morning', 'hope', 'new day', 'start', 'light', 'dawn'] },
            { id: 3, category: 'Water', url: "https://images.unsplash.com/photo-1528715471579-d1bcf0ba5e83", keywords: ['water', 'lake', 'ocean', 'seren', 'blue', 'still', 'wave', 'river'] },
            { id: 4, category: 'Self-care', url: "https://images.unsplash.com/photo-1518199266791-5375a83190b7", keywords: ['self-care', 'bath', 'relax', 'pamper', 'love', 'kind', 'care', 'soft'] },
            { id: 5, category: 'Reading', url: "https://images.unsplash.com/photo-1493676303817-11398b446662", keywords: ['read', 'book', 'quiet', 'librar', 'study', 'learn', 'knowledg', 'page'] },
            { id: 6, category: 'Support', url: "https://images.unsplash.com/photo-1516534775068-ba3e7458af70", keywords: ['support', 'help', 'hand', 'together', 'empath', 'commun', 'friend', 'connect'] },
            { id: 8, category: 'Journaling', url: "https://images.unsplash.com/photo-1474418397713-7ded81cfaccb", keywords: ['journal', 'write', 'coffee', 'pen', 'reflect', 'thought', 'note', 'paper'] },
            { id: 9, category: 'Creativity', url: "https://images.unsplash.com/photo-1515023115689-589c33041d3c", keywords: ['art', 'paint', 'creat', 'color', 'express', 'draw', 'sketch', 'brush'] },
            { id: 10, category: 'Sleep', url: "https://images.unsplash.com/photo-1511295742364-917e7bb11ceb", keywords: ['sleep', 'rest', 'bed', 'night', 'dream', 'tired', 'nap', 'sleepy'] },
            { id: 11, category: 'Exercise', url: "https://images.unsplash.com/photo-1517836357463-d25dfeac3438", keywords: ['exercis', 'run', 'gym', 'workout', 'move', 'strength', 'active', 'fit'] },
            { id: 12, category: 'Nutrition', url: "https://images.unsplash.com/photo-1490645935467-49ec9c47a0c5", keywords: ['food', 'health', 'eat', 'nutrit', 'fruit', 'vegetab', 'meal', 'diet'] },
            { id: 14, category: 'Growth', url: "https://images.unsplash.com/photo-1416870262648-2513befe267b", keywords: ['growth', 'plant', 'sprout', 'change', 'improv', 'progress', 'grow', 'better'] },
            { id: 15, category: 'Mountains', url: "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b", keywords: ['mountain', 'climb', 'strength', 'peak', 'challeng', 'view', 'height', 'top'] },
            { id: 16, category: 'Cozy', url: "https://images.unsplash.com/photo-1536657467771-22250f4fac29", keywords: ['cozy', 'home', 'warm', 'blanket', 'inside', 'safe', 'house', 'comfy'] },
            { id: 17, category: 'Stars', url: "https://images.unsplash.com/photo-1419242902214-272b3f66ee7a", keywords: ['star', 'night', 'univers', 'dream', 'dark', 'perspect', 'sky', 'space'] },
            { id: 18, category: 'Coffee', url: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085", keywords: ['coffee', 'tea', 'warm', 'mug', 'break', 'pause', 'cup', 'drink'] },
            { id: 19, category: 'Flowers', url: "https://images.unsplash.com/photo-1490750967868-88aa4486c946", keywords: ['flower', 'bloom', 'beaut', 'soft', 'color', 'life', 'spring', 'petal'] },
            { id: 20, category: 'Sky', url: "https://images.unsplash.com/photo-1490730141103-6cac27aaab94", keywords: ['sky', 'cloud', 'blue', 'vast', 'freedom', 'open'] },
            { id: 21, category: 'Smile', url: "https://images.unsplash.com/photo-1522075469751-3a6694fb2f61", keywords: ['smile', 'happy', 'laugh', 'joy', 'cheer', 'positive'] },
            { id: 22, category: 'Beach', url: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e", keywords: ['beach', 'sand', 'sun', 'summer', 'vacation', 'shore'] },
            { id: 23, category: 'Rain', url: "https://images.unsplash.com/photo-1515694346937-087093206263", keywords: ['rain', 'puddle', 'wet', 'cozy', 'window', 'water'] },
            { id: 24, category: 'Art', url: "https://images.unsplash.com/photo-1460661419201-fd4cecea8f82", keywords: ['art', 'brush', 'canvas', 'creative', 'paint', 'studio'] }
        ];
    }

    /**
     * @desc    Select an image based on keyword matching from text
     */
    selectImageByText(text) {
        const images = this.getMentalHealthImages();
        const lowerText = text.toLowerCase();

        // Find image with most keyword matches
        let bestIndex = 1; // Default to Nature
        let maxMatches = 0;

        images.forEach((img, index) => {
            const matches = img.keywords.filter(k => lowerText.includes(k)).length;
            if (matches > maxMatches) {
                maxMatches = matches;
                bestIndex = index;
            }
        });

        return images[bestIndex].url;
    }

    /**
     * @desc    Generate a short, catchy mental health engagement tip
     */
    async generateEngagementTip() {
        const images = this.getMentalHealthImages();

        if (!this.apiKey) {
            return {
                title: "Mindfulness Tip 🧘‍♂️",
                message: "Take 3 deep breaths. Inhale peace, exhale stress. You've got this!",
                imageUrl: images[0]
            };
        }

        const prompt = `Generate a short, catchy, and supportive mental health tip for a mobile app push notification. 
        It should be 1-2 sentences max. Use emojis.
        Also, select the index (0-9) of the most relevant image from this thematic list:
        0: Meditation, 1: Nature, 2: Sunrise, 3: Calm Water, 4: Self-care, 5: Quiet time, 6: Support, 7: Freedom, 8: Journaling, 9: Creativity.
        
        Return JSON: { "title": "Catchy Title", "message": "The tip content", "imageIndex": Number }`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [{ role: 'system', content: 'You are a helpful mental health assistant.' }, { role: 'user', content: prompt }],
                response_format: { type: 'json_object' }
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}` } });

            const result = JSON.parse(response.data.choices[0].message.content);

            // Use improved keyword-based selection from expanded library
            result.imageUrl = this.selectImageByText(result.message);

            return result;
        } catch (error) {
            logger.error('OpenAI Engagement Tip Error: %s', error.message);
            return {
                title: "Daily Reminder 🌿",
                message: "Small steps lead to big changes. Keep moving forward!",
                imageUrl: images[1]
            };
        }
    }

    /**
     * @desc    Generate personalized encouragement based on assessment results
     */
    async generatePostAssessmentEncouragement(category, score) {
        const images = this.getMentalHealthImages();

        if (!this.apiKey) {
            return {
                title: "Assessment Complete! ✨",
                message: `Thank you for completing your ${category} assessment. Taking this step is a great move for your well-being!`,
                imageUrl: images[6]
            };
        }

        const prompt = `A user just completed a "${category}" mental health assessment with a score of ${score}.
        Generate a highly personalized, warm, and encouraging push notification message (1-2 sentences).
        Don't be clinical; be like a supportive friend.
        Also, select the index (0-9) of the most relevant image from this thematic list:
        0: Meditation, 1: Nature, 2: Sunrise, 3: Calm Water, 4: Self-care, 5: Quiet time, 6: Support, 7: Freedom, 8: Journaling, 9: Creativity.
        
        Return JSON: { "title": "Encouraging Title", "message": "The message", "imageIndex": Number }`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [{ role: 'system', content: 'You are a supportive mental health companion.' }, { role: 'user', content: prompt }],
                response_format: { type: 'json_object' }
            }, { headers: { 'Authorization': `Bearer ${this.apiKey}` } });

            const result = JSON.parse(response.data.choices[0].message.content);

            // Use improved keyword-based selection from expanded library
            result.imageUrl = this.selectImageByText(result.message);

            return result;
        } catch (error) {
            logger.error('OpenAI Post-Assessment Encouragement Error: %s', error.message);
            return {
                title: "Great Progress! 🌟",
                message: "You've completed your assessment. Proud of you for checking in with yourself today!",
                imageUrl: images[6]
            };
        }
    }

    /**
     * @desc    Generate a streaming AI response for chat interactions (SSE)
     * @param   {Array}  messages        - Historical messages [{role, content}]
     * @param   {Object} clinicalContext - { chief_complaint, hpi } (optional)
     * @returns {Stream} OpenAI response stream
     */
    async chatStream(messages = [], clinicalContext = {}) {
        if (!this.apiKey) {
            throw new Error('OpenAI API Key is missing for streaming');
        }

        const { chief_complaint, hpi } = clinicalContext;
        const contextLine = (chief_complaint || hpi) ? `
CONTEXT:
Latest Chief Complaint: ${chief_complaint?.complaint_text || 'None recorded'}
Brief HPI Summary: ${hpi?.hpi_summary || 'None recorded'}
` : '';

        const systemPrompt = `
You are MindBalance AI, an empathetic and professional Mental Health Assistant.
Your goal is to provide supportive, clear, and science-backed information.

STRICT RULES:
1. ONLY use information in the provided CLINICAL CONTEXT.
2. NEVER diagnose or prescribe medications.
3. If crisis (suicide/self-harm) is detected, provide the 988 Lifeline IMMEDIATELY.
4. Keep responses concise (2-4 sentences max per message).
5. Always end non-crisis responses with: "— MindBalance AI (For medical advice, please talk to a professional.)"

${contextLine}
`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: systemPrompt },
                    ...messages
                ],
                stream: true,
                temperature: 0.3
            }, {
                headers: {
                    'Authorization': `Bearer ${this.apiKey}`,
                    'Content-Type': 'application/json'
                },
                responseType: 'stream'
            });

            return response.data;
        } catch (error) {
            logger.error('OpenAI Streaming API Error: %s', error.message);
            throw new Error('Failed to initiate AI stream');
        }
    }

    /**
     * @desc    Generate a personalized welcome notification (Login/Register)
     * @param   {Object}  user      - User details { firstName }
     * @param   {Boolean} isNewUser - True if first-time registration
     * @returns {Object}  { title, message, imageIndex }
     */
    async generateWelcomeMessage(user, isNewUser = false) {
        if (!this.apiKey) return null;

        const prompt = isNewUser
            ? `A new user named ${user.firstName} just joined MindBalance. 
               Generate a warm, welcoming "Welcome" notification (max 20 words). 
               Include JSON: title, message, imageIndex (0-9).`
            : `User ${user.firstName} just logged back into MindBalance. 
               Generate a short, friendly "Welcome Back" greeting (max 15 words). 
               Include JSON: title, message, imageIndex (0-9).`;

        try {
            const response = await axios.post(this.apiUrl, {
                model: this.model,
                messages: [
                    { role: 'system', content: 'You are a supportive mental health assistant. Return JSON only.' },
                    { role: 'user', content: prompt }
                ],
                response_format: { type: 'json_object' }
            }, {
                headers: { 'Authorization': `Bearer ${this.apiKey}` }
            });

            return JSON.parse(response.data.choices[0].message.content);
        } catch (error) {
            logger.error('OpenAI Welcome Message Error: %s', error.message);
            return null;
        }
    }
}

module.exports = new OpenAIService();

