
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('business_type')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('banner')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('profile_completed_at')->nullable();

            $table->index('listing_type');
            $table->index('status');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropIndex(['listing_type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['verification_status']);

            $table->dropColumn([
                'business_type',
                'postal_code',
                'banner',
                'rejection_reason',
                'suspension_reason',
                'suspended_at',
                'profile_completed_at',
            ]);
        });
    }
};
