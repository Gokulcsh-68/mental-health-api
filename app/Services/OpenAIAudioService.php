<?php

namespace App\Services;

use App\Entities\AiLog;
use App\Entities\Doc;
use App\Entities\Patient;
use App\Entities\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use Ramsey\Uuid\Uuid;

class OpenAIAudioService
{
            /**
             * Convert audio transcription to a SOAP note.
             *
             * @param Request $request
             * @return \Illuminate\Http\JsonResponse
             */
            public function convertToSOAP(Request $request)    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'audio' => 'required|file|mimes:mp3,wav,m4a,aac,mp4|max:10240',
            'patient_id' => 'required|integer|exists:patients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                
            ], 422);
        }

        $convertedFilePath = null;
        $transcription = null;

        try {
            // Ensure OpenAI API key is set
            if (empty(env('OPENAI_API_KEY'))) {
                throw new \Exception('OpenAI API key is not configured');
            }

            // Ensure temp directory exists
            // Since additional_info is cast to object, access it directly
            $patientInfo = $patient->additional_info ?? new \stdClass();
            
            // Extract patient details safely
            $patientDetails = [
                'id'                => $patient->id,
                'patient_name'      => trim(($patient->user->first_name  ?? '') . ' ' . ($patient->user->last_name ?? '')) ?: 'Not provided',
                'dob'               => $patient->user->dob ?? 'Not provided',
                'gender'            => $patient->user->gender ?? 'Not provided',
                'blood_group'       => $patient->user->blood_group ?? 'Not provided',
            ];

            // Step 1: Handle audio file
            $audioFile = $request->file('audio');
            $extension = strtolower($audioFile->getClientOriginalExtension());
            $filename = 'audio_' . Uuid::uuid4()->toString() . '.' . ($extension === 'aac' ? 'mp3' : $extension);
            $filePath = $audioFile->getRealPath();
            $originalName = $audioFile->getClientOriginalName();

            // Convert AAC to MP3
            if ($extension === 'aac') {
                $convertedFilePath = storage_path('app/temp/' . $filename);
                try {
                    // Fetch FFmpeg paths from .env with Docker-compatible defaults
                    $ffmpegPath = env('FFMPEG_PATH', '/usr/bin/ffmpeg');
                    $ffprobePath = env('FFPROBE_PATH', '/usr/bin/ffprobe');

                    // Verify FFmpeg binaries exist
                    if (!file_exists($ffmpegPath) || !file_exists($ffprobePath)) {
                        throw new \Exception('FFmpeg or FFprobe binary not found at: ' . $ffmpegPath . ' or ' . $ffprobePath);
                    }

                    $ffmpeg = FFMpeg::create([
                        'ffmpeg.binaries' => $ffmpegPath,
                        'ffprobe.binaries' => $ffprobePath,
                    ]);
                    $audio = $ffmpeg->open($filePath);
                    $audio->save(new \FFMpeg\Format\Audio\Mp3(), $convertedFilePath);
                    $filePath = $convertedFilePath;
                    $originalName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('FFmpeg conversion failed: ' . $e->getMessage());
                    throw new \Exception('Failed to convert AAC to MP3: ' . $e->getMessage());
                }
            }

            // Step 2: Store the audio file on S3
            $s3Path = Storage::disk('s3')->putFileAs('human_audio', new \Illuminate\Http\UploadedFile(
                $filePath,
                $originalName,
                $extension === 'aac' ? 'audio/mpeg' : $audioFile->getMimeType(),
                null,
                true
            ), $filename, 'public');
            if (!$s3Path) {
                throw new \Exception('Failed to store audio on S3');
            }
            $url = Storage::disk('s3')->url($s3Path);

            // Save document metadata to Doc model
            $insertDocument = [
                'user_id' => $patient->user->id,
                'properties' => json_encode([
                    'file_path' => $s3Path,
                    'file_name' => $filename,
                    'mime_type' => $extension === 'aac' ? 'audio/mpeg' : ($audioFile->getMimeType() ?? 'application/octet-stream'),
                    'original_name' => $audioFile->getClientOriginalName() ?? $filename,
                    'url' => $url,
                ]),
                'document_source' => 'audio',
                'created_by' => $request->user()->id,
            ];

            $mergedRequest = Request::create(
                $request->getUri(),
                $request->method(),
                array_merge($request->all(), $insertDocument),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all()
            );

            $data = $mergedRequest->all();
            $data['properties'] = json_decode($data['properties'], true);
            Doc::create($data);

            // Step 3: Transcribe audio using Whisper
            $whisperResponse = Http::timeout(config('services.openai.whisper_timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                ])
                ->attach(
                    'file',
                    fopen($filePath, 'r'),
                    $originalName
                )
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => config('services.openai.whisper_model', 'whisper-1'),
                    'language' => 'en',
                ]);

            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            if ($whisperResponse->failed()) {
                throw new \Exception('Audio transcription failed: ' . json_encode($whisperResponse->json()));
            }

            $transcription = $whisperResponse->json()['text'] ?? '';
            if (empty($transcription)) {
                throw new \Exception('Empty transcription received from Whisper API');
            }

            // Step 4: Prepare system prompt for SOAP note
            $systemPrompt = $this->buildSystemPrompt($patientDetails, $transcription);

            // Step 5: Get structured SOAP note from GPT
            $response = Http::timeout(config('services.openai.gpt_timeout', 30))
                ->retry(config('services.openai.retries', 2), 1000)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $transcription],
                    ],
                    'temperature' => config('services.openai.temperature', 0.3),
                ]);

            if ($response->failed()) {
                throw new \Exception('GPT API request failed: ' . json_encode($response->json()));
            }

            $gptContent = $response->json()['choices'][0]['message']['content'] ?? '';
            if (empty($gptContent)) {
                throw new \Exception('Empty response from GPT API');
            }

            // Step 6: Clean and parse GPT response
            $cleanedContent = trim($gptContent);
            if (str_starts_with($cleanedContent, '```')) {
                $cleanedContent = preg_replace('/^```(?:json)?\s*([\s\S]*?)\s*```$/', '$1', $cleanedContent);
            }

            $soapData = json_decode($cleanedContent, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($soapData)) {
                throw new \Exception('Failed to parse SOAP JSON: ' . json_last_error_msg());
            }

            // Step 7: Save AI log
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'response' => $soapData,
                    'transcript' => $transcription,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'audio_url' => $url,
                ]),
                'status' => 'success',
            ]);

            // Step 8: Return response
            return response()->json([
                'code' => 200,
                'message' => 'SOAP note generated successfully',
                'data' => $soapData,
                'transcript' => $transcription,
                'audio_url' => $url,
            ], 200);

        } catch (\Exception $e) {
            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            // Log error for debugging
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'error' => $e->getMessage(),
                    'transcript' => $transcription ?? null,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                ]),
                'status' => 'error',
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    public function convertToTranscript(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'audio' => 'required|file|mimes:mp3,wav,m4a,aac,mp4|max:10240',
            'patient_id' => 'required|integer|exists:patients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                
            ], 422);
        }

        $convertedFilePath = null;
        $transcription = null;

        try {
            // Ensure OpenAI API key is set
            if (empty(env('OPENAI_API_KEY'))) {
                throw new \Exception('OpenAI API key is not configured');
            }

            // Ensure temp directory exists
            Storage::makeDirectory('temp');

            // Retrieve patient details
            $patient = Patient::findOrFail($request->input('patient_id'));


            // Step 1: Handle audio file
            $audioFile = $request->file('audio');
            $extension = strtolower($audioFile->getClientOriginalExtension());
            $filename = 'audio_' . Uuid::uuid4()->toString() . '.' . ($extension === 'aac' ? 'mp3' : $extension);
            $filePath = $audioFile->getRealPath();
            $originalName = $audioFile->getClientOriginalName();

            // Convert AAC to MP3
            if ($extension === 'aac') {
                $convertedFilePath = storage_path('app/temp/' . $filename);
                try {
                    // Fetch FFmpeg paths from .env with Docker-compatible defaults
                    $ffmpegPath = env('FFMPEG_PATH', '/usr/bin/ffmpeg');
                    $ffprobePath = env('FFPROBE_PATH', '/usr/bin/ffprobe');

                    // Verify FFmpeg binaries exist
                    if (!file_exists($ffmpegPath) || !file_exists($ffprobePath)) {
                        throw new \Exception('FFmpeg or FFprobe binary not found at: ' . $ffmpegPath . ' or ' . $ffprobePath);
                    }

                    $ffmpeg = FFMpeg::create([
                        'ffmpeg.binaries' => $ffmpegPath,
                        'ffprobe.binaries' => $ffprobePath,
                    ]);
                    $audio = $ffmpeg->open($filePath);
                    $audio->save(new \FFMpeg\Format\Audio\Mp3(), $convertedFilePath);
                    $filePath = $convertedFilePath;
                    $originalName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('FFmpeg conversion failed: ' . $e->getMessage());
                    throw new \Exception('Failed to convert AAC to MP3: ' . $e->getMessage());
                }
            }

            // Step 2: Store the audio file on S3
            $s3Path = Storage::disk('s3')->putFileAs('human_audio', new \Illuminate\Http\UploadedFile(
                $filePath,
                $originalName,
                $extension === 'aac' ? 'audio/mpeg' : $audioFile->getMimeType(),
                null,
                true
            ), $filename, 'public');
            if (!$s3Path) {
                throw new \Exception('Failed to store audio on S3');
            }
            $url = Storage::disk('s3')->url($s3Path);

            // Save document metadata to Doc model

            $insertDocument = [
                'user_id' => $patient->user->id,
                'properties' => json_encode([
                    'file_path' => $s3Path,
                    'file_name' => $filename,
                    'mime_type' => $extension === 'aac' ? 'audio/mpeg' : ($audioFile->getMimeType() ?? 'application/octet-stream'),
                    'original_name' => $audioFile->getClientOriginalName() ?? $filename,
                    'url' => $url,
                ]),
                'document_source' => 'audio',
                'created_by' => $request->user()->id,
            ];

            $mergedRequest = Request::create(
                $request->getUri(),
                $request->method(),
                array_merge($request->all(), $insertDocument),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all()
            );

            $data = $mergedRequest->all();
            $data['properties'] = json_decode($data['properties'], true);
            $docs = Doc::create($data);

            // Step 3: Transcribe audio using Whisper
            $whisperResponse = Http::timeout(config('services.openai.whisper_timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                ])
                ->attach(
                    'file',
                    fopen($filePath, 'r'),
                    $originalName
                )
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => config('services.openai.whisper_model', 'whisper-1'),
                    'language' => 'en',
                ]);

            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            if ($whisperResponse->failed()) {
                throw new \Exception('Audio transcription failed: ' . json_encode($whisperResponse->json()));
            }

            $transcription = $whisperResponse->json()['text'] ?? '';
            if (empty($transcription)) {
                throw new \Exception('Empty transcription received from Whisper API');
            }

            // Step 7: Save AI log
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_transcript',
                    'transcript' => $transcription,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'audio_url' => $url,
                ]),
                'status' => 'success',
            ]);

            // Step 8: Return response
            return response()->json([
                'code' => 200,
                'message' => 'SOAP transcription generated successfully',
                'transcript' => $transcription,
                'audio_url' => $url,
            ], 200);

        } catch (\Exception $e) {
            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            // Log error for debugging
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'error' => $e->getMessage(),
                    'transcript' => $transcription ?? null,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                ]),
                'status' => 'error',
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }


     public function convertToTranscriptBasedOnConversation(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'audio' => 'required|file|mimes:mp3,wav,m4a,aac,mp4|max:10240',
            'patient_id' => 'required|integer|exists:patients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                
            ], 422);
        }

        $convertedFilePath = null;
        $transcription = null;

        try {
            // Ensure OpenAI API key is set
            if (empty(env('OPENAI_API_KEY'))) {
                throw new \Exception('OpenAI API key is not configured');
            }

            // Ensure temp directory exists
            Storage::makeDirectory('temp');

            // Retrieve patient details
            $patient = Patient::findOrFail($request->input('patient_id'));


            // Step 1: Handle audio file
            $audioFile = $request->file('audio');
            $extension = strtolower($audioFile->getClientOriginalExtension());
            $filename = 'audio_' . Uuid::uuid4()->toString() . '.' . ($extension === 'aac' ? 'mp3' : $extension);
            $filePath = $audioFile->getRealPath();
            $originalName = $audioFile->getClientOriginalName();

            // Convert AAC to MP3
            if ($extension === 'aac') {
                $convertedFilePath = storage_path('app/temp/' . $filename);
                try {
                    // Fetch FFmpeg paths from .env with Docker-compatible defaults
                    $ffmpegPath = env('FFMPEG_PATH', '/usr/bin/ffmpeg');
                    $ffprobePath = env('FFPROBE_PATH', '/usr/bin/ffprobe');

                    // Verify FFmpeg binaries exist
                    if (!file_exists($ffmpegPath) || !file_exists($ffprobePath)) {
                        throw new \Exception('FFmpeg or FFprobe binary not found at: ' . $ffmpegPath . ' or ' . $ffprobePath);
                    }

                    $ffmpeg = FFMpeg::create([
                        'ffmpeg.binaries' => $ffmpegPath,
                        'ffprobe.binaries' => $ffprobePath,
                    ]);
                    $audio = $ffmpeg->open($filePath);
                    $audio->save(new \FFMpeg\Format\Audio\Mp3(), $convertedFilePath);
                    $filePath = $convertedFilePath;
                    $originalName = pathinfo($audioFile->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3';
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('FFmpeg conversion failed: ' . $e->getMessage());
                    throw new \Exception('Failed to convert AAC to MP3: ' . $e->getMessage());
                }
            }

            // Step 2: Store the audio file on S3
            $s3Path = Storage::disk('s3')->putFileAs('human_audio', new \Illuminate\Http\UploadedFile(
                $filePath,
                $originalName,
                $extension === 'aac' ? 'audio/mpeg' : $audioFile->getMimeType(),
                null,
                true
            ), $filename, 'public');
            if (!$s3Path) {
                throw new \Exception('Failed to store audio on S3');
            }
            $url = Storage::disk('s3')->url($s3Path);

            // Save document metadata to Doc model

            $insertDocument = [
                'user_id' => $patient->user->id,
                'properties' => json_encode([
                    'file_path' => $s3Path,
                    'file_name' => $filename,
                    'mime_type' => $extension === 'aac' ? 'audio/mpeg' : ($audioFile->getMimeType() ?? 'application/octet-stream'),
                    'original_name' => $audioFile->getClientOriginalName() ?? $filename,
                    'url' => $url,
                ]),
                'document_source' => 'audio',
                'created_by' => $request->user()->id,
            ];

            $mergedRequest = Request::create(
                $request->getUri(),
                $request->method(),
                array_merge($request->all(), $insertDocument),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all()
            );

            $data = $mergedRequest->all();
            $data['properties'] = json_decode($data['properties'], true);
            $docs = Doc::create($data);

            // Step 3: Transcribe audio using Whisper
            $whisperResponse = Http::timeout(config('services.openai.whisper_timeout', 30))
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                ])
                ->attach(
                    'file',
                    fopen($filePath, 'r'),
                    $originalName
                )
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => config('services.openai.whisper_model', 'whisper-1'),
                    'language' => 'en',
                ]);

            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            if ($whisperResponse->failed()) {
                throw new \Exception('Audio transcription failed: ' . json_encode($whisperResponse->json()));
            }

            $transcription = $whisperResponse->json()['text'] ?? '';
            if (empty($transcription)) {
                throw new \Exception('Empty transcription received from Whisper API');
            }

            // Diarize the conversation
            $diarizedTranscription = $this->diarizeConversation($transcription);

            // Step 7: Save AI log
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_transcript',
                    'transcript' => $diarizedTranscription,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToTranscriptBasedOnConversation',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'audio_url' => $url,
                ]),
                'status' => 'success',
            ]);

            // Step 8: Return response
            return response()->json([
                'code' => 200,
                'message' => 'SOAP transcription generated successfully',
                'transcript' => $diarizedTranscription,
                'audio_url' => $url,
            ], 200);

        } catch (\Exception $e) {
            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            // Log error for debugging
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'error' => $e->getMessage(),
                    'transcript' => $transcription ?? null,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToTranscriptBasedOnConversation',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                ]),
                'status' => 'error',
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

     /**
     * Convert audio transcription to a SOAP note.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function convertToSOAPTEXT(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'transcript' => 'required|string|max:10000',
            'patient_id' => 'required|integer|exists:patients,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $convertedFilePath = null;
        $transcription = $request->input('transcript'); // Assign transcription from request

        try {
            // Ensure OpenAI API key is set
            // Retrieve patient details
            $patient = Patient::findOrFail($request->input('patient_id'));
            
            // Since additional_info is cast to object, access it directly
            $patientInfo = $patient->additional_info ?? new \stdClass();
            
            // Extract patient details safely
            $patientDetails = [
                'id'                => $patient->id,
                'patient_name'      => trim(($patient->user->first_name  ?? '') . ' ' . ($patient->user->last_name ?? '')) ?: 'Not provided',
                'dob'               => $patient->user->dob ?? 'Not provided',
                'gender'            => $patient->user->gender ?? 'Not provided',
                'blood_group'       => $patient->user->blood_group ?? 'Not provided',
            ];

            // Prepare system prompt for SOAP note
            $systemPrompt = $this->buildSystemPrompt($patientDetails, $transcription);

            // Debug: Uncomment to inspect system prompt
            // dd($systemPrompt);

            // Validate transcription is not empty
            if (empty($transcription)) {
                throw new \Exception('Transcription is empty');
            }

            // Get structured SOAP note from GPT
            $response = Http::timeout(config('services.openai.gpt_timeout', 30))
                ->retry(config('services.openai.retries', 2), 1000)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $transcription],
                    ],
                    'temperature' => config('services.openai.temperature', 0),
                ]);

            if ($response->failed()) {
                throw new \Exception('GPT API request failed: ' . json_encode($response->json()));
            }

            $gptContent = $response->json()['choices'][0]['message']['content'] ?? '';
            if (empty($gptContent)) {
                throw new \Exception('Empty response from GPT API');
            }

            // Clean and parse GPT response
            $cleanedContent = trim($gptContent);
            if (str_starts_with($cleanedContent, '```')) {
                $cleanedContent = preg_replace('/^```(?:json)?\s*([\s\S]*?)\s*```$/', '$1', $cleanedContent);
            }

            $soapData = json_decode($cleanedContent, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($soapData)) {
                throw new \Exception('Failed to parse SOAP JSON: ' . json_last_error_msg());
            }

            // Save AI log
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'response' => $soapData,
                    'transcript' => $transcription,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                    'audio_url' => $url ?? 'Not provided', // Handle undefined $url
                ]),
                'status' => 'success',
            ]);

            // Return response
            return response()->json([
                'code' => 200,
                'message' => 'SOAP note generated successfully',
                'data' => $soapData,
                'transcript' => $transcription,
                'audio_url' => $url ?? 'Not provided', // Handle undefined $url
            ], 200);

        } catch (\Exception $e) {
            // Clean up converted file if it exists
            if ($convertedFilePath && file_exists($convertedFilePath)) {
                unlink($convertedFilePath);
            }

            // Log error for debugging
            AiLog::create([
                'patient_id' => $patient->id,
                'data' => json_encode([
                    'query' => 'voice_to_soap',
                    'error' => $e->getMessage(),
                    'transcript' => $transcription ?? null,
                    'service' => 'OpenAIAudioService',
                    'method' => 'convertToSOAP',
                    'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                ]),
                'status' => 'error',
            ]);

            return response()->json([
                'code' => 500,
                'message' => 'Internal server error',
                'errors' => ['details' => $e->getMessage()],
            ], 500);
        }
    }

    /**
     * Build the system prompt for the SOAP note generation.
     *
     * @param array $patientDetails
     * @return string
     */
    private function buildSystemPrompt(array $patientDetails, string $transcript): string
    {
        // Step 1: Default values for patient details
        $patientDetails = array_merge([
            'id' => '',
            'patient_name' => 'Not provided',
            'dob' => 'Not provided',
            'gender' => 'Not provided',
            'blood_group' => 'Not provided',
        ], $patientDetails);

        $vitalsHint = <<<VITALS
            "vitals": {
                "temperature": {"value": "", "unit": "°F"},
                "blood_pressure": {"value": "", "unit": "mmHg"},
                "heart_rate": {"value": "", "unit": "bpm"},
                "respiratory_rate": {"value": "", "unit": "breaths/min"},
                "spo2": {"value": "", "unit": "%"},
                "weight": {"value": "", "unit": "kg"},
                "height": {"value": "", "unit": "cm"},
                "bmi": {"value": "", "unit": "kg/m²"}
            },
        VITALS;

        // Step 2: Create the system prompt for a human patient
        $prompt = <<<PROMPT
            You are a clinical documentation AI acting as a scribe.
            Your task is to carefully listen to the transcript and RECORD only what is explicitly stated.
            Do not interpret, exaggerate, or infer any medical reasoning.
            Simply map the observed details into the SOAP note JSON structure for a human patient.

            Transcript:
            "{$transcript}"

            Patient Details:
            - ID: {$patientDetails['id']}
            - Name: {$patientDetails['patient_name']}
            - Date of Birth: {$patientDetails['dob']}
            - Gender: {$patientDetails['gender']}
            - Blood Group: {$patientDetails['blood_group']}

            SOAP Format JSON:
            {
                "patient_info": {
                    "id": "{$patientDetails['id']}",
                    "name": "{$patientDetails['patient_name']}",
                    "dob": "{$patientDetails['dob']}",
                    "gender": "{$patientDetails['gender']}",
                    "blood_group": "{$patientDetails['blood_group']}",
                    "encounter_type": "In Person"
                },
                "note_format": "SOAP",
                "soap_sections": {
                    "subjective": {
                        "text_format": "Paragraph",
                        "chief_complaint": "",
                        "history_of_present_illness": "",
                        "past_medical_history": "",
                        "past_surgical_history": "",
                        "past_social_history": "",
                        "family_history": "Not provided",
                        "medications": "",
                        "allergies": "",
                        "review_of_systems": "",
                        "detailed_notes": ""
                    },
                    "objective": {
                        {$vitalsHint}
                        "physical_exam": ""
                    },
                    "assessment": {
                        "combined_with_plan": false,
                        "diagnosis": "",
                        "differential_diagnoses": "",
                        "problem_list": "",
                        "summary_of_condition": "",
                        "diagnostic_results": ""
                    },
                    "plan": {
                        "treatment": "",
                        "medications_prescribed": "",
                        "tests_ordered": "",
                        "referrals": "",
                        "follow_up": "",
                        "patient_instructions": ""
                    }
                }
            }

            Instructions:
            - Only record what is explicitly mentioned in the transcript or patient details.
            - If not mentioned, leave as an empty string ("").
            - Family_history must be "Not provided" unless explicitly mentioned.
            - Do not add interpretations, summaries, or medical reasoning.
            - The output must be valid JSON in the SOAP format.
            PROMPT;

        // Step 3: Return the prompt string
        return $prompt;
    }

    private function diarizeConversation(string $transcription): string
    {
        $roles = Role::orderBy('name')->get()->pluck('code')->toArray();
        $rolesString = implode(', ', $roles); // e.g., "folio, hospital, hospitalgroup, provider, scancentre, admin"
        // Include 'patient' explicitly if it's a common speaker in your context
        if (!in_array('patient', $roles)) {
            $roles[] = 'patient';
            $rolesString = implode(', ', $roles);
        }

        $prompt = <<<PROMPT
            You are a helpful assistant that specializes in analyzing and formatting conversation transcripts.
            Your task is to take a raw transcript and reformat it to clearly distinguish between different speakers.
            Identify the speakers based on the context of the conversation, choosing from the following roles: {$rolesString}.
            If the context strongly suggests a speaker is a patient, label them as 'patient' even if not explicitly mentioned in the transcript.
            For healthcare providers, use 'provider' or other relevant roles from the provided list.
            Output the result strictly as a JSON object with the following structure:
            {
                "diarized_transcript": [
                    {
                        "speaker": "Speaker 1",
                        "dialogue": "Text spoken by Speaker 1"
                    },
                    {
                        "speaker": "Speaker 2",
                        "dialogue": "Text spoken by Speaker 2"
                    }
                ],
                "speakers": ["Speaker 1", "Speaker 2"]
            }

            Ensure the JSON is valid, compact, and contains no unnecessary whitespace or newlines (\n).

            Here is the transcript:
            "{$transcription}"
        PROMPT;

        $response = Http::timeout(config('services.openai.gpt_timeout', 30))
            ->retry(config('services.openai.retries', 2), 1000)
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.gpt_model', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $transcription],
                ],
                'temperature' => config('services.openai.temperature', 0.3),
            ]);

        if ($response->failed()) {
            throw new \Exception('GPT API request failed: ' . json_encode($response->json(), JSON_UNESCAPED_SLASHES));
        }

        $gptContent = $response->json()['choices'][0]['message']['content'] ?? '';
        if (empty($gptContent)) {
            throw new \Exception('Empty response from GPT API');
        }

        // Validate JSON response
        $decoded = json_decode($gptContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from GPT: ' . json_last_error_msg());
        }

        // Ensure the response has the expected structure
        if (!isset($decoded['diarized_transcript']) || !is_array($decoded['diarized_transcript']) || !isset($decoded['speakers']) || !is_array($decoded['speakers'])) {
            throw new \Exception('Invalid response structure from GPT API');
        }

        // Validate that speakers are from the allowed roles
        foreach ($decoded['speakers'] as $speaker) {
            if (!in_array($speaker, $roles)) {
                throw new \Exception("Invalid speaker '$speaker' in response. Allowed roles are: $rolesString");
            }
        }

        // Return compact JSON without newlines or extra whitespace
        return json_encode($decoded, JSON_UNESCAPED_SLASHES);
    }


}