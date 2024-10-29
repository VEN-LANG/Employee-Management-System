<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class UserSettings extends Settings
{
    public static function group(): string
    {
        return 'user';
    }
}
