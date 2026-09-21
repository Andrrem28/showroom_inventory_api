<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'client_id',
        'user_id',
        'payment_method',
        'installments',
        'installment_value',
        'total_amount',
        'sold_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'total_amount'      => 'decimal:2',
            'installment_value' => 'decimal:2',
            'installments'      => 'integer',
            'sold_at'           => 'datetime',
        ];
    }

    // Verifica se a venda é parcelada
    public function isInstallment(): bool
    {
        return $this->installments > 1;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
