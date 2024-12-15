<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ContactSettings extends Settings
{
    public ?string $street;
    public ?string $house_number;
    public ?string $city;
    public ?string $postcode;
    public ?string $email;
    public ?string $phone;

    public ?string $admin_name;
    public ?string $admin_email;

    public static function group(): string
    {
        return 'contact';
    }
}
