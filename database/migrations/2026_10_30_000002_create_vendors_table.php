<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->string('business_email');
            $table->string('phone', 30)->nullable();
            $table->string('website_url')->nullable();
            $table->string('listing_type', 30)->default('manual');
            $table->string('gst_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country', 100)->default('India');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('verification_status', 30)->default('pending')->index();
            $table->string('status', 30)->default('active')->index();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
