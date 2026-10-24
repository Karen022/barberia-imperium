<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionItem extends Model
{
    protected $fillable = [
        'barber_commission_id',
        'source_type',
        'source_id',
        'sales_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected function casts(): array 
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function barberCommission()
    {
        return $this->belongsTo(BarberCommission::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::Class, 'sales_id');
    }
}
