<?php

namespace App\Enums;

class UserTypeEnum extends EnumAbstract
{
    const SUPERADMIN = "admin";

    const ADMIN = "school";

    const PROVIDER = "provider";

    const STAFF = "staff";

    const PATIENT = "student";
}
