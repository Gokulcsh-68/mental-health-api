<?php

namespace App\Services;

use App\Utils\HttpResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Lumen\Routing\Controller as BaseController;

class BaseService extends BaseController
{
	protected $httpResponse;

	public function __construct()
	{
		$this->httpResponse = app(HttpResponse::class);
	}

	public function collectionTransform($collectionName, $collection, $type = "list")
	{
		$transformed = [];
		$className = "";		
		if ($type === 'detail') {
			$className = sprintf("\\App\\Transformers\\DetailTransformers\\%sDetailTransformer",  $collectionName);
		}

		if (!class_exists($className)) {
			$className = sprintf("\\App\\Transformers\\%sTransformer",  $collectionName);
		}

		if (class_exists($className) && !app('request')->has('pluck')) {
			if ($collection instanceof LengthAwarePaginator || $collection instanceof Collection) {
				$transformed = $className::collection($collection);
			} else {
				$transformed = [new $className($collection)];
			}
		} else if ($collection instanceof LengthAwarePaginator) {
			$transformed = $collection->items();
		} else {
			$transformed = [$collection];
		}

		return $transformed;
	}
}
