<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'court_id', 'session_id', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function sessions()
    {
        return $this->belongsTo(RentalSession::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'reservation_transaction', 'reservation_id','transaction_id');
    }
    

}
