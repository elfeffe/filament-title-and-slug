<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elfeffe\FilamentTitleAndSlug\FilamentTitleAndSlug
 */
class FilamentTitleAndSlug extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'FilamentTitleAndSlug';
    }
}
