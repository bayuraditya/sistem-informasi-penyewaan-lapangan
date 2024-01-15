<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    Protected $fillable = ['user_id', 'payment_method', 'total_amount', 'payment_date', 'total_session', 'payment_status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'orders');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
