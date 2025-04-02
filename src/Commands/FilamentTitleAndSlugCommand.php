<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug\Commands;

use Illuminate\Console\Command;

class FilamentTitleAndSlugCommand extends Command
{
    public $signature = 'filament-title-and-slug:install'; // Example signature, adjust if needed

    public $description = 'Installation command for Filament Title and Slug package.';

    public function handle(): int
    {
        $this->comment('Filament Title and Slug package setup...');
        // Add any necessary installation logic here, like publishing config files.
        // Example: $this->call('vendor:publish', ['--tag' => 'filament-title-and-slug-config']);
        $this->info('Filament Title and Slug setup complete.');

        return self::SUCCESS;
    }
}
