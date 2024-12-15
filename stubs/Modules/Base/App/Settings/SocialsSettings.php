<?php

namespace RemiHin\FilamentPrefabReactStubs\Modules\Base\App\Settings;

use Spatie\LaravelSettings\Settings;

class SocialsSettings extends Settings
{
    public ?string $facebook;
    public ?string $twitter;
    public ?string $linkedin;
    public ?string $youtube;
    public ?string $instagram;


    public static function group(): string
    {
        return 'socials';
    }
}
