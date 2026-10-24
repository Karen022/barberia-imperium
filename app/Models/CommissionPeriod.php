<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionPeriod extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'closed_at',
        'closed_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'closed_at' => 'datetime',
        ];
    }

    public function barberCommissions()
    {
        return  $this->hasMany(BarberCommission::class);
    }

    public function closedBy() 
    {
        return $this->belongsTo(User::Class,'closed_by');
    }
}
