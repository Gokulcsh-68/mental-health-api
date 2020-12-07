<?php

namespace App\Services;

use Carbon\Carbon;
use App\Entities\Master;
use Illuminate\Http\Request;
use App\Enums\UserTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class MasterService extends BaseService
{  
    /**
     * Bulk Master list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function masterList(Request $request): JsonResponse
    {
        $masterTypes = $request->get('master_types');
        $result = [];
        if (is_array($masterTypes)) {
            foreach ($masterTypes as $key => $masterType) {
                $result[$masterType] = Cache::remember("MASTER-CACHE-" . camel_case(strtolower($masterType)), 86400, function() use ($masterType){
                    app('request')->merge(['master_type' => $masterType]);
                    $masterData = app(Master::class)->getModelList()->limit(25)->get();
                    return $masterData->isNotEmpty() ? $this->collectionTransform("Master", $masterData) : [];
                });
            }
        }

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }
}
