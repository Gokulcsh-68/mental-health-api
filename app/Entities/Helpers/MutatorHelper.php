<?php

namespace App\Entities\Helpers;

use Carbon\Carbon;

trait MutatorHelper 
{
	public function istToUtcConvert($value)
	{	
		return $value ? dateTimezoneConversion(Carbon::parse($value)) : null;
	}

	public function utcToIstConvert($value)
	{
		return $value ? dateTimezoneConversion(dateTimezoneConversion($value), config('api.timezone')) : null;
	}


	public function setFirstObservedAttribute($value)
	{
		$this->attributes['firstObserved'] = $this->utcToIstConvert($value);
	}

	public function getFirstObservedAttribute($value)
	{
		return $this->istToUtcConvert($value);
	}


	public function getCreatedDatetimeAttribute($value)
	{
		return $this->istToUtcConvert($value);
	}

	public function getUpdatedDatetimeAttribute($value)
	{
		return $this->istToUtcConvert($value);
	}
}
