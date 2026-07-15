<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('ui_strings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->unsignedInteger('position');
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('image')->nullable();
            $table->string('tag')->nullable();
            $table->json('title');
            $table->json('description');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('image')->nullable();
            $table->json('category');
            $table->json('title');
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('image')->nullable();
            $table->string('name');
            $table->json('role');
            $table->json('links')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('position');
            $table->string('author');
            $table->json('role');
            $table->json('quote');
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('excerpt');
            $table->json('body');
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('service')->nullable();
            $table->text('message');
            $table->string('locale', 3)->default('en');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('original_name');
            $table->string('mime');
            $table->unsignedInteger('size');
            $table->string('alt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['media', 'contact_submissions', 'posts', 'testimonials',
                  'team_members', 'projects', 'services', 'sections', 'ui_strings', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
