<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id', 
        'total',
        'payment_method',
        'status',
        ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client() {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function saleDetails(){
        return $this->hasMany(SaleDetail::class);
    }
    public function services() {
        return $this->belongsToMany(Service::class, 'sale_service')->withPivot('price', 'performed_by')->withTimestamps();
    }
}
