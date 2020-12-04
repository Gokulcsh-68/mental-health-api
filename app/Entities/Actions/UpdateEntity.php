<?php

namespace App\Entities\Actions;

trait UpdateEntity 
{
	/**
     * Model Update Process.
     *  
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function modelUpdateProcess($id, $request, $only = []): array
    {
        $model = $this->updateModel($id, $request, $only);

        $result['success'] = false;
        $result['data'] = [];

        if ($model) {
            $result['success'] = true;
            $result['data'] = $this->modelResponse($model);
        }

        return $result;
    }

    /**
     * Model Update.
     *  
     * @param  $request
     * @return model
    */

    protected function updateModel($id, $request, $only = [])
    {
        $instance = $this->getModel($id);
        $instance->fill($this->getModelAttributes($request, $only));
        $instance->save(['touch' => false]);

        return $instance;
    }
}
