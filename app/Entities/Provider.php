<?php

namespace App\Entities;

use App\Entities\User;
use App\Services\AuthService;
use DB;

class Provider extends BaseModel
{
    const VIEW = true;

    const CREATE = true;

    const UPDATE = true;

    const ACTION = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id",
        "hospital_id",
        "practicing_since",
        "license_no",
        "additional_info",
        "availabilities",
        "group_id"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'additional_info' => 'object',
        'availabilities' => 'object'

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be updated on patch method.
     *
     * @var array
     */
    protected $partialFillable = [
        "availabilities"
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customAvailabilityDetail()
    {
        return $this->hasMany(CustomAvailabilityDetail::class, 'provider_id', 'user_id');
    }


    public function availabilityDetail()
    {
        return $this->hasMany(AvailabilityDetail::class);
    }

    public function providerSpeciality()
    {
        return $this->hasMany(ProviderSpeciality::class);
    }

    public function providerUnavailability()
    {
        return $this->hasMany(ProviderUnavailability::class, 'provider_id', 'user_id');
    }

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);
        $authUser = $request->user();
        DB::beginTransaction();
        try {
            if (!empty($data['availabilities'])) {
                $data['availabilities']  = json_encode($data['availabilities']);
            }

            $data['user']['role_id'] = Role::where("code", $data['user']['role'])->pluck('id')->first();

            $user = $this->user()->create($data['user']);


            if (!$request->get('hospital_id')) {
                if ($authUser->staff) {
                    $data['hospital_id'] = $authUser->staff->hospital_id;
                } else {
                    throw new \Exception('Hospital ID is required or user is not linked to a hospital');
                }
            }

            if ($request->user()->role->code == 'hospitalgroup') {
                $data['group_id'] = $request->user()->staff->group_id;
            } else {
                $data['group_id'] = Hospital::Where('id', $request->user()->staff->hospital_id)->value('group_id');
            }

            $data['user_id'] = $user->id;

            $model = $this->create($data);

            //Provider specialities add
            $provider_speciality = [];
            foreach ($data['provider_speciality'] as $key => $value) {
                $provider_speciality[$key] = ['speciality' => $value];
            }

            $model->providerSpeciality()->createMany($provider_speciality);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Provider Create Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function multipleArraySearch($arrayValue, $exceptList)
    {
        $exceptListKeys = [];
        foreach ($exceptList as $key => $value) {
            $exceptListKeys[] = array_search($value, $arrayValue);
        }
        return $exceptListKeys;
    }

    protected function updateModel($id, $request, $only = [])
    {

        $data = $this->getModelAttributes($request);

        if (!empty($data['availabilities'])) {
            $data['availabilities']  = json_encode($data['availabilities']);
        }

        //remove strict fields

        if ($request->user()->role->code == 'hospitalgroup') {
            $exceptKey = ['user_id'];
        } else {
            $exceptKey = ['user_id', 'hospital_id'];
        }

        $exceptListKey = $this->multipleArraySearch($this->getFillable(), $exceptKey);
        $only = array_except($this->getFillable(), $exceptListKey);

        DB::beginTransaction();
        try {

            $user = User::where('id', $request->get('user_id'))
                ->first();

            $model = parent::updateModel($id, $request, $only);

            if ($request->method() == "PUT") {
                $data['user'] = array_except($data['user'], ['role_id']);
                $model->user->fill($data['user'])
                    ->save(['touch' => false]);

                if (!empty($data['provider_speciality'])) {
                    //Provider specialities delete and add

                    $model->providerSpeciality()->delete();
                    $provider_speciality = [];
                    foreach ($data['provider_speciality'] as $key => $value) {
                        $provider_speciality[$key] = ['speciality' => $value];
                    }

                    if (!empty($provider_speciality)) {
                        $model->providerSpeciality()->createMany($provider_speciality);
                    }
                }
            }

            DB::commit();


            if (!empty($user)) {
                if ($user['is_active'] != 1) {
                    if ($data['user']['is_active'] == 1) {
                        $data['otp_type'] = "provider_activated";

                        $this->_communication_service = new AuthService;
                        $this->_communication_service->otpNotification($data, $user);
                    }
                }
            }

            return $model;
        } catch (Exception $e) {
            exceptionLogger("Provider Update Rollback", $e);
            DB::rollback();
        }

        return null;
    }

    public function applyFilters($model, $isPluck)
    {
        $model = parent::applyFilters($model, $isPluck);
        $request = app('request');

        if ($request->get('staff')) {

            if ($request->get('staff')->hospital_id) {
                $model->where('providers.hospital_id', $request->get('staff')->hospital_id);
            }
            if ($request->get('staff')->group_id) {
                $model->where('providers.group_id', $request->get('staff')->group_id);
            }


            if ($request->get('user_id')) {
                $model->where('providers.user_id', $request->get('user_id'));
            }
        } else {
            $model->where('providers.user_id', $request->user()->id);
        }

        if ($request->get('gender')) {
            $model->whereHas('user', function ($query) use ($request) {
                $query->where('users.gender', $request->get('gender'));
            });
        }

        if ($request->get('speciality')) {
            $model->whereHas('providerSpeciality', function ($query) use ($request) {
                $query->Where('provider_specialities.speciality', $request->get('speciality'));
            });
        }

        if ($request->get('searchkey')) {
            $model->where(function ($query) use ($request) {
                $query->whereHas('user', function ($subquery) use ($request) {
                    $subquery->Where('users.email', 'LIKE', "%" . $request->get('searchkey') . "%")
                        ->orWhere('users.mobile', 'LIKE', "%" . $request->get('searchkey') . "%")
                        ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%" . $request->get('searchkey') . "%")
                        ->orWhere('users.address', 'LIKE', "%" . $request->get('searchkey') . "%")
                        ->orWhere('users.gender', 'LIKE', "%" . $request->get('searchkey') . "%");
                });

                $query->orwhereHas('providerSpeciality', function ($subquery) use ($request) {
                    $subquery->Where('provider_specialities.speciality', 'LIKE', "%" . $request->get('searchkey') . "%");
                });
            });
        }

        return $model;
    }
}
