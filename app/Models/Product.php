<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $touches = ['variations'];

    protected $casts = [
        'images' => 'array',
    ];

    protected $appends = ['main_image'];


    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function productCategories()
    {
        return $this->belongsToMany(ProductCategory::class, 'category_product', 'product_id', 'category_id');
    }


    /**
     * Override parent boot and Call events
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            foreach ($product->variations()->get() as $variation) {
                $variation->delete();
            }
        });

        static::restoring(function ($product) {
            foreach ($product->variations()->withTrashed()->get() as $variation) {
                $variation->restore();
            }
        });
    }

    public function getMainImageAttribute()
    {
        return $this->images[0];
    }

    public function setSkuAttribute($value)
    {
        $this->attributes['sku'] = strtoupper(str_slug($value));
    }
}
