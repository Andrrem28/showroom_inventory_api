<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
    ];

    # Descomentar esta linha, quando fizer o módulo sales
    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }
}
