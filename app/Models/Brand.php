<?php

namespace App\Models;

use App\LogAdminActivity;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use LogAdminActivity;

    protected $table = 'brands';
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('storage/default-product-image.svg'); // Or null
        }
        if (str_starts_with($this->logo, 'http')) {
            return $this->logo;
        }
        return asset('storage/' . $this->logo);
    }
}
