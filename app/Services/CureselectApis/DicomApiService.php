<?php

namespace App\Services\CureselectApis;

use App\Services\CureselectApis\BaseService;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class DicomApiService extends BaseService {
	
	
	public function getFile($id)
	{
		$url = $this->_base_url . 'v1/dicom/view?file_id=' . $id;

		$authorization = "Authorization: Bearer " . $this->getToken();

      	$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$response = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($response, true);


		try {
			if($result['code'] == '200') {
				return $result['data'];
			}
		} catch(\Exception $e) {
			Log::error('DICOM API File get ERROR ------- ', ['errorDetails' => $e->getMessage()]);
		}

		return false;

		// return json_decode($result);
	}

	public function getViewerUrl($id)
	{
		$file = $this->getFile($id);
		if($file && $file['dicom_web_url']) {
			return $file['dicom_web_url'];
		}

		return false;
	}


	public function upload($file)
	{
		//$file =  Input::file('file');
		$filePath = $file->getRealPath();

		$uploadFieldName = 'file';
		$url = $this->_base_url . 'v1/dicom/upload';

		$authorization = "Authorization: Bearer " . $this->getToken();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data' , $authorization ));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$filePath = curl_file_create($file);


		$postFields = array(
		    $uploadFieldName => $filePath,
		);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

		$response = curl_exec($ch);

		if(curl_errno($ch)){
		    throw new \Exception(curl_error($ch));
		}

		$result = json_decode($response, true);
		

		try {
			if($result['code'] == '200') {
				$result['data']['dicom_viewer_url'] = $this->getViewerUrl( $result['data']['id'] );
				return $result['data'];
			}
		} catch(\Exception $e) {
			Log::error('DICOM API File upload ERROR ------- ', ['errorDetails' => $e->getMessage()]);
		}

		return false;
	}

}