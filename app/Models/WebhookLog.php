<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WebhookLog extends Model {
    protected $fillable = ['vendor_integration_id','event_id','event_type','payload','processing_status','error_message','received_at','processed_at'];
    protected function casts(): array { return ['payload' => 'array','received_at' => 'datetime','processed_at' => 'datetime']; }
    public function integration(): BelongsTo { return $this->belongsTo(VendorIntegration::class, 'vendor_integration_id'); }
}
