<?php

namespace App\Entities\Actions;

trait CreateEntity 
{
	/**
     * Model Create.
     *  
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function modelCreateProcess($request): array
    {
        $model = $this->createModel($request);

        $result['success'] = false;
        $result['data'] = [];

        if ($model) {
            $result['success'] = true;
            $result['data'] = $this->modelResponse($model);
        }

        return $result;
    }

    /**
     * Model Create.
     *  
     * @param  $request
     * @return model
    */

    protected function createModel($request)
    {
        $data = $this->getModelAttributes($request);
        return $this->create($data);
    }
}
