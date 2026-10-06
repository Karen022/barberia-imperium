<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberUnavailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_id',
        'start_time',
        'end_time',
        'reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function barber(){
        return $this->belongsTo(User::class, 'barber_id');
    }
}
