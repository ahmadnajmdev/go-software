<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Media;
use App\Support\ImageUpload;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients.index', ['items' => Client::ordered()->get()]);
    }

    public function create()
    {
        return view('admin.clients.form', ['item' => new Client(), 'media' => $this->library()]);
    }

    public function store(Request $request)
    {
        Client::create($this->data($request) + ['position' => Client::max('position') + 1]);

        return redirect()->route('admin.clients.index')->with('ok', 'Client created.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.form', ['item' => $client, 'media' => $this->library()]);
    }

    public function update(Request $request, Client $client)
    {
        $client->update($this->data($request));

        return redirect()->route('admin.clients.index')->with('ok', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')->with('ok', 'Client deleted.');
    }

    private function data(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'logo' => ['nullable', 'string', 'max:500'],
            'logo_file' => ImageUpload::RULES,
            'url' => ['nullable', 'url', 'max:300'],
        ]);

        // An uploaded file wins over whatever the path field holds.
        if ($request->hasFile('logo_file')) {
            $data['logo'] = ImageUpload::store($request->file('logo_file'))->path;
        }

        unset($data['logo_file']);

        return $data;
    }

    private function library()
    {
        return Media::latest()->take(24)->get();
    }
}
