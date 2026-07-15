<?php

namespace App\Support;

/**
 * PHP port of the design's applyTheme(): emits the CSS custom properties
 * driven by the theme settings (accent / mood / shape). Base defaults for
 * the "soft" shape and "midnight" mood live in app.css; this only emits
 * overrides, exactly like the original runtime did.
 */
class Theme
{
    public const ACCENTS = ['#2ca69c', '#2A6FDB', '#D97757', '#7C5CFF'];

    public const MOODS = ['midnight', 'bright'];

    public const SHAPES = ['soft', 'sharp', 'round'];

    public static function cssVars(): string
    {
        $accent = Settings::get('theme.accent', '#2ca69c');
        $mood = Settings::get('theme.mood', 'midnight');
        $shape = Settings::get('theme.shape', 'soft');

        $vars = [
            '--gs-accent' => $accent,
            '--gs-accent-lite' => $mood === 'bright'
                ? static::mixHex($accent, '#0d1826', 0.28)
                : static::mixHex($accent, '#ffffff', 0.42),
        ];

        if ($mood === 'bright') {
            $vars += [
                '--gs-deep-bg' => '#f2f7f7',
                '--gs-deep-fg' => '#0d1826',
                '--gs-deep-muted' => '#55636f',
                '--gs-deep-line' => 'rgba(13,24,38,.2)',
                '--gs-deep-grid' => 'rgba(13,24,38,.05)',
            ];
        }

        if ($shape === 'sharp') {
            $vars += ['--gs-r-card' => '4px', '--gs-r-tile' => '3px', '--gs-r-btn' => '4px', '--gs-r-sm' => '2px'];
        } elseif ($shape === 'round') {
            $vars += ['--gs-r-card' => '28px', '--gs-r-tile' => '16px', '--gs-r-btn' => '999px', '--gs-r-sm' => '12px'];
        }

        return collect($vars)->map(fn ($v, $k) => "{$k}: {$v};")->implode(' ');
    }

    public static function mixHex(string $a, string $b, float $w): string
    {
        $parse = fn (string $h) => array_map(fn ($i) => hexdec(substr($h, $i, 2)), [1, 3, 5]);
        [$ar, $ag, $ab] = $parse($a);
        [$br, $bg, $bb] = $parse($b);

        $mix = fn (int $x, int $y) => str_pad(dechex((int) round($x * (1 - $w) + $y * $w)), 2, '0', STR_PAD_LEFT);

        return '#'.$mix($ar, $br).$mix($ag, $bg).$mix($ab, $bb);
    }
}
