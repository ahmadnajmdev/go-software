<?php

namespace App\Models\Concerns;

trait HasTranslations
{
    /**
     * Localized value of a JSON-translated field, falling back to English.
     */
    public function tr(string $field, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $value = $this->{$field} ?? [];

        if (! is_array($value)) {
            return (string) $value;
        }

        return $value[$locale] ?? $value['en'] ?? '';
    }

    public function translatable(): array
    {
        return $this->translatable ?? [];
    }
}
