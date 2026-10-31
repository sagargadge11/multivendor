<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EnquiryHistory extends Model {
    protected $fillable = ['enquiry_id','updated_by','old_status','new_status','notes'];
    public function enquiry(): BelongsTo { return $this->belongsTo(Enquiry::class); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
