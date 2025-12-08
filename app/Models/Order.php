<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'customer_id',
        'category_id',
        'order_number',
        'tracking_code',
        'qr_code',
        'weight',
        'origin_country',
        'status',
        'description',
        'loaded_at',
        'arrived_at',
        'delivered_at',
    ];

    protected $casts = [
        'loaded_at' => 'datetime',
        'arrived_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'loaded' => 'blue',
            'in_transit' => 'orange',
            'arrived' => 'yellow',
            'ready' => 'green',
            'delivered' => 'gray',
            default => 'gray',
        };
    }

    public function getQrCodeUrlAttribute(): ?string
    {
        if ($this->qr_code) {
            return asset('storage/' . $this->qr_code);
        }
        return null;
    }
}
