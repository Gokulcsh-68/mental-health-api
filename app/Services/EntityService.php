<?php

namespace App\Services;

use App\Entities\ActivityWellness;
use App\Entities\Doc;
use App\Entities\FamilyHistory;
use App\Entities\Form;
use App\Entities\Master;
use App\Entities\Patient;
use App\Entities\PatientHealth;
use App\Entities\PatientHistory;
use App\Entities\PhysicalExamination;
use App\Entities\Provider;
use App\Entities\ReviewOfSystem;
use App\Entities\Role;
use App\Entities\Timezone;
use App\Entities\User;
use App\Entities\Vital;
use App\Jobs\CommunicationJob;
use App\Requests\ChangePasswordRequest;
use App\Requests\CommunicationRequest;
use App\Requests\ConsultTokenValidateRequest;
use App\Requests\ForgotPasswordEmailRequest;
use App\Requests\GeneralLoginRequest;
use App\Requests\ResendOtpRequest;
use App\Requests\TwofaRequest;
use App\Requests\VerifyOtpRequest;
use App\Services\CureselectApis\TeleConsultApiService;
use App\Services\UtilService;
use App\Traits\DicomUploadTrait;
use App\Transformers\UserTransformer;
use App\Utils\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

class EntityService extends BaseService
{


    public function getEntity(Request $request): JsonResponse{
      
        $resource   = $request['resource'];
        $entity     = $request['entity'];

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $data_info    = [];
        $collection   = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        // $data_info['lists']     = $records;

        // $patient_details = $request->get('patient_id')? User::where('id',$request->get('patient_id'))->get(): [];

        // $return = ["data_info" => $data_info, "patient_details"=>$patient_details];

        return $this->httpResponse->setHttpData($records)->jsonResponse();
    }

    function getLimitEntity(Request $request): JsonResponse{
        $resource   = $request['resource'];
        $entity     = $request['entity'];

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $collection = callUserFuncArray([$entity, 'getModelList'], [])->paginate($entity->getResourceDataFetchLimit());

        $records = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        return $this->httpResponse->setHttpData($records)->jsonResponse();
    }

    function getPaginationEntity(Request $request): JsonResponse{
        $resource   = $request['resource'];
        $entity     = $request['entity'];

        $getResourceName = snake_case(camel_case(str_plural($resource)));

        $collection = callUserFuncArray([$entity, 'getModelList'], [])->paginate($entity->getResourceDataFetchLimit());

        $result[$getResourceName] = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];
        $result['pagination'] = $entity->pagination($collection);

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

}

