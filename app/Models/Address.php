<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'addresses';

    protected $guarded = [];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
