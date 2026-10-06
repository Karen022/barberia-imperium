<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'duration_min', 'description', 'image'];

    public function appointments(){
        return $this->belongsToMany(Appointment::class, 'appointment_service')->withPivot('price')->withTimestamps();
    }

    public function sales() {
        return $this->belongsToMany(Sale::class, 'sale_service')->withPivot('price')->withTimestamps();
    }
}
