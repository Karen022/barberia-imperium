<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'expense_date',
        'concept',
        'category',
        'amount',
        'payment_method',
        'description',
        'status',
        'commission_payment_id'
    ];

    public function casts() : array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function commissionPayment()
    {
        return $this->belongsTo(CommissionPayment::class);
    }
}
