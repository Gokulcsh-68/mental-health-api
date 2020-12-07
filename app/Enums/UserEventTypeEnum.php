<?php

namespace App\Enums;

class UserEventTypeEnum extends EnumAbstract
{
    const Login = "login";

    const PasswordReset = "password_reset";
    
    const PasswordSet = "password_set";
    
    const PasswordChange = "password_change";
}
