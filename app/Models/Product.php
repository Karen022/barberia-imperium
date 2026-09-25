<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'stock', 'description', 'image', 'is_featured'];

    public function saleDetails(){
        return $this->hasMany(saleDetail::class);
    }
}
