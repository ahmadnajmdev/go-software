<?php

namespace App\Http\Requests;

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public const SERVICES = ['website', 'mobile', 'system', 'pos', 'ecommerce', 'other'];

    public const BUDGETS = ['under-3k', '3k-8k', '8k-20k', '20k-plus', 'unsure'];

    public const TIMELINES = ['asap', '1-3-months', '3-6-months', 'exploring'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * POST /contact carries no locale prefix, so SetLocale leaves the app on
     * English however the visitor got here. Resolve the real locale before
     * validation runs, so the error messages come back in their language and
     * the stored row records the language they actually browsed in.
     */
    protected function prepareForValidation(): void
    {
        $locale = $this->input('locale');

        if (! in_array($locale, SetLocale::LOCALES, true)) {
            $locale = $this->localeFromReferer() ?? config('app.locale');
        }

        app()->setLocale($locale);

        $this->merge(['locale' => $locale]);
    }

    /** /ckb/projects → ckb. Backs up the hidden field for cached pages. */
    private function localeFromReferer(): ?string
    {
        $path = (string) parse_url((string) $this->headers->get('referer'), PHP_URL_PATH);
        $first = explode('/', trim($path, '/'))[0];

        return in_array($first, SetLocale::LOCALES, true) ? $first : null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            // The WhatsApp number is how most replies actually happen, so it
            // is required now rather than optional.
            'phone' => ['required', 'string', 'max:40', 'regex:/[0-9]{6,}/'],
            'company' => ['nullable', 'string', 'max:160'],
            'service' => ['required', Rule::in(self::SERVICES)],

            // Optional by design. "Not sure yet" and "Just exploring" exist so
            // the question produces answers instead of abandonment.
            'budget' => ['nullable', Rule::in(self::BUDGETS)],
            'timeline' => ['nullable', Rule::in(self::TIMELINES)],
            'message' => ['required', 'string', 'max:5000'],
            'locale' => ['required', Rule::in(SetLocale::LOCALES)],

            // Which page/form the submission came from. Reporting only, so a
            // spoofed value costs nothing beyond a wrong row in a report.
            'source' => ['nullable', 'string', 'max:190'],

            'website' => ['prohibited'], // honeypot
        ];
    }

    /**
     * Messages come from ui_strings like the rest of the site's copy, so they
     * are translated in all three languages and editable in the admin panel.
     */
    public function messages(): array
    {
        return [
            'name.required' => t('errName'),
            'email.required' => t('errEmail'),
            'email.email' => t('errEmailValid'),
            'message.required' => t('errMessage'),
            'phone.required' => t('errPhone'),
            'phone.regex' => t('errPhone'),
            'service.required' => t('errService'),
            'service.in' => t('errService'),
            'name.max' => t('errTooLong'),
            'email.max' => t('errTooLong'),
            'phone.max' => t('errTooLong'),
            'service.max' => t('errTooLong'),
            'message.max' => t('errTooLong'),
        ];
    }

    /** Errors send the visitor back to the form, not the top of the page. */
    protected function getRedirectUrl(): string
    {
        return $this->redirector->getUrlGenerator()->previous().'#contact';
    }
}
