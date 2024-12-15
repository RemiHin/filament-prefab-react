<?php

namespace RemiHin\FilamentPrefabReactStubs\Modules\Base\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function App\Console\Commands\public_path;
use function App\Console\Commands\resource_path;

class SymlinkSvg extends Command
{
    protected $signature = 'svg:link';
    protected $description = 'Create a symbolic link from resources/images/svg to public/images/svg';

    public function handle()
    {
        // Create resources/images/svg if it doesn't exist
        if (!File::exists(resource_path('images/svg'))) {
            File::makeDirectory(resource_path('images/svg'), 0755, true);
            $this->info('Created directory [resources/images/svg]');
        }

        // Create public/images if it doesn't exist
        if (!File::exists(public_path('images'))) {
            File::makeDirectory(public_path('images'), 0755, true);
            $this->info('Created directory [public/images]');
        }

        // Remove existing svg directory or symlink in public/images
        if (File::exists(public_path('images/svg'))) {
            File::deleteDirectory(public_path('images/svg'));
            $this->info('Removed existing [public/images/svg]');
        }

        // Create the symlink
        try {
            File::link(
                resource_path('images/svg'),
                public_path('images/svg')
            );
            $this->info('The [resources/images/svg] directory has been linked with [public/images/svg]');
        } catch (\Exception $e) {
            $this->error('Failed to create symlink: ' . $e->getMessage());
        }
    }
}
