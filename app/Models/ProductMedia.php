<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    protected $table = "product_medias";

    const IMAGE = 'image';
    const VIDEO = 'video';
    protected $guarded = [];
}
