<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
    ];

    # Descomentar esta linha quando criar o módulo de produtos.
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}
