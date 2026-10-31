<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('sku')->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 3)->default('INR');
            $table->string('product_url')->nullable();
            $table->string('source_type', 30)->default('manual');
            $table->string('stock_status', 30)->default('unknown');
            $table->string('approval_status', 30)->default('pending')->index();
            $table->string('publication_status', 30)->default('draft')->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['vendor_id', 'external_id']);
            $table->index('category_id');
            $table->index('vendor_id');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
