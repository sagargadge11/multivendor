<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductApproval extends Model {
    protected $fillable = ['product_id','reviewed_by','status','remarks','reviewed_at'];
    protected function casts(): array { return ['reviewed_at' => 'datetime']; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
