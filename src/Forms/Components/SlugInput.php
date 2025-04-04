<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class SlugInput extends TextInput
{
    protected string $view = 'filament-title-and-slug::forms.components.slug-input';

    protected string | Closure | null $operation = null;

    protected string | Closure $basePath = '/';

    protected string | Closure | null $baseUrl = null;

    protected bool $showUrl = true;

    protected bool $cancelled = false;

    protected Closure $recordSlug;

    protected bool | Closure $isReadOnly = false;

    protected string $labelPrefix;

    protected ?Closure $visitLinkRoute = null;

    protected string | Closure | null $visitLinkLabel = null;

    protected bool | Closure $slugInputUrlVisitLinkVisible = true;

    protected ?Closure $slugInputModelName = null;

    protected string | Closure | null $slugLabelPostfix = null;

    public function slugInputUrlVisitLinkVisible(bool | Closure $slugInputUrlVisitLinkVisible): static
    {
        $this->slugInputUrlVisitLinkVisible = $slugInputUrlVisitLinkVisible;

        return $this;
    }

    public function getSlugInputUrlVisitLinkVisible(): bool
    {
        return (bool) $this->evaluate($this->slugInputUrlVisitLinkVisible);
    }

    public function slugInputModelName(?Closure $slugInputModelName): static
    {
        $this->slugInputModelName = $slugInputModelName;

        return $this;
    }

    public function getSlugInputModelName(): ?string
    {
        $value = $this->evaluate($this->slugInputModelName);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function slugInputVisitLinkRoute(?Closure $visitLinkRoute): static
    {
        $this->visitLinkRoute = $visitLinkRoute;

        return $this;
    }

    public function getVisitLinkRoute(): ?string
    {
        $value = $this->evaluate($this->visitLinkRoute);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function slugInputVisitLinkLabel(string | Closure | null $visitLinkLabel): static
    {
        $this->visitLinkLabel = $visitLinkLabel;

        return $this;
    }

    public function getVisitLinkLabel(): string
    {
        $label = $this->evaluate($this->visitLinkLabel);
        
        if ($label instanceof \Illuminate\Support\Stringable) {
            $label = $label->toString();
        }

        if ($label === '') {
            return '';
        }

        return $label ?: trans('filament-title-and-slug::title-and-slug.permalink_label_link_visit') . ' ' . $this->getSlugInputModelName();
    }

    public function slugInputLabelPrefix(?string $labelPrefix): static
    {
        $this->labelPrefix = $labelPrefix ?? trans('filament-title-and-slug::title-and-slug.permalink_label');

        return $this;
    }

    public function getLabelPrefix(): string
    {
        $value = $this->evaluate($this->labelPrefix);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function readOnly(bool | Closure $condition = true): static
    {
        $this->isReadOnly = $condition;

        return $this;
    }

    public function getReadonly(): bool
    {
        return (bool) $this->evaluate($this->isReadOnly);
    }

    public function slugInputOperation(string | Closure | null $operation): static
    {
        $this->operation = $operation;

        return $this;
    }

    public function getOperation(): ?string
    {
        $value = $this->evaluate($this->operation);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function slugInputSlugLabelPostfix(string | Closure | null $slugLabelPostfix): static
    {
        $this->slugLabelPostfix = $slugLabelPostfix;

        return $this;
    }

    public function getSlugLabelPostfix(): ?string
    {
        $value = $this->evaluate($this->slugLabelPostfix);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function slugInputRecordSlug(Closure $recordSlug): static
    {
        $this->recordSlug = $recordSlug;

        return $this;
    }

    public function getRecordSlug(): ?string
    {
        $value = $this->evaluate($this->recordSlug);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }

    public function getRecordUrl(): ?string
    {
        if (! $this->getRecordSlug()) {
            return null;
        }

        $visitLinkRoute = $this->getVisitLinkRoute();
        
        $result = $visitLinkRoute
            ? $this->getVisitLinkRoute()
            : $this->getBaseUrl() . $this->getBasePath() . $this->getRecordSlug();
            
        if ($result instanceof \Illuminate\Support\Stringable) {
            return $result->toString();
        }
        
        return $result;
    }

    public function slugInputBasePath(string | Closure | null $path): static
    {
        $this->basePath = ! is_null($path) ? $path : $this->basePath;

        return $this;
    }

    public function slugInputBaseUrl(string | Closure | null $url): static
    {
        $this->baseUrl = $url ?: config('app.url');

        return $this;
    }

    public function getBaseUrl(): string
    {
        return Str::of($this->evaluate($this->baseUrl))->rtrim('/')->toString();
    }

    public function slugInputShowUrl(bool $showUrl): static
    {
        $this->showUrl = $showUrl;

        return $this;
    }

    public function getShowUrl(): bool
    {
        return $this->showUrl;
    }

    public function getFullBaseUrl(): ?string
    {
        $result = $this->showUrl
            ? $this->getBaseUrl() . $this->getBasePath()
            : $this->getBasePath();
            
        if ($result instanceof \Illuminate\Support\Stringable) {
            return $result->toString();
        }
        
        return $result;
    }

    public function getBasePath(): string
    {
        $value = $this->evaluate($this->basePath);
        
        if ($value instanceof \Illuminate\Support\Stringable) {
            return $value->toString();
        }
        
        return $value;
    }
}
