<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'slug',
        'business_email',
        'phone',
        'website_url',
        'listing_type',
        'gst_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'description',
        'logo',
        'banner',
        'verification_status',
        'rejection_reason',
        'status',
        'suspension_reason',
        'suspended_at',
        'verified_at',
        'profile_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'suspended_at' => 'datetime',
            'profile_completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(VendorIntegration::class);
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
}
