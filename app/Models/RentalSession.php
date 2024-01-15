<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalSession extends Model
{
    use HasFactory;
    protected $fillable = ['session_time'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
