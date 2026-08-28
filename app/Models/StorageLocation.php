<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageLocation extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    # Descomentar esta linha quando fizer a parte de produtos
    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'location_id');
    // }
}
