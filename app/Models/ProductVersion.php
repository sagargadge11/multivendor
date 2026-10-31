<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductVersion extends Model {
    protected $fillable = ['product_id','version_data','version_type','source'];
    protected function casts(): array { return ['version_data' => 'array']; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
