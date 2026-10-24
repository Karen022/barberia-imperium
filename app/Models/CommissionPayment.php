<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionPayment extends Model
{
    protected $fillable = [
        'barber_id',
        'amount',
        'payment_date',
        'payment_method',
        'registered_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function barber() 
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function allocations()
    {
        return $this->hasMany(CommissionPaymentAllocation::class);
    }

    public function expenses()
    {
        return $this->hasOne(Expense::class, 'commission_payment_id');
    }
    
}
