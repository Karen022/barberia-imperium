<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'document_type',
        'document_number',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function appointmentsAsCliente(){
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function appointmentsAsBarber(){
        return $this->hasMany(Appointment::class, 'barber_id');
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function cashRegister(){
        return $this->hasMany(CashRegister::class);
    }

     public function formattedDocument() : ?string
    {
        if (!$this->document_type || !$this->document_number) {
            return null;
        }

        $number = $this->document_number;

        if ($this->document_type === 'CI') {
            return number_format((int) $number, 0, '', '.');
        }

        if ($this->document_type === 'RUC'){
            if (strlen($number) < 2) {
                return $number;
            }

            return substr($number, 0, -1) . '-' . substr($number, -1);
        }

    }
}
