<?php

namespace App\Support;

/**
 * WhatsApp deep links.
 *
 * WhatsApp is the default business channel in Iraq, so every one of these is a
 * conversion path rather than a convenience. The number defaults to the same
 * one shown on the site (contact.phone) so there is a single source of truth,
 * with a `whatsapp.number` setting to override it if the two ever differ.
 */
class WhatsApp
{
    /**
     * Which pre-written message belongs to which placement. Keys are the
     * `source` a component is given; values are ui_string keys.
     */
    public const MESSAGES = [
        'hero' => 'waMsgHero',
        'contact' => 'waMsgContact',
        'sticky_bar' => 'waMsgHero',
        'form_success' => 'waMsgContact',
        'service_website' => 'waMsgWebsite',
        'service_web_app' => 'waMsgWebApp',
        'service_mobile' => 'waMsgMobile',
        'service_system' => 'waMsgSystem',
        'service_pos' => 'waMsgPos',
        'service_ecommerce' => 'waMsgEcommerce',
        'service_support' => 'waMsgSupport',
    ];

    /** Digits only, as wa.me requires: "+964 751 711 0459" → "9647517110459". */
    public static function number(): ?string
    {
        $raw = Settings::get('whatsapp.number') ?: Settings::get('contact.phone');
        $digits = preg_replace('/\D+/', '', (string) $raw);

        return strlen((string) $digits) >= 8 ? $digits : null;
    }

    public static function isConfigured(): bool
    {
        return self::number() !== null;
    }

    /** Full wa.me link with the message pre-filled, or null if unconfigured. */
    public static function url(string $message = ''): ?string
    {
        if (! $number = self::number()) {
            return null;
        }

        $url = 'https://wa.me/'.$number;

        return $message === '' ? $url : $url.'?text='.rawurlencode($message);
    }

    /** The localized message for a placement, falling back to the generic one. */
    public static function messageFor(string $source): string
    {
        return t(self::MESSAGES[$source] ?? 'waMsgHero');
    }

    /**
     * Map a service card to its WhatsApp source. Services are database rows,
     * so this reads their tag rather than assuming a fixed set of IDs.
     */
    public static function sourceForService(?string $tag): string
    {
        return match (strtoupper(trim((string) $tag))) {
            'WEB', 'WEBSITE' => 'service_website',
            'WEB APPS', 'WEB APP' => 'service_web_app',
            'MOBILE' => 'service_mobile',
            'SYSTEMS', 'SYSTEM' => 'service_system',
            'POS' => 'service_pos',
            'ECOMMERCE', 'E-COMMERCE' => 'service_ecommerce',
            'SUPPORT' => 'service_support',
            default => 'hero',
        };
    }
}
