<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'purchase_price',
        'sale_price',
        'stock',
        'category_id',
    ];

    protected $appends = ['image_path'];

    public function getImagePathAttribute()
    {
        return asset('uploads/product_images/' . $this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getProfitPercentAttribute(){
        $profit=$this->sale_price-$this->purchase_price;
        $profit_percent=$profit*100/$this->purchase_price;
        return number_format($profit_percent,2);
    }
}
