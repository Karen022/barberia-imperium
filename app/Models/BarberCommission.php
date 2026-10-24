<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarberCommission extends Model
{
    protected $fillable = [
        'commission_period_id',
        'barber_id',
        'services_total',
        'barber_percentage',
        'shop_percentage',
        'barber_amount',
        'shop_amount',
    ];

    protected function casts(): array
    {
        return [
            'services_total' => 'decimal:2',
            'barber_percentage' => 'decimal:2',
            'shop_percentage' => 'decimal:2',
            'barber_amount' => 'decimal:2',
            'shop_amount' => 'decimal:2',
        ];
    }

    public function period() 
    {
        return $this->belongsTo(CommissionPeriod::class, 'commission_period_id');
    }

    public function barber() 
    {   
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function items()
    {
        return $this->hasMany(CommissionItem::class);
    }

    public function paymentAllocations()
    {
        return $this->hasMany(CommissionPaymentAllocation::class);
    }
}

