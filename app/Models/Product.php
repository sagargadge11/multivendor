<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'vendor_id','category_id','brand_id','external_id','sku','slug','name',
        'description','price','currency','product_url','source_type','stock_status',
        'approval_status','publication_status','approved_at','last_synced_at',
    ];

    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'approved_at' => 'datetime', 'last_synced_at' => 'datetime'];
    }

    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class); }
    public function versions(): HasMany { return $this->hasMany(ProductVersion::class); }
    public function approvals(): HasMany { return $this->hasMany(ProductApproval::class); }
    public function enquiries(): HasMany { return $this->hasMany(Enquiry::class); }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('approval_status', 'approved')
            ->where('publication_status', 'published')
            ->whereHas('vendor', fn ($vendor) => $vendor
                ->where('verification_status', 'verified')
                ->where('status', 'active'));
    }
}
