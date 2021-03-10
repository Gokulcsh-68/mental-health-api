<?php

namespace App\Entities;

use App\Entities\Actions\CreateEntity;
use App\Entities\Actions\DeleteEntity;
use App\Entities\Actions\GetEntity;
use App\Entities\Actions\UpdateEntity;
use App\Entities\Helpers\MutatorHelper;
use App\Events\AuditLog;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use CreateEntity,
    GetEntity,
    UpdateEntity,
    DeleteEntity,
        MutatorHelper;

    const VIEW = false;

    const CREATE = false;

    const UPDATE = false;

    const DELETE = false;

    const ACTION = false;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $partialFillable = [];

    protected $selectedColumns = ['*'];

    /*Temp Fix*/

    const UPDATED_AT = "updated_at";

    const CREATED_AT = "created_at";

    public static function boot()
    {
        // static::updated(function ($model) {
        //     if ($model->isFillable('updated_by') && app('request')->attributes->get('user') && count(array_except($model->getDirty(), ['updated_by', 'updated_datetime'])) > 0) {
        //         logInfo("AuditLog updated => " . $model->getTable());
        //         event(new AuditLog($model, "update"));
        //     }
        // });

        // static::created(function ($model) {
        //     if ($model->isFillable('created_by') && app('request')->attributes->get('user')) {
        //         logInfo("AuditLog created => " . $model->getTable());
        //         event(new AuditLog($model, "create"));
        //     }
        // });

        // static::deleted(function ($model) {
        //     if ($model->isFillable('deleted_by') && app('request')->attributes->get('user')) {
        //         logInfo("AuditLog deleted => " . $model->getTable());
        //         event(new AuditLog($model, "delete"));
        //     }
        // });

        static::creating(function ($model) {
            if ($model->isFillable('created_by') && app('request')->attributes->get('user')) {
                $model->created_by = app('request')->attributes->get('user')->id;
            }
        });

        static::updating(function ($model) {
            if ($model->isFillable('updated_by') && app('request')->attributes->get('user')) {
                $model->updated_by = app('request')->attributes->get('user')->id;
            }
        });

        /*static::deleting(function ($model) {
        if ($model->isFillable('deleted_by') && app('request')->attributes->get('user')) {
        logInfo("deleting => " . $model->getTable());
        $model->deleted_by = app('request')->attributes->get('user')->id;
        $model->save();
        }
        });*/

        parent::boot();
    }

    public function canDoAction($action): bool
    {
        return $this->getConstant($action) || $this->getConstant("ACTION");
    }

    private function getConstant($action): bool
    {
        $process = constant("self::" . $action);
        if (defined("static::" . $action)) {
            $process = constant("static::" . $action);
        }

        return $process;
    }

    public function getParitialFillable(): array
    {
        return $this->partialFillable;
    }

    /**
     * Model Response.
     *
     * @param  $model
     * @return array
     */

    protected function modelResponse($model): array
    {
        return ["id" => $model->getKey()];
    }

    /**
     * Model Addtiional Process.
     *
     * @return collections
     */

    public function applyGlobalConditions($model)
    {
        // if ($this->isFillable('patient_id') && app('request')->attributes->get('patient')) {
        //     static::addGlobalScope(new PatientScope);
        // }

        // if ($this->isFillable('provider_id') && app('request')->attributes->get('provider')) {
        //     static::addGlobalScope(new ProviderScope);
        // }

        return $model;
    }

    public function getModel($id)
    {
        $model = $this->applyGlobalConditions($this);

        return $model->where($this->getKeyName(), $id)->firstOrFail();
    }

    public function getModelAttributes($request, $only = []): array
    {
        if (empty($only) === false) {
            $data = $request->only($only);
        } else {
            $data = $request->json()->all();

            // if ($this->isFillable('patient_id') && $request->attributes->get('patient')) {
            //     $data['patient_id'] = $request->attributes->get('patient')->getKey();
            // }

            // if ($this->isFillable('provider_id') && $request->attributes->get('provider')) {
            //     $data['provider_id'] = $request->attributes->get('provider')->getKey();
            // }

            // if ($this->isFillable('consult_id') && $request->attributes->get('consult')) {
            //     $data['consult_id'] = $request->attributes->get('consult')->getKey();
            // }
        }

        return $data;
    }

    public function isActive(): bool
    {
        return $this->is_active ? true : false;
    }
}
