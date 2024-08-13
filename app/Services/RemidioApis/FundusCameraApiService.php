<?php

namespace App\Services\RemidioApis;

use App\Entities\Doc;
use App\Entities\Patient;
use App\Services\RemidioApis\BaseService;
use App\Traits\S3;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FundusCameraApiService extends BaseService
{

	use S3;
	private $_model;

	public function __construct()
	{
		parent::__construct();
		$this->_model = new Doc;
	}

	public function getElementFromDownloadQueue()
	{			
		try {

			$url = $this->_base_url . '/api/gateway/getQueueItem';

			$options = [
				'headers' => $this->getHeadersWithClientToken(),
			];

			$this->apiCall($url, $options);
			$api_response = $this->toGuzzleArray();

			// find user
			$patient = $api_response['data']['patient'];
			$patient_model = Patient::with('user')
			->whereHas('user', function($q) use ($patient) {
				$q->where('first_name', $patient['firstName'])
				->where('last_name', $patient['lastName']);
			})
			->where('additional_info->mrn_number', $patient['mrn'])
			->first();

			if(!$patient_model) {
				$log_id = DB::table('remidio_fundus_api_log')->insertGetId([
					'data' => json_encode($api_response),
					'created_at' => Carbon::now(),
					'notes' => 'Patient not found',
				]);
				Log::info('REMIDIO PATIENT NOT FOUND ------- ' . $log_id, []);
				$this->elementQueueNext();
				return;
			}

			// Store response to log
			$log_id = DB::table('remidio_fundus_api_log')->insertGetId([
				'data' => json_encode($api_response),
				'created_at' => Carbon::now(),
			]);

			// Process data
			$processed_data = $this->processData($api_response['data'], $patient_model);

			// Save to doc
			$this->saveDocData($processed_data);

			DB::table('remidio_fundus_api_log')
				->where('id', $log_id)
				->update([
					'status' => 1,
					'updated_at' => Carbon::now(),
				]);

			Log::info('REMIDIO QUEUE IMPORTED ------- ' . $log_id, []);

			// complete process
			$this->elementQueueNext();

			// dd($api_response, $log_id);

		} catch (\Exception $e) {

			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				'trace' => $e->getTraceAsString(),
			];
			// dd($error);
			// dd($e->getMessage());
			Log::error('REMIDIO PROCESS COMPLETE API ERROR ------- ', $error);

			throw new BadRequestHttpException($e->getMessage(), $e);
			// $response = [$e->getMessage()];
		}

		// return $response;
	}

	protected function elementQueueNext() {
		try {

			$url = $this->_base_url . '/api/gateway/itemSuccessfullyHandled';

			$options = [
				'headers' => $this->getHeadersWithClientToken(),
			];

			$this->apiCall($url, $options, 'POST');
			$api_response = $this->toGuzzleArray();

			if(!empty($api_response['status']) && $api_response['status']['statusCode'] == 'OK') {
				$this->getElementFromDownloadQueue();
			}

		} catch (\Exception $e) {

			$error = [
				'code' => $this->error->getCode(),
				'message' => $this->error->getMessage(),
				'trace' => $e->getTraceAsString(),
			];
			// dd($error);
			// dd($e->getMessage());
			Log::error('REMIDIO QUEUE COMPLETE API ERROR ------- ', $error);

			throw new BadRequestHttpException($e->getMessage(), $e);
			// $response = [$e->getMessage()];
		}

		// return $response;
	}

	protected function processData($data, $patient): array
	{
		$upload_path = config('api.fileSystem.remidio');
		$filenamePrefix = '';

		$insert_data = [
			'user_id' => $patient->user_id,
			'document_source' => 'imaging',
			'created_by' => 1,
		];

		switch ($data['type']) {
			case 'IMAGE':
				$ext = 'jpeg';
				$remotePath = $data['image']['path'];
				$filenamePrefix = sprintf('Fundus_%s_%s', $data['image']['laterality'], $data['image']['field']);

				$insert_data['addition_info'] = [
					'notes' => $data['image']['quality'],
					'title' => sprintf('Fundus_%s_%s', $data['image']['laterality'], $data['image']['field']),
					'disc_quality_results' => $data['image']['discQualityResults'],
				];
				break;
			case 'AI_REPORT_V2':
				$ext = 'pdf';
				$remotePath = $data['aiReportV2']['path'];
				$filenamePrefix = sprintf('Fundus_%s', $data['type']);

				$insert_data['addition_info'] = [
					'notes' => '',
					'title' => sprintf('Fundus_%s', $data['type']),
					'dr_result' => $data['aiReportV2']['drResult'],
					'amd_result' => $data['aiReportV2']['amdResult'],
					'gma_result' => $data['aiReportV2']['gmaResult'],
				];
				break;
			case 'DOCTOR_REPORT':
				$ext = 'pdf';
				$remotePath = $data['report']['path'];
				$filenamePrefix = sprintf('Fundus_%s', $data['type']);

				$insert_data['addition_info'] = [
					'notes' => '',
					'title' => sprintf('Fundus_%s', $data['type']),
					'left_eye_diagnosis' => $data['report']['leftEyeDiagnosis'],
					'right_eye_diagnosis' => $data['report']['rightEyeDiagnosis'],
				];
				break;			
		}

		$filenamePrefix .= md5(time() . uniqid(rand(), true));

		$s3_upload_response = $this->diskStorageFromExternal($remotePath, $upload_path, $filenamePrefix, $ext, "private");
		$insert_data['properties'] = [
			'file_name' => $s3_upload_response['filename'],
			'file_path' => $s3_upload_response['fullPath'],
			's3_upload' => 1,
			's3_signed_url' => $s3_upload_response['url'],
		];

		return $insert_data;
	}

	protected function saveDocData($data)
	{
		$new_request = new Request();
		$new_request->merge($data);
		$new_request->setJson($new_request);

		$this->_model->modelCreateProcess($new_request);
	}
}
