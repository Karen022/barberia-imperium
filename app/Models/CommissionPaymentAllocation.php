<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionPaymentAllocation extends Model
{
    protected $fillable = [
        'commission_payment_id',
        'barber_commission_id',
        'amount',
    ];

    protected function casts(): array 
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(CommissionPayment::class, 'commission_payment_id');
    }

    public function barberCommission()
    {
        return $this->belongsTo(BarberCommission::class);
    }
}
