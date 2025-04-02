<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentTitleAndSlugPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-title-and-slug';
    }

    public function register(Panel $panel): void
    {
        // No registration needed for this simple component logic
    }

    public function boot(Panel $panel): void
    {
        // No boot logic needed for this simple component logic
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
