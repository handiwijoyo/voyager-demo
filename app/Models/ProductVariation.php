<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariation extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at', 'sale_start', 'sale_end'];

    protected $fillable = [
        'sku',
        'size',
        'quantity',
        'reserved',
        'available',
        'price',
        'cost_of_good',
        'sale_price',
        'sale_start',
        'sale_end'
    ];

    protected $appends = ['current_price'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getCurrentPriceAttribute()
    {
        if(Carbon::now()->between(Carbon::parse($this->sale_start), Carbon::parse($this->sale_end)))
        {
            return ($this->sale_price) ?: $this->price;
        }

        return $this->price;
    }


    public function getSaleStartAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d M Y');
        }
    }


    public function getSaleEndAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->format('d M Y');
        }
    }


    public function setSaleStartAttribute($value)
    {
        if ($value) {
            $this->attributes['sale_start'] = Carbon::createFromFormat('d M Y', $value);
        }

    }


    public function setSaleEndAttribute($value)
    {
        if ($value) {
            $this->attributes['sale_end'] = Carbon::createFromFormat('d M Y', $value);
        }
    }


    public function setSalePriceAttribute($value)
    {
        $this->attributes['sale_price'] = $value ?: 0;
    }


    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value ?: 0;
    }


    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = $value ?: 0;
    }


    public function setCostOfGoodAttribute($value)
    {
        $this->attributes['cost_of_good'] = $value ?: 0;
    }
}
