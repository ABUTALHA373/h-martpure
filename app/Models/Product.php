<?php

namespace App\Models;

use App\LogAdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use LogAdminActivity;

    protected $table = 'products';
    protected $guarded = [];
    protected $appends = ['first_image_url', 'images_url'];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function getFirstImageUrlAttribute()
    {
        $images = json_decode($this->images, true);
        if (!$images || empty($images)) {
             return asset('storage/default-product-image.svg');
        }
        $firstImage = $images[0];
        
        if (str_starts_with($firstImage, 'http')) {
            return $firstImage;
        }
        return asset('storage/' . $firstImage);
    }

    public function getImagesUrlAttribute()
    {
        $images = json_decode($this->images, true);
        if (!$images) return [];

        return array_map(function($img) {
            if (str_starts_with($img, 'http')) {
                return $img;
            }
            return asset('storage/' . $img);
        }, $images);
    }
}
