<?php

namespace RemiHin\FilamentPrefabReact\Console;

use App\Filament\Plugins\Blocks\StoryBlock;
use App\Models\Blog;
use App\Models\Page;
use App\Models\NewsItem;
//use App\Models\Location;
//use App\Models\Service;
use App\Models\Service;
use App\Models\Story;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class PrefabCommand extends Command
{
    protected $signature = 'prefab:filament-react
        {--M|module= : Define a single module to prefab.}
        {--B|bundle= : Define a bundle to prefab. Available bundles: care and non-profit.}
        {--all}
        {--no-shell}
        {--force}';

    protected $description = 'Install prefab Filament modules and bundles';

    /**
     * List of supported modules.
     */
    public array $modules = [
        'base',
    ];

    protected ?string $module;

    protected ?string $bundle;

    protected bool $all = false;

    protected Filesystem $filesystem;

    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle(): void
    {
        $this->setGivenOptions($this->options());

        if ($this->all) {
            $this->allModules();

            return;
        } elseif ($this->module) {
            $this->prefabModule($this->module);

            return;
        } elseif ($this->bundle) {
            $this->prefabBundle($this->bundle);

            return;
        }

        $this->error('Provide a module or bundle to install.');
        $this->comment('Execute the "php artisan filament:prefab --help" command to see the available options.');
    }

    /**
     * Return an array with all available bundles.
     * @return array
     */
    protected function getAvailableBundles(): array
    {
        return array_keys($this->bundles);
    }

    /**
     * Return an array with all available modules.
     * @return array
     */
    protected function getAvailableModules(): array
    {
        return $this->modules;
    }

    /**
     * Return an array with all modules from a bundle.
     * @param string $bundle
     * @return array
     */
    protected function getModulesFromBundle(string $bundle): array
    {
        return $this->bundles[$bundle] ?? [];
    }

    /**
     * Publish all prefab modules.
     *
     * @return void
     */
    protected function allModules(): void
    {
        foreach ($this->modules as $module) {
            $this->prefabModule($module);
        }

        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    /**
     * Prefab a single module.
     * @param string $module
     * @return void
     */
    protected function prefabModule(string $module): void
    {
        if (!in_array($module, $this->getAvailableModules())) {
            $this->error("Module `{$module}` not available.");
            return;
        }

        // Root...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/root", base_path());

        // App...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/App", app_path());

        // Resources...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/resources/views", resource_path('views'));

        // Resources...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/resources/images", resource_path('images'));

        // Resources...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/resources/js", resource_path('js'));

        // Resources...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/resources/css", resource_path('css'));

        // Config...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/config", config_path());

        // Bootstrap...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/bootstrap", base_path('bootstrap'));

        // Tests...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/tests", base_path('tests'));

        // Database...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/database", base_path('database'));

        // Public...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/public", base_path('public'));

        // Merge translations
        $this->mergeModuleTranslations($module);
        $this->mergeModuleValidationTranslations($module);
        $this->mergeModuleEnumTranslations($module);

        // Merge css
        $this->mergeModuleCss($module);

        // Merge config
        $this->mergeModuleConfig($module);

        // Merge routes
        $this->mergeModuleRoutes($module);
        $this->mergeModuleRoutes($module, 'api');
        $this->mergeModuleRoutes($module, 'filament');
        $this->mergeModuleRoutes($module, 'console');

        // Env
        $this->mergeModuleEnvironment($module);

        // Execute shell commands
        $this->executeModuleShellCommands($module);

        // Add the seeders to the database seeder
        $this->mergeSeedersInDatabaseSeeders($module);

        // execute custom commands of the module
        $this->executeModuleCustomCommands($module);

        $this->updateComposer();

        if (isset($this->moduleSettings[$module])) {
            $this->processModuleSettings($this->moduleSettings[$module], $module);
        }

        // Feedback
        $this->info("Prefab of module `{$module}` was successful.");
    }

    /**
     * Copies files without overwriting existing files.
     *
     * @param string $path
     * @param string $targetPath
     * @return void
     */
    protected function copyDirectory(string $path, string $targetPath): void
    {
        if (! $this->filesystem->exists($path)) {
            return;
        }

        /** @var \SplFileInfo $file */
        foreach ($this->filesystem->allFiles($path) as $file) {
            $newPath = $targetPath . '/' . $file->getRelativePath();
            $newPathname = $targetPath . '/' . $file->getRelativePathname();

            $this->filesystem->ensureDirectoryExists($newPath);

            if ($this->option('force') || ! $this->filesystem->exists($newPathname)) {
                $this->filesystem->copy($file->getRealPath(), $newPathname);
            }
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     * @param mixed $packages
     * @return void
     */
    protected function requireComposerPackages($packages): void
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Update the "package.json" file.
     * @param callable $callback
     * @param bool $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     * @return void
     */
    protected static function flushNodeModules(): void
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Move the contents of a resource folder to the project
     */
    protected function copyResourceFolder(string $folder, string $destination): void
    {
        $this->filesystem->ensureDirectoryExists(resource_path($destination));
        $this->filesystem->copyDirectory(__DIR__ . "/../../stubs/" . $folder, resource_path($destination));
    }

    protected function setGivenOptions(array $options): void
    {
        $this->module = $options['module'] ?? null;
        $this->bundle = $options['bundle'] ?? null;

        if ($options['all']) {
            $this->all = true;

            return;
        }

        if (empty($options['module']) && empty($options['bundle'])) {
            $type = $this->choice('Do you want to install a bundle or module?', ['bundle', 'module']);

            if ($type === 'module') {
                $this->module = $this->choice(
                    'Which module do you want to install?',
                    $this->getAvailableModules(),
                    count($this->getAvailableModules()) === 1 ? $this->getAvailableModules()[0] : null
                );
            } elseif ($type === 'bundle') {
                $this->bundle = $this->choice('Which bundle do you want to install?', array_keys($this->bundles));
            }
        }
    }

    protected function processModuleSettings(array $moduleSettings, ?string $module = null): void
    {
        if (!empty($moduleSettings['has-sitemap'])) {
            $this->addSitemapRoute();
        }

        if (!empty($moduleSettings['has-template-routes'])) {
            $this->addFilamentTemplateRoute();
        }

        if (!empty($moduleSettings['seed-overview-page'])) {
            $this->seedFilamentPage(Str::plural($module), $module . '-overview', 'overview');
        }

        if (!empty($moduleSettings['pages'])) {
            foreach ($moduleSettings['pages'] ?? [] as $page) {
                $this->seedFilamentPage($page['title'], $page['label'], $page['type'] ?? 'page');
            }
        }

        if (!empty($moduleSettings['enable'])) {
            $this->enableModule($module);
        }

        if (! empty($moduleSettings['searchable'])) {
            $this->addSearchable($moduleSettings['searchable']);
        }

        if (! empty($moduleSettings['blocks'])) {
            $this->registerBlocks($moduleSettings['blocks']);
        }
    }

    protected function addSearchable(array $config): void
    {
        $spacing = '        '; //8 spaces

        $output = '';

        foreach ($config as $model => $modelConfig) {
            $output .= $spacing . $model . '::class => ';

            $text = var_export($modelConfig, true);

            // Replace brackets
            $text = Str::replace('array (', '[', $text);
            $text = Str::replace(')', ']', $text);

            // Add spacing after each newline
            $text = Str::replace(PHP_EOL, PHP_EOL . $spacing, $text);

            // Remove integer keys
            $text = Str::replaceMatches('/[0-9]+ =>/', '', $text);

            $output .= $text . ','. PHP_EOL;
        }

        $this->addToExistingFile(
            config_path('searchable.php'),
            $output,
            "    'models' => ["
        );
    }

    protected function addFilamentTemplateRoute(): void
    {
        $this->addToExistingFile(
            base_path('routes/web.php'),
            'Route::template();'
        );
    }

    /**
     * Enable a Filament module. This displays the module in the roles section where you can enable or disable the module for certain roles.
     */
//    protected function enableModule(string $module): void
//    {
//        $FilamentServiceProviderPath = app_path('Providers/AppServiceProvider.php');
//
//        if (strpos(file_get_contents($filamentServiceProviderPath), 'Liberiser::serving(function (ServingLiberiser $event) {') === false) {
//            $this->addToExistingFile(
//                $liberiserServiceProviderPath,
//                'use Motivo\Liberiser\Events\ServingLiberiser;',
//                'use Illuminate\Support\Facades\Gate;'
//            );
//            $this->addToExistingFile(
//                $liberiserServiceProviderPath,
//                '
//        Liberiser::serving(function (ServingLiberiser $event) {' . PHP_EOL .
//                '        });',
//                "Liberiser::resourcesIn(app_path('Liberiser'));"
//            );
//        }
//
//        $this->addToExistingFile(
//            $liberiserServiceProviderPath,
//            "            Liberiser::enableModule('{$module}', '" . Str::studly($module) . "');",
//            'Liberiser::serving(function (ServingLiberiser $event) {'
//        );
//    }

    protected function mergeModuleCss($module): void
    {
        $moduleCssPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/resources/css/app.css";
        if (!file_exists($moduleCssPath)) {
            return;
        }

        $projectCssPath = base_path('resources/css/app.css');

        if (!file_exists($projectCssPath)) {
            $this->error("Project CSS file not found. CSS for module `{$module}` not added.");

            return;
        }

        $projectCssContents = file_get_contents($projectCssPath);

        $cssLines = explode(PHP_EOL, file_get_contents($moduleCssPath));

        $startLine = 0;
        $addCssLines = [];
        foreach($cssLines as $cssLine) {
            if (! is_null($startLine) && strpos($projectCssContents, $cssLine) === false) {
                $addCssLines[] = $cssLine;
            }
        }

        if (empty($addCssLines)) {
            return;
        }

        $this->addToExistingFile(
            $projectCssPath,
            implode(PHP_EOL, $addCssLines) . PHP_EOL,
            '@tailwind base;',
            'before'
        );
    }

    protected function mergeModuleTranslations($module): void
    {
        // Root...
        $this->copyDirectory(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/lang/nl", base_path('/lang/nl'));


        $moduleTranslationsPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/lang/nl.json";

        if (!file_exists($moduleTranslationsPath)) {
            return;
        }

        $projectTranslationsPath = base_path('lang/nl.json');

        if (!file_exists($projectTranslationsPath)) {
            $dir = base_path('lang');
            if(!File::isDirectory($dir)) {
                File::makeDirectory($dir, 0777, true, true);
            }

            $file = '{' . PHP_EOL . '    "EOF": "End of file"' . PHP_EOL . '}';

            file_put_contents($projectTranslationsPath, $file);
        }

        $projectTranslationContents = file_get_contents($projectTranslationsPath);

        $translationLines = explode(PHP_EOL, file_get_contents($moduleTranslationsPath));

        $startLine = null;
        $addTranslationLines = [];
        foreach($translationLines as $key => $translationLine) {
            if ($translationLine === '{') {
                $startLine = $key;
                continue;
            } elseif ($translationLine === '}') {
                break;
            }

            if (! is_null($startLine) && strpos($projectTranslationContents, $translationLine) === false) {
                $addTranslationLines[] = $translationLine;
            }
        }

        if (empty($addTranslationLines)) {
            return;
        }

        $lastLine = array_pop($addTranslationLines);
        $addTranslationLines[] = $lastLine . (Str::endsWith($lastLine, ',') ? '' : ',');

        $this->addToExistingFile(
            $projectTranslationsPath,
            implode(PHP_EOL, $addTranslationLines) . PHP_EOL,
            '    "EOF"',
            'before',
        );
    }

    protected function mergeModuleValidationTranslations($module): void
    {
        $moduleValidationTranslationsPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/lang/nl/validation.php";

        if (!file_exists($moduleValidationTranslationsPath)) {
            return;
        }

        $projectValidationTranslationsPath = base_path('lang/nl/validation.php');
        if (!file_exists($projectValidationTranslationsPath)) {
            $this->error("Validation translation file not found in project. Validation rules for module `{$module}` not added.");

            return;
        }

        $projectValidationTranslationContents = file_get_contents($projectValidationTranslationsPath);
        $translationLines = require $moduleValidationTranslationsPath;

        foreach ($translationLines as $translationKey => $translationLine) {
            if (is_array($translationLine)) {
                // Heeft een array content
                $addPosition = 'after';
                if (strpos($projectValidationTranslationContents, "'{$translationKey}'") === false) {
                    $this->addToExistingFile(
                        $projectValidationTranslationsPath,
                        "    '{$translationKey}' => [" . PHP_EOL . "    ],",
                        '];',
                        'before',
                    );

                    $attributesReference = "'{$translationKey}' => [";
                } else {
                    foreach (explode(PHP_EOL, $projectValidationTranslationContents) as $projectValidationLine) {
                        if (strpos($projectValidationLine, "'{$translationKey}'") !== false && strpos($projectValidationLine, "=> [") !== false) {
                            $attributesReference = $projectValidationLine;

                            break;
                        }
                    }
                }

                $toBeAddedTranslationLines = "";
                foreach($translationLine as $translationLineKey => $translationLineValue) {
                    if (is_array($translationLineValue)) {
                        $toBeAddedTranslationLines .= (!empty($toBeAddedTranslationLines) ? PHP_EOL : '') . "        '{$translationLineKey}' => [";
                        foreach($translationLineValue as $translationLineSubKey => $translationLineSubValue) {
                            $toBeAddedTranslationLines .= PHP_EOL . "            '{$translationLineSubKey}' => '{$translationLineSubValue}',";
                        }
                        $toBeAddedTranslationLines .= PHP_EOL . "        ],";
                    } else {
                        $toBeAddedTranslationLines .= (! empty($toBeAddedTranslationLines) ? PHP_EOL : '') . "        '{$translationLineKey}' => '{$translationLineValue}',";
                    }
                }
            } else {
                // Single value
                $attributesReference = '];';
                $addPosition = 'before';
                $toBeAddedTranslationLines = "    '{$translationKey}' => '{$translationLine}',";
            }

            $this->addToExistingFile(
                $projectValidationTranslationsPath,
                $toBeAddedTranslationLines,
                $attributesReference,
                $addPosition,
            );
        }
    }

    protected function mergeModuleEnumTranslations($module): void
    {
        $moduleEnumTranslationsPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/lang/nl/enums.php";

        if (!file_exists($moduleEnumTranslationsPath)) {
            return;
        }

        $projectEnumTranslationsPath = base_path('lang/nl/enums.php');
        if (!file_exists($projectEnumTranslationsPath)) {
            file_put_contents($projectEnumTranslationsPath, '<?php' . PHP_EOL . '' . PHP_EOL . 'return [' . PHP_EOL . '];');
        }

        $translationLines = explode(PHP_EOL, file_get_contents($moduleEnumTranslationsPath));

        $startLine = null;
        $addTranslationLines = [];
        $useClasses = [];
        foreach ($translationLines as $key => $translationLine) {
            if (strpos($translationLine, 'use ') === 0) {
                $useClasses[] = $translationLine;
            }

            if ($translationLine === 'return [') {
                $startLine = $key;
                continue;
            } elseif ($translationLine === '];') {
                break;
            }

            if (!is_null($startLine)) {
                $addTranslationLines[] = $translationLine;
            }
        }

        if (empty($addTranslationLines)) {
            return;
        }

        $this->addToExistingFile(
            $projectEnumTranslationsPath,
            implode(PHP_EOL, $addTranslationLines),
            "];",
            'before',
        );

        if (! empty($useClasses)) {
            foreach($useClasses as $useClass){
                $this->addToExistingFile(
                    $projectEnumTranslationsPath,
                    $useClass . PHP_EOL,
                    'return [',
                    'before',
                );
            }

        }
    }

    protected function seedFilamentPage(string $page, ?string $pageLabel = null, ?string $pageType = null): void
    {
        $pageName = __(ucfirst($page));
        $pageSlug = Str::slug($pageName);

        $data = "        [
            'name' => '{$pageName}',
            'slug' => '{$pageSlug}',
            'label' => '{$pageLabel}',
            " . ($pageType ? "'type' => '{$pageType}'," : "") . "
            'content' => '',
            'visible' => 1,
        ],";

        $this->addToExistingFile(
            config_path('filament/seeders.php'),
            $data,
            "'pages' => ["
        );
    }

    public function addToExistingFile(string $filePath, string $data, ?string $reference = null, string $positionToReference = 'after', $newline = true): void
    {
        if (!file_exists($filePath)) {
            return;
        }

        $file = file_get_contents($filePath);

        if (!$reference && !Str::contains($file, $data)) {
            file_put_contents($filePath, $file . ($newline ? PHP_EOL : '') . $data);
        } elseif (!Str::contains($file, $data) && Str::contains($file, $reference)) {
            if ($positionToReference === 'after') {
                file_put_contents($filePath, str_replace(
                    $reference,
                    $reference . ($newline ? PHP_EOL : '') . $data,
                    $file
                ));
            } elseif ($positionToReference === 'before') {
                file_put_contents($filePath, str_replace(
                    $reference,
                    $data . ($newline ? PHP_EOL : '') . $reference,
                    $file
                ));
            } elseif ($positionToReference === 'replace') {
                file_put_contents($filePath, str_replace(
                    $reference,
                    $data,
                    $file
                ));
            }
        }
    }

    protected function mergeModuleConfig(string $module): void
    {
        if ($this->filesystem->missing(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/config.php")) {
            return;
        }

        $configChanges = require __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/config.php";

        if (! is_array($configChanges) || count($configChanges) === 0) {
            return;
        }

        foreach ($configChanges as $configPath => $options) {
            if (! file_exists($configPath ?? '')) {
                $this->error("We can't update the following config file because it doesn't exists: {$configPath}");

                continue;
            }

            $projectConfigContents = require $configPath;

            if (!is_array($projectConfigContents) || count($projectConfigContents) === 0) {
                continue;
            }

            foreach($options as $configKey => $configValue) {
                $data = "'{$configKey}' => " . var_export($configValue, true);
                if (isset($projectConfigContents[$configKey])) {
                    // Replace the exisiting config option
                    $reference = "'{$configKey}' => " . var_export($projectConfigContents[$configKey], true);
                    $positionToReference = 'replace';
                } else {
                    // Option doesnt exist yet, add to bottom config file
                    $data = '    ' . $data . ',';
                    $reference = '];';
                    $positionToReference = 'before';
                }

                $this->addToExistingFile($configPath, $data, $reference, $positionToReference);
            }
        }
    }

    protected function mergeModuleRoutes(string $module, string $routeFile = 'web'): void
    {
        $moduleRoutePath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/routes/{$routeFile}.php";

        if (!file_exists($moduleRoutePath)) {
            return;
        }

        $projectRoutePath = base_path("routes/{$routeFile}.php");

        if (!file_exists($projectRoutePath)) {
            copy(
                $moduleRoutePath,
                $projectRoutePath,
            );

            return;
        }

        $projectRouteContents = file_get_contents($projectRoutePath);

        $routeLines = explode(PHP_EOL, file_get_contents($moduleRoutePath));

        $addRouteLines = [];
        $useClasses = [];

        foreach ($routeLines as $key => $routeLine) {
            if (strpos($routeLine, '<?php') === 0 || strpos($routeLine, 'declare(') === 0 || empty($routeLine)) {
                continue;
            }

            if (strpos($routeLine, 'use ') === 0) {
                if (strpos($projectRouteContents, $routeLine) === false) {
                    $useClasses[] = $routeLine;
                }

                continue;
            }

            $addRouteLines[] = $routeLine;
        }

        if (empty($addRouteLines)) {
            return;
        }

        $addRoutesBlock = implode(PHP_EOL, $addRouteLines);

        $reference = null;
        $positionToReference = 'after';

        if (strpos($projectRouteContents, 'Route::template();') !== false) {
            // Route template exists: add routes before
            $reference = 'Route::template();';
            $positionToReference = 'before';
        }

        if (strpos($projectRouteContents, $addRoutesBlock) === false) {
            $this->addToExistingFile(
                $projectRoutePath,
                implode(PHP_EOL, $addRouteLines) . PHP_EOL,
                $reference,
                $positionToReference,
            );
        }

        if (!empty($useClasses)) {
            $reference = '<?php' . PHP_EOL;

            if (strpos($projectRouteContents, 'declare(strict_types=1);') !== false) {
                $reference = 'declare(strict_types=1);' . PHP_EOL;
            }

            $this->addToExistingFile(
                $projectRoutePath,
                implode(PHP_EOL, $useClasses) . PHP_EOL,
                $reference,
                'after',
            );
        }
    }

    protected function mergeModuleEnvironment(string $module): void
    {
        foreach (['.env', '.env.example'] as $file) {
            $path = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/{$file}";

            if ($this->filesystem->isFile($path) && $this->filesystem->isFile(base_path($file))) {
                foreach (file($path) as $line) {
                    // Remove newline to make sure it has a valid partial exists check
                    $line = preg_replace( '/\r|\n/', '', $line);
                    $this->addToExistingFile(base_path($file), $line);
                }
            }
        }
    }

    protected function executeModuleShellCommands(string $module): void
    {
        if ($this->option('no-shell')) {
            return;
        }

        $bashPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/shell.sh";

        if ($this->filesystem->isFile($bashPath)) {
            $this->info("Executing shell script for {$module} module...");

            $process = new Process(["sh", $bashPath], timeout: 300);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->newLine();

            $this->info($process->getOutput());

            $this->newLine();

            $this->info("Successfully executed shell script for {$module} module");

            $this->newLine();
        }
    }

    protected function executeModuleCustomCommands(string $module): void
    {
        if (!file_exists(__DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/ModuleActions.php")) {
            return;
        }

        app("RemiHin\FilamentPrefabReactStubs\Modules\\" . Str::studly($module) . "\ModuleActions")->execute();
    }

    protected function mergeSeedersInDatabaseSeeders(string $module): void
    {
        $this->mergeSeeders($module);
        $this->mergeSeeders($module, 'Demo');
        $this->mergeSeeders($module, 'Testing');
    }

    protected function mergeSeeders(string $module, ?string $seederType = null): void
    {
        $seederPath = __DIR__ . "/../../stubs/Modules/" . Str::studly($module) . "/database/seeders";

        if ($seederType) {
            $seederType = ucfirst($seederType);
            $seederPath .= '/' . $seederType;
        }

        if (! file_exists($seederPath)) {
            return;
        }

        $seeders = array_diff(scandir($seederPath), ['.', '..']);

        if (empty($seeders)) {
            return;
        }

        foreach ($seeders as $seederFileName) {
            if (strpos($seederFileName, '.php') === false) {
                continue;
            }

            $seederClassName = str_replace('.php', '', $seederFileName);

            $targetFile = database_path('seeders/DatabaseSeeder.php');

            $databaseSeederContent = file_get_contents($targetFile);

            if(!Str::contains($databaseSeederContent, '$this->call([')) {
                $after = <<< 'AFTER'
    public function run(): void
    {
AFTER;

                $add = <<< 'ADD'
        $this->call([
        
        ]);
ADD;
                $this->addToExistingFile(
                    $targetFile,
                    $add,
                    $after,
                );

            }

            if ($seederType) {
                $targetFile = database_path(sprintf('seeders/%sSeeder.php', $seederType));
            }

            if ($seederType) {
                $this->addToExistingFile(
                    $targetFile,
                    sprintf('use Database\Seeders\%s\%s;', $seederType, $seederClassName),
                    'use Illuminate\Database\Seeder;'
                );
            }

            $this->addToExistingFile(
                $targetFile,
                "            {$seederClassName}::class,",
                '$this->call([',
            );
        }
    }

    protected function updateComposer(): void
    {
        $after = <<< 'AFTER'
        "files": [
            "app/Helpers/helpers.php"
        ],
AFTER;

        $this->addToExistingFile(
            base_path('composer.json'),
            $after,
            '"autoload": {'
        );
    }

    protected function registerBlocks(array $blocks): void
    {
        foreach ($blocks as $block) {
            $this->addToExistingFile(
                config_path('blocks.php'),
                '        ' . $block . '::class,',
                "    'active' => ["
            );
        }
    }
}
