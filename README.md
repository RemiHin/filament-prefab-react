# Prefab

Use this template to scaffold a new website

## Installation

1. Create a new project `laravel new project-name`
2. clone this repository
3. update the `composer.json` of your new project and change minimum stability to dev: `"minimum-stability": "dev",` and add the following:
```json
"repositories": {
        "filament-prefab-react": {
            "type": "path",
            "url": "../<path-to>/filament-prefab-react",
            "symlink": true
        }
    },
```
4. `composer require remihin/filament-prefab-react`
5. Install all modules:
- `php artisan prefab:filament --module=base --force`
  - be patient with the shell script, force is required to overwrite the user model
- NOTE: When updating modules after their initial rollout add `--force` to override local files. Additionally `--no-shell` can be added to prevent shell commands from being executed to speed up rolling out updates.
6. `composer dump`
7. `php artisan migrate`
8. `php artisan make:filament-user` to create a user follow the prompts
9. `php artisan db:seed`
10. `php artisan svg:link` to make the icon picker work in React
11. `npm install && npm run dev`

### How to use Seoable en Ogable
1. add the `use Seoable`trait to the model
2. add `static::$model::seoFields(),` to the form fields in the resource

### How to use Labels
1. add the `use Labelable` trait to the model
2. add `static::$model::labelableFields(),` to the form fields in the resource

### How to use menus
1. Implement `App/Contracts/Menuable` on models that should be able to be linked in menus.
2. Implement required methods
3. Available resources will be auto detected by the menu item resource

### How to use icon picker
1. create a folder in  `/resources/images/svg` (if this folder is empty it will not be committed, so add atleast 1 icon)
2. Place all the SVGs voor de icons you want in this folder (make sure the name of the file is prefixed with `icon-`)
3. add the IconPicker to a resource (with preload):
```php
IconPicker::make('icon')
    ->preload()
```
4. in the React views use `<DynamicIcon name={page.icon} className="w-6 h-6"/>`

### How to use titles and slugs
1. For titles and slugs we use a forked and self-hosted project [filament-title-with-slug](https://github.com/MotivoZwolle/filament-title-with-slug)
2. On forms use the `TitleWithSlugInput` form component. This will handle both the title and the slug. Both fields are required and the slug field validates if it is unique.
3. For more documentation checkout [the motivo repository](https://github.com/MotivoZwolle/filament-title-with-slug)

### How to use blocks module
1. Simple add `BlockModule::make('content')` to any resource, where the param is the name of the column which stores the data.
2. Add `'content' => 'array'` to the casts of the model
3. New blocks can be created by making a new class in the `App/Filament/Plugins/Blocks` directory and extending the `BaseBlock` model
4. New blocks can be registered in the `active` array in the `blocks.php` config file
5. There is also a toggle content field, which can have nested fields. These are registed in the `toggle_content` array in the `blocks.php` config
6. You can also create your own set of blocks.
   1. First create a new array in the `blocks.php` config file. The key of this array is not restricted.
   2. When adding the block module to the resource you can specify a second parameter, which is the key of the array from the previous step, for example `BlockModule::make('content', 'form-builder')`

### Settings
1. For settings we use the [spatie plugin](https://filamentphp.com/plugins/filament-spatie-settings).
2. Optional: add the filament page to the correct navigation group `protected static ?string $navigationGroup = 'settings';`
3. Add the settings to the share function in `HandleInertiaRequests` to access variables in the views
