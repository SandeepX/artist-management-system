<?php

namespace App\Helper;

class AppHelper
{
    public static function getAuthUserId()
    {
        return auth()->user()->id;
    }

    public static function getAuthUserRole()
    {
        return auth()->user()->role;
    }

}
