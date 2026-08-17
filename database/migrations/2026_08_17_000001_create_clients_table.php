<?php

use App\Models\Client;
use App\Support\Settings;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The client marquee used to be a plain list of names in the "clients"
     * setting. Promote it to a table so each client can carry a logo image.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });

        $names = Settings::get('clients');

        foreach (is_array($names) ? $names : [] as $i => $name) {
            Client::create(['position' => $i + 1, 'name' => $name]);
        }
    }

    public function down(): void
    {
        Settings::set('clients', Client::orderBy('position')->pluck('name')->all());

        Schema::dropIfExists('clients');
    }
};
