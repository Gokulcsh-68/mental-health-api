<?php

namespace App\Services\RijuvenApis;

use App\Entities\Doc;
use App\Entities\Patient;
use App\Entities\User;
use App\Entities\Vital;
use App\Traits\S3;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebhookService
{
	use S3;

	public function handle(Request $request): JsonResponse {
		$result = [
			'success' => 1,
			'message' => 'OK'
		];

		$data = $request->all();

		$log_id = DB::table('rijuven_api_log')->insertGetId([
			'data' => json_encode($data),
			'created_at' => Carbon::now(),
		]);

		if(!empty($data['test_type'])) {

			$doc_id = '-1';

			$vital_data = array();

			$rijuven_patient_log = $this->getRijuvenPatientFromDB($data['patient_id']);

			$uid = Patient::where('id', $rijuven_patient_log->patient_id)->first()->user_id;

			if(!$rijuven_patient_log) {
				$result['success'] = 0;
				$result['message'] = 'Patient not found';
				// update to our log
				DB::table('rijuven_api_log')
					->where('id', $log_id)
					->update([
						'notes' => 'Patient not found',
						'status' => 0,
						'updated_at' => Carbon::now(),
					]);
				return response()->json($result);
			}

			if($data['test_type'] == 'ecg') {
				$notes = '';
				foreach ($data['tests'] as $test) {
					$notes .= sprintf('%s - %s, ', $test['location'], $test['ecg_description_short']);
				}
				$processed_data = [
					'user_id' => $uid,
					'document_source' => 'imaging',
					'created_by' => 1,
					'addition_info' => [
						'notes' => rtrim($notes, ','),
						'title' => 'ECG - Rijuven',
						'document_link' => $data['report_url'],
					],
					'properties' => $this->uploadToS3($data['report_url'], sprintf('%s-%s',$data['patient_id'],$data['session_id']), $ext = '.pdf'),

				];

                $response_doc = $this->saveDocData($processed_data);
				$doc_id = $response_doc['data']['id'];
				$vital_data['details']['device_type'] = '3 Lead ECG';

			} else if($data['test_type'] == 'auscultation') {
				$notes = '';
				foreach ($data['tests'] as $test) {
					$notes .= sprintf('%s - %s (%s), ', $test['location'], $test["diagnosis_translation"], $test['ecg_description_short']);
				}
				$processed_data = [
					'user_id' => $uid,
					'document_source' => 'imaging',
					'created_by' => 1,
					'addition_info' => [
						'notes' => rtrim($notes, ','),
						'title' => 'Auscultation - Rijuven',
						'document_link' => $data['report_url'],
					],
					'properties' => $this->uploadToS3($data['report_url'], sprintf('%s-%s',$data['patient_id'],$data['session_id']), $ext = '.pdf'),
				];
                $response_doc = $this->saveDocData($processed_data);

				$doc_id = $response_doc['data']['id'];

				$vital_data['details']['device_type'] = 'Auscultation';

			} else if($data['test_type'] == 'cardiac_function') {
				$processed_data = [
					'user_id' => $uid,
					'document_source' => 'imaging',
					'created_by' => 1,
					'addition_info' => [
						'notes' => $data['location'],
						'title' => 'Cardiac Function - Rijuven',
						'document_link' => $data['report_url'],
					],
					'properties' => $this->uploadToS3($data['report_url'], sprintf('%s-%s',$data['patient_id'],$data['session_id']), $ext = '.pdf'),
				];
                $response_doc = $this->saveDocData($processed_data);

				$doc_id = $response_doc['data']['id'];

				$vital_data['details']['device_type'] = 'Cardiac Function Test';
			}

			if($doc_id > 0){

				$dateOfBirth = User::Where('id', $uid)->value('dob');

				$years  = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%y');
				$months = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%m');
				$days   = Carbon::parse($dateOfBirth)->diff(Carbon::now())->format('%d');

				$vital_data['slug'] = 'heart-rate';
				$vital_data['user_id'] = $uid;
				$vital_data['details']['date'] = Carbon::now()->format('Y-m-d');
				$vital_data['details']['time'] = Carbon::now()->format('H:i:s');
				$vital_data['details']['unit'] = 'Bpm';
				$vital_data['details']['heart'] = '0';
				$vital_data['details'] += Vital::heart_rate_flag($vital_data['details'], $years, $months, $days);
				$vital_data['details']['doc_id'] = $doc_id;

				Vital::create($vital_data);

			}

			DB::table('rijuven_api_log')
				->where('id', $log_id)
				->update([
					'action' => $data['test_type'],
					'status' => 1,
					'patient_id' => $rijuven_patient_log->patient_id,
					'rijuven_patient_id' => $rijuven_patient_log->rijuven_patient_id,
					'updated_at' => Carbon::now(),
				]);

		} else if(!empty($data['patient_identifier']) && !empty($data['name_first']) && !empty($data['name_last']) && !empty($data['id'])) {

			//  check rijuven patient id exist in our log
			if($this->getRijuvenPatientFromDB($data['id'])) {
				return response()->json($result);
			}

			// Check patient already exist in our patient list
			$patient = $this->checkPatientExist($data);
			if(!$patient) {
				$result['success'] = 0;
				$result['message'] = 'Patient not found';
				// update to our log
				DB::table('rijuven_api_log')
					->where('id', $log_id)
					->update([
						'notes' => 'Patient not found',
						'status' => 0,
						'updated_at' => Carbon::now(),
					]);
				return response()->json($result);
			}

			// update patient reference detail in log
			DB::table('rijuven_api_log')
				->where('id', $log_id)
				->update([
					'action' => 'patient',
					'status' => 1,
					'patient_id' => $patient->id,
					'rijuven_patient_id' => $data['id'],
					'updated_at' => Carbon::now(),
				]);
			return response()->json($result);
		}

		return response()->json($result);
	}

	private function checkPatientExist($data) {
		return Patient::with('user')
			->whereHas('user', function($q) use ($data) {
				$q->where('first_name', $data['name_first'])
				->where('last_name', $data['name_last']);
			})
			->where('additional_info->mrn_number', $data['patient_identifier'])
			->first();
	}

	private function getRijuvenPatientFromDB($id) {
		return DB::table('rijuven_api_log')
		->where('action', 'patient')
		->where('rijuven_patient_id', $id)
		->first();
	}

	protected function saveDocData($data)
	{
		$new_request = new Request();
		$new_request->merge($data);
		$new_request->setJson($new_request);

		$doc_id = (new Doc)->modelCreateProcess($new_request);
        return $doc_id;
	}

	protected function uploadToS3($remotePath, $filenamePrefix, $ext = '.pdf'): array {
		$upload_path = config('api.fileSystem.rijuven');
		$filenamePrefix .= md5(time() . uniqid(rand(), true));

		$s3_upload_response = $this->diskStorageFromExternal($remotePath, $upload_path, $filenamePrefix, $ext, "private");
		return [
			'file_name' => $s3_upload_response['filename'],
			'file_path' => $s3_upload_response['fullPath'],
			's3_upload' => 1,
			's3_signed_url' => $s3_upload_response['url'],
		];
	}
}
