<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function medias()
    {
        return $this->hasMany(ProductMedia::class, 'product_id', 'id');
    }
}
