<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];
    
    // Using enum casting if Laravel 9+ and PHP 8.1+
    // protected $casts = [
    //     'status' => InventoryStatus::class, 
    // ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSoldOut($query)
    {
        return $query->where('status', 'sold_out');
    }
}
