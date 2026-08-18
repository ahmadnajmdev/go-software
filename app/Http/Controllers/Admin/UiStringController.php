<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UiString;
use App\Support\UiStringDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UiStringController extends Controller
{
    public function index()
    {
        return view('admin.strings.index', [
            'groups' => UiString::orderBy('key')->get()->groupBy('group')->sortKeys(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'strings' => ['required', 'array'],
            'strings.*.en' => ['nullable', 'string', 'max:5000'],
            'strings.*.ar' => ['nullable', 'string', 'max:5000'],
            'strings.*.ckb' => ['nullable', 'string', 'max:5000'],
        ]);

        $existing = UiString::whereIn('key', array_keys($data['strings']))->get()->keyBy('key');

        foreach ($data['strings'] as $key => $value) {
            if ($string = $existing->get($key)) {
                $clean = array_filter([
                    'en' => trim($value['en'] ?? ''),
                    'ar' => trim($value['ar'] ?? ''),
                    'ckb' => trim($value['ckb'] ?? ''),
                ], fn ($v) => $v !== '');

                if ($clean && $clean !== $string->value) {
                    $string->update(['value' => $clean]);
                }
            }
        }

        Cache::forget('gs.strings');

        return back()->with('ok', 'Strings saved.');
    }

    public function reset()
    {
        // Re-create anything missing first, then reset the rest: a key absent
        // from this install would otherwise stay absent and keep rendering as
        // its own name on the page.
        UiStringDefaults::syncMissing();

        foreach (UiStringDefaults::all() as $key => $value) {
            UiString::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('gs.strings');

        return back()->with('ok', 'All strings reset to design defaults.');
    }
}
