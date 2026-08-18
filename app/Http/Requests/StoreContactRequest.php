<?php

namespace App\Http\Requests;

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
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
            'phone' => ['nullable', 'string', 'max:40'],
            'service' => ['nullable', 'string', 'max:60'],
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
