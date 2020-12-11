<?php

namespace App\Observers;

use Carbon\Carbon;
use lfkeitel\phptotp\{Totp,Base32};
use Illuminate\Http\Request;

class UserObserver
{
    public function creating($model)
    {
        $model->secret = Base32::encode(Totp::GenerateSecret(16));
    }
}
