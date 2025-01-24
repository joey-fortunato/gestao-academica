<?php

namespace App\Helpers;

class PermissionHelper
{
    public static function getFriendlyName($permission)
    {
        return config('permissions')[$permission] ?? $permission;
    }
}
