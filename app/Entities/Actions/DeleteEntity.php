<?php

namespace App\Entities\Actions;

trait DeleteEntity 
{
	/**
     * Model Delete Process.
     *  
     * @param  \Illuminate\Http\Request  $request
     * @return array
    */

    public function modelDeleteProcess($id, $request): array
    {
        $model = $this->deleteModel($id, $request);

        $result['success'] = false;
        $result['data'] = [];

        if ($model) {
            $result['success'] = true;
            $result['data'] = (object)[
                "id" => (int) $id
            ];
        }

        return $result;
    }

    /**
     * Model Delete.
     *  
     * @param  $request
     * @return model
    */

    protected function deleteModel($id, $request)
    {
        return $this->getModel($id)->delete();
    }
}
