<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('socials.facebook', '');
        $this->migrator->add('socials.linkedin', '');
        $this->migrator->add('socials.twitter', '');
        $this->migrator->add('socials.youtube', '');
        $this->migrator->add('socials.instagram', '');
    }
};
