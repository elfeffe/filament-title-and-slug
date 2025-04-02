<?php

declare(strict_types=1);

namespace Elfeffe\FilamentTitleAndSlug\Forms\Components;

use Closure;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Elfeffe\SlugRewrite\Models\SlugRewrite;

class TitleAndSlugInput
{
    public static function make(

        // Model fields
        string $fieldTitle = 'title',
        string $fieldSlug = 'slug',

        // Url
        string | Closure | null $urlPath = '/',
        string | Closure | null $urlHost = null,
        bool $urlHostVisible = true,
        bool | Closure $urlVisitLinkVisible = false,
        null | Closure | string $urlVisitLinkLabel = null,
        null | Closure $urlVisitLinkRoute = null,

        // Title
        string | Closure | null $titleLabel = 'Title',
        ?string $titlePlaceholder = null,
        array | Closure | null $titleExtraInputAttributes = null,
        array $titleRules = [
            'required',
        ],
        array $titleRuleUniqueParameters = [],
        bool | Closure $titleIsReadonly = false,
        bool | Closure $titleAutofocus = true,
        null | Closure $titleAfterStateUpdated = null,

        // Slug
        array $slugRules = [
            'required',
        ],
        array $slugRuleUniqueParameters = [],
        bool | Closure $slugIsReadonly = false,
        null | Closure $slugAfterStateUpdated = null,
        string | Closure | null $slugRuleRegex = null,
        string | Closure | null $slugLabelPostfix = null

    ): Group {
        $fieldTitle = $fieldTitle ?? config('filament-title-and-slug.field_title', 'title');
        $fieldSlug = $fieldSlug ?? config('filament-title-and-slug.field_slug', 'slug');
        $urlHost = $urlHost ?? config('app.url');

        $defaultController = null;
        $defaultAction = null;

        /** Input: "Title" */
        $textInput = TextInput::make($fieldTitle)
            ->disabled($titleIsReadonly)
            ->autofocus($titleAutofocus)
            ->reactive()
            ->autocomplete(false)
            ->rules($titleRules)
            ->extraInputAttributes($titleExtraInputAttributes ?? ['class' => 'text-xl font-semibold'])
            ->beforeStateDehydrated(fn (TextInput $component, $state) => $component->state(trim((string) $state)))
            ->afterStateUpdated(
                function (
                    $state,
                    Set $set,
                    Get $get,
                    string $context,
                    ?Model $record,
                    TextInput $component
                ) use (
                    $fieldSlug,
                    $titleAfterStateUpdated
                ) {
                    $slugAutoUpdateDisabled = $get('slug_auto_update_disabled');
                    if ($context === 'edit' && filled($record)) {
                        $slugAutoUpdateDisabled = true;
                    }

                    if (! $slugAutoUpdateDisabled && filled($state)) {
                        // Try to get controller/action from model reflection
                        $controller = null;
                        $action = null;
                        $modelClass = $component->getModel();
                        if ($modelClass && class_exists($modelClass)) {
                            try {
                                $reflection = new \ReflectionClass($modelClass);
                                $defaultProps = $reflection->getDefaultProperties();
                                $controller = $defaultProps['controller'] ?? null;
                                $action = $defaultProps['action'] ?? null;
                            } catch (\ReflectionException $e) {
                                // Handle reflection error if needed
                            }
                        }

                        if ($controller && $action) {
                            $slug = SlugRewrite::generateUniqueSlugForFilament(
                                (string) $state,
                                $controller, // Use reflected controller
                                $action,     // Use reflected action
                                $record?->getKey() // Pass ID (null on create)
                            );
                            $set($fieldSlug, $slug);
                        } else {
                            // Fallback if controller/action not found on model
                            $set($fieldSlug, Str::slug((string) $state));
                        }
                    }

                    if ($titleAfterStateUpdated) {
                        $component->evaluate($titleAfterStateUpdated);
                    }
                }
            );

        if (in_array('required', $titleRules, true)) {
            $textInput->required();
        }

        if ($titlePlaceholder !== '') {
            $textInput->placeholder($titlePlaceholder ?: fn () => Str::of($fieldTitle)->headline());
        }

        if ($titleLabel === null) {
            $textInput->hiddenLabel();
        } else {
            $textInput->label($titleLabel);
        }

        if ($titleRuleUniqueParameters) {
            $textInput->unique(...$titleRuleUniqueParameters);
        }

        /** Input: "Slug" (+ view) */
        $slugInput = SlugInput::make($fieldSlug)

            // Custom SlugInput methods
            ->slugInputVisitLinkRoute($urlVisitLinkRoute)
            ->slugInputVisitLinkLabel($urlVisitLinkLabel)
            ->slugInputUrlVisitLinkVisible($urlVisitLinkVisible)
            ->slugInputOperation(fn ($operation) => $operation === 'create' ? 'create' : 'edit')
            ->slugInputRecordSlug(fn (?Model $record) => $record?->getAttributeValue($fieldSlug))
            ->slugInputModelName(
                fn (?Model $record) => $record
                    ? Str::of(class_basename($record))->headline()
                    : ''
            )
            ->slugInputBasePath($urlPath)
            ->slugInputBaseUrl($urlHost)
            ->slugInputShowUrl($urlHostVisible)
            ->slugInputSlugLabelPostfix($slugLabelPostfix)

            // Default TextInput methods
            ->readOnly($slugIsReadonly)
            ->reactive()
            ->hiddenLabel()
            ->rules($slugRules)
            ->afterStateUpdated(
                function (
                    $state,
                    Set $set,
                    Get $get,
                    ?Model $record,
                    TextInput $component
                ) use (
                    $fieldTitle,
                    $fieldSlug,
                    $slugAfterStateUpdated
                ) {
                    $textToSlugify = trim((string) $state);

                    if (filled($textToSlugify)) {
                         // Try to get controller/action from model reflection
                        $controller = null;
                        $action = null;
                        $modelClass = $component->getModel();
                        if ($modelClass && class_exists($modelClass)) {
                            try {
                                $reflection = new \ReflectionClass($modelClass);
                                $defaultProps = $reflection->getDefaultProperties();
                                $controller = $defaultProps['controller'] ?? null;
                                $action = $defaultProps['action'] ?? null;
                            } catch (\ReflectionException $e) {
                                // Handle reflection error if needed
                            }
                        }

                        if ($controller && $action) {
                            // If controller/action found, use ensureUniqueSlug
                            $modelContext = (object) [
                                'scope' => $defaultProps['scope'] ?? 'default', // Also get scope if defined
                                'id' => $record?->getKey()
                            ];
                            $uniqueSlug = SlugRewrite::ensureUniqueSlug($textToSlugify, $modelContext);
                            $set($fieldSlug, $uniqueSlug);
                        } else {
                            // Fallback if controller/action not found
                            $set($fieldSlug, Str::slug($textToSlugify));
                        }
                    } else {
                        $set($fieldSlug, '');
                    }

                    $set('slug_auto_update_disabled', true);

                    if ($slugAfterStateUpdated) {
                        $component->evaluate($slugAfterStateUpdated);
                    }
                }
            );

        // If a regex rule is provided by the user, apply it.
        if ($slugRuleRegex !== null) {
            $slugInput->regex($slugRuleRegex);
        }

        if (in_array('required', $slugRules, true)) {
            $slugInput->required();
        }

        /** Input: "Slug Auto Update Disabled" (Hidden) */
        $hiddenInputSlugAutoUpdateDisabled = Hidden::make('slug_auto_update_disabled')
            ->dehydrated(false);

        /** Group */

        return Group::make()
            ->schema([
                $textInput,
                $slugInput,
                $hiddenInputSlugAutoUpdateDisabled,
            ]);
    }
}
