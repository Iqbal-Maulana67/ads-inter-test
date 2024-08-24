<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'slug',
        'price',
        'category_id',
    ];

    public $timestamps = false;

    public function images()
    {
        return $this->hasMany(ProductAssets::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
