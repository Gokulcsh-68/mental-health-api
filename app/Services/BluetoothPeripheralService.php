<?php
namespace App\Services;

use App\Entities\User;
use App\Utils\AuthHelper;
use Illuminate\Http\Request;
use Log;

class BluetoothPeripheralService extends BaseService
{

    use AuthHelper;

    public function autoLogin(Request $request)
    {
        $requestedData = $request->json()->all();
        $model = User::where('id', $requestedData['user_id'])
            ->whereHas('role', function ($query) {
                $query->where('code', 'folio');
            })
            ->first();

        if (isset($model->id)) {
            $result = [];
            $result['info'] = array_only($model->getBasicInfo(), ['id', 'name', 'gender']);
            $Authorization = $result['token'] = $this->getAuthorization(['userId' => $model->id]);
            $token_details = $this->decodeJwt($Authorization);
            if ($token_details->exp) {
                $result['token_expiration_time'] = $token_details->exp;
            }

            $result['status'] = 'verified_user';

            return $this->httpResponse->setHttpData($result)
                ->setHttpHeader(['Authorization' => $Authorization])
                ->jsonResponse();
        } else {
            $message = trans('auth.failed');
            return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
        }
    }

    public function login(Request $request)
    {
        $requestedData = $request->json()->all();
        $model = User::where('id', $requestedData['username'])
            ->whereHas('role', function ($query) {
                $query->where('code', 'folio');
            })
            ->first();

        if (isset($model->id) && $model->isValidPeripheralPassword($requestedData)) {
            $result = [];
            $result['info'] = array_only($model->getBasicInfo(), ['id', 'name', 'gender']);

            $Authorization = $result['token'] = $this->getAuthorization(['userId' => $model->id]);

            $token_details = $this->decodeJwt($Authorization);

            if ($token_details->exp) {
                $result['token_expiration_time'] = $token_details->exp;
            }

            $result['status'] = 'verified_user';

            return $this->httpResponse->setHttpData($result)
                ->setHttpHeader(['Authorization' => $Authorization])
                ->jsonResponse();

        } else {
            $message = trans('auth.failed');
            return $this->httpResponse->setHttpMessage($message)->setHttpCode(401)->jsonResponse();
        }
    }

    public function capture(Request $request)
    {
        Log::debug('EVITALZ', ['data' => $request->all()]);
    }
}
