<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Localized UI string span: <x-t k="aboutTitle"/>.
 * For logged-in admins it carries the data attribute the inline editor
 * (admin-edit.js) uses to persist per-locale edits.
 */
class T extends Component
{
    public function __construct(public string $k)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <span class="gs-edit" @auth data-edit-string="{{ $k }}" @endauth {{ $attributes }}>{{ t($k) }}</span>
        blade;
    }
}
