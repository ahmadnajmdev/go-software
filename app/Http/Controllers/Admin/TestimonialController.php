<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('admin.testimonials.index', ['items' => Testimonial::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.testimonials.form', ['item' => new Testimonial()]);
    }

    public function store(Request $request)
    {
        Testimonial::create($this->data($request) + ['position' => Testimonial::max('position') + 1]);

        return redirect()->route('admin.testimonials.index')->with('ok', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', ['item' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($this->data($request));

        return redirect()->route('admin.testimonials.index')->with('ok', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('ok', 'Testimonial deleted.');
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'avatar' => ['nullable', 'string', 'max:500'],
            'author' => ['required', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'role.en' => ['required', 'string', 'max:190'],
            'role.ar' => ['nullable', 'string', 'max:190'],
            'role.ckb' => ['nullable', 'string', 'max:190'],
            'quote.en' => ['required', 'string', 'max:1000'],
            'quote.ar' => ['nullable', 'string', 'max:1000'],
            'quote.ckb' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
