<?php

namespace App\Enums;

class ConsultStatusTypeEnum extends EnumAbstract
{
    const FRESH = "New";
    
    const FAILED = "Failed";
    
    const WAITING = "Waiting";

    const ENDED = "Ended";

    const STARTED = "Started";
}
