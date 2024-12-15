<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('contact.street');
        $this->migrator->add('contact.house_number');
        $this->migrator->add('contact.city');
        $this->migrator->add('contact.postcode');

        $this->migrator->add('contact.email');
        $this->migrator->add('contact.phone');

        $this->migrator->add('contact.admin_name');
        $this->migrator->add('contact.admin_email');
    }
};
