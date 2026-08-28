<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'contact_person',
    ];

    # Descomentar esta linha quando fizer a parte de produtos
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}
