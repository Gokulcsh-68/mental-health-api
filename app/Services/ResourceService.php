<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceService extends BaseService
{
    /**
     * Entity Create.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function create(Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $requestClass = sprintf('\App\Requests\%sRequest', $resource);
        class_exists($requestClass) ? app()->make($requestClass) : $request;
        $model = callUserFuncArray([$entity, 'modelCreateProcess'], [$request]);

        return $this->httpResponse->setHttpData([$this->getResourceName($request) => $model["data"]])->setHttpCode($model["success"] ? 201 : 400)->setHttpMessage($model["success"] ? "" : "Unable to create model")->jsonResponse();
    }

    /**
     * Entity List.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    function list(Request $request): JsonResponse{
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->paginate($entity->getResourceDataFetchLimit());

        $result[$this->getResourceName($request)] = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];
        $result['pagination'] = $entity->pagination($collection);

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    /**
     * Get All.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function getAll(Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->get();

        $result[$this->getResourceName($request)] = $collection->isNotEmpty() ? $this->collectionTransform($resource, $collection) : [];

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    /**
     * First Entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function getFirst(Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->first();

        $result[$this->getResourceName($request)] = $collection ? $this->collectionTransform($resource, $collection)[0] : (object) [];

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    /**
     * Entity Fetch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function fetch($id, Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        app("request")->merge(["id" => $id]);
        $collection = callUserFuncArray([$entity, 'getModelList'], [])->firstOrFail();
        $result[$this->getResourceName($request)] = (object) $this->collectionTransform($resource, $collection, 'detail')[0];

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    /**
     * Entity Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function update($id, Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $requestClass = sprintf('\App\Requests\%sRequest', $resource);
        class_exists($requestClass) ? app()->make($requestClass) : $request;
        $model = callUserFuncArray([$entity, 'modelUpdateProcess'], [$id, $request]);

        return $this->httpResponse->setHttpData([$this->getResourceName($request) => $model["data"]])->setHttpCode($model["success"] ? 200 : 400)->setHttpMessage($model["success"] ? "" : "Unable to update model")->jsonResponse();
    }

    /**
     * Entity Partial Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function partialUpdate($id, Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $model = callUserFuncArray([$entity, 'modelUpdateProcess'], [$id, $request, $entity->getParitialFillable()]);

        return $this->httpResponse->setHttpData([$this->getResourceName($request) => $model["data"]])->setHttpCode($model["success"] ? 200 : 400)->setHttpMessage($model["success"] ? "" : "Unable to update model")->jsonResponse();
    }

    /**
     * Entity Delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function delete($id, Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $model = callUserFuncArray([$entity, 'modelDeleteProcess'], [$id, $request]);

        return $this->httpResponse->setHttpData([$this->getResourceName($request) => $model["data"]])->setHttpCode($model["success"] ? 200 : 400)->setHttpMessage($model["success"] ? "" : "Unable to delete model")->jsonResponse();
    }

    /**
     * Entity aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return json
     */

    public function aggregate(Request $request): JsonResponse
    {
        $resource = $request->attributes->get('resource');
        $entity = $request->attributes->get('entity');
        $count = callUserFuncArray([$entity, 'getModelList'], [])->count();
        $result[$this->getResourceName($request)] = $count;

        return $this->httpResponse->setHttpData($result)->jsonResponse();
    }

    private function getResourceName($request): string
    {
        return snake_case(camel_case(str_plural($request->attributes->get('resource'))));
    }
}
