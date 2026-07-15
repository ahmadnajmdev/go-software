<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return view('admin.services.index', ['items' => Service::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.services.form', ['item' => new Service()]);
    }

    public function store(Request $request)
    {
        Service::create($this->data($request) + ['position' => Service::max('position') + 1]);

        return redirect()->route('admin.services.index')->with('ok', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.form', ['item' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $service->update($this->data($request));

        return redirect()->route('admin.services.index')->with('ok', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('ok', 'Service deleted.');
    }

    private function data(Request $request): array
    {
        return $request->validate([
            'image' => ['nullable', 'string', 'max:500'],
            'tag' => ['nullable', 'string', 'max:40'],
            'title.en' => ['required', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],
            'title.ckb' => ['nullable', 'string', 'max:255'],
            'description.en' => ['required', 'string', 'max:1000'],
            'description.ar' => ['nullable', 'string', 'max:1000'],
            'description.ckb' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
