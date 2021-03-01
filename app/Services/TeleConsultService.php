<?php

namespace App\Services;

use App\Entities\Consult;
use Illuminate\Http\Request;
use App\Services\ResourceService;
use Illuminate\Http\JsonResponse;

class TeleConsultService extends ResourceService
{

    /**
     * Entity List.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    function list(Request $request): JsonResponse{

        $response = app(Consult::class)->getModelList();

      
        $result = [];
        if($response['data']) {
            $result['consults'] = $response['data']['consults'];
            $result['pagination'] = $response['data']['pagination'];
        }

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }
}
