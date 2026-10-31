<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class VendorIntegration extends Model {
    protected $fillable = ['vendor_id','platform','website_url','webhook_token','webhook_secret','api_credentials','connection_status','last_sync_at'];
    protected function casts(): array {
        return ['webhook_secret' => 'encrypted', 'api_credentials' => 'encrypted', 'last_sync_at' => 'datetime'];
    }
    public function vendor(): BelongsTo { return $this->belongsTo(Vendor::class); }
    public function webhookLogs(): HasMany { return $this->hasMany(WebhookLog::class); }
}
