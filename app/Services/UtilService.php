<?php

namespace App\Services;

use App\Enums\UserTypeEnum;
use App\Services\BaseService;
use App\Traits\S3;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UtilService extends BaseService
{  
    use S3;
    
    /**
     * Post signed url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public  function postSignedUrl(Request $request)
    {
        $path =  config('api.fileSystem.' . $request->get('type')) . $request->get('file_name');
        
        if ($request->attributes->get('patient') && $request->get('type') === 'patient') {
            $path = sprintf($path, $request->attributes->get('patient')->patient_id);
        }
        else{
            $path = sprintf($path, $request->get('type'));
        }

        $url = $this->getAwsTemporaryUrl($path, Carbon::now()->addMinute(10), $this->getUploadOptions($request->get('filetype'), $request->get('content_type')), "post");

        $response['file_path'] = $url;
        $response['file_tmp'] = $path;
        $response['file_name'] = $request->get('file_name');
        return $response;

        // return $this->httpResponse->setHttpData(['url' => $url, 'path' => $path])->jsonResponse();
    }

    /**
     * Get signed url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function getSignedUrl(Request $request): JsonResponse
    {
        $url = $this->getAwsTemporaryUrl($request->query('path'), Carbon::now()->addMinute(10));

        return $this->httpResponse->setHttpData(['url' => $url])->jsonResponse();
    }

    private function getUploadOptions($type, $contentType = null)
    {
        $options = [];
        if ($contentType) {
            $options['ContentType'] = $contentType;
        }
        switch ($type) {
            case 'profile_image':
            case 'item_image':
                $options['ACL'] = 'public-read';
                break;
        }

        return $options;
    }

    public function downloadFromS3(DownloadRequest $request)
    {
        $s3 = app('filesystem')->disk('s3');

        if ($s3->exists($request->get('file_path'))) {

            return $s3->response($request->get('file_path'));
        }

        throw new NotFoundHttpException("File Not Found.");
    }
}
