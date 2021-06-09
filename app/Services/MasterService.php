<?php

namespace App\Services;

use Carbon\Carbon;
use App\Entities\Master;
use App\Entities\CustomMaster;
use Illuminate\Http\Request;
use App\Enums\UserTypeEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Entities\Timezone;

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
                $result[$masterType] = $this->getMasterData($masterType);
            }
        }

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    public function getMasterData($masterType)
    {
        return Cache::remember("MASTER-CACHE-" . camel_case(strtolower($masterType)), 86400, function() use ($masterType){
                    app('request')->merge(['master_type' => $masterType]);
                    $masterData = app(Master::class)->getModelList()->get();
                    return $masterData->isNotEmpty() ? $this->collectionTransform("Master", $masterData) : [];
                });
    }

    public function customMasterList(Request $request): JsonResponse
    {
        $slug   = $request->get('slug');
        $result = [];

        $result['masters'] = $this->getCustomMasterData($slug, $request);

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    public function getCustomMasterData($masterType, $request){

        $limit = (!empty($request->get('limit')) ? $request->get('limit') : 10);

        $customMasterData = app(CustomMaster::class)->getModelList()->paginate($limit);

        if ($customMasterData->count() < $limit) {
            $customMasterData = $customMasterData->merge(app(Master::class)->getModelList()->paginate($limit - $customMasterData->count()));
        }


        $result = $customMasterData->isNotEmpty() ? $this->collectionTransform("CustomMaster", $customMasterData) : [];

        return $result;
    }
}
