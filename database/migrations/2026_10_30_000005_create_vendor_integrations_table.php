<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('platform', 50);
            $table->string('website_url')->nullable();
            $table->string('webhook_token')->unique();
            $table->longText('webhook_secret')->nullable();
            $table->longText('api_credentials')->nullable();
            $table->string('connection_status', 30)->default('pending');
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_integrations');
    }
};
