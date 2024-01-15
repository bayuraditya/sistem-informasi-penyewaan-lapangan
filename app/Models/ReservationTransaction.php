<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['reservation_id', 'transaction_id'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
