<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug;

//use Filament\Support\Assets\AlpineComponent;
use Elfeffe\FilamentTitleAndSlug\Commands\FilamentTitleAndSlugCommand;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

//use Filament\Support\Facades\FilamentIcon;
//use Illuminate\Filesystem\Filesystem;
//use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

//use Altwaireb\Filament\Testing\TestsFilament;

class FilamentTitleAndSlugServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-title-and-slug';

    public static string $viewNamespace = 'filament-title-and-slug';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile('title-and-slug')
            ->hasCommands($this->getCommands())
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

//        FilamentAsset::registerScriptData(
//            $this->getScriptData(),
//            $this->getAssetPackageName()
//        );

        // Icon Registration
//        FilamentIcon::register($this->getIcons());

        // Handle Stubs
//        if (app()->runningInConsole()) {
//            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
//                $this->publishes([
//                    $file->getRealPath() => base_path("stubs/filament-title-and-slug/{$file->getFilename()}"),
//                ], 'filament-title-and-slug-stubs');
//            }
//        }

        // Testing
//        Testable::mixin(new TestsFilament());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'elfeffe/filament-title-and-slug';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-title-and-slug', __DIR__ . '/../resources/dist/components/filament-title-and-slug.js'),
            Css::make('filament-title-and-slug-styles', __DIR__ . '/../resources/dist/filament-title-and-slug.css'),
//            Js::make('filament-title-and-slug-scripts', __DIR__ . '/../resources/dist/filament-title-and-slug.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentTitleAndSlugCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
//    protected function getIcons(): array
//    {
//        return [];
//    }

    /**
     * @return array<string>
     */
//    protected function getRoutes(): array
//    {
//        return [];
//    }

    /**
     * @return array<string, mixed>
     */
//    protected function getScriptData(): array
//    {
//        return [];
//    }

    /**
     * @return array<string>
     */
//    protected function getMigrations(): array
//    {
//        return [
//            'create_filament-title-and-slug_table',
//        ];
//    }
}
