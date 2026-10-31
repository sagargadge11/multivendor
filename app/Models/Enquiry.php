<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Enquiry extends Model {
    protected $fillable = ['enquiry_number','product_id','vendor_id','customer_id','customer_name','customer_email','customer_phone','message','status'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function customer(): BelongsTo { return $this->belongsTo(User::class, 'customer_id'); }
    public function history(): HasMany { return $this->hasMany(EnquiryHistory::class); }
}
