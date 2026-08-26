<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];
    # Descomentar esta linha quando fizer a parte de produtos
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}
