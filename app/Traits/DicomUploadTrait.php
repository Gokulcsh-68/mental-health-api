<?php

namespace App\Traits;

trait DicomUploadTrait {

	public function initiateDicomUpload($data, $file_extension = 'DCM',$userId)
	{
		$response = [
			'file_path' => false,
			'filename' => false
		];

		$dicom_api_service = new \App\Services\CureselectApis\DicomApiService;
		$upload_response = $dicom_api_service->upload($data);
	
		if($upload_response) {
			$filename = 'A2Z_DICOM_API_'. $upload_response['id'] .'_'.$userId.'_'. \Carbon\Carbon::now()->timestamp .'.' . $file_extension;

			$response['file_path'] = $upload_response['dicom_viewer_url'];
			$response['file_name'] = $filename;
			return $response;
		}
		else {
			throw new \Exception('Something went wrong while uploading to dicom server. Check log file for more details');
		}

		return $response;
	}
}