<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'seedling_id', 'quantity', 'total_amount', 'payment_method', 'payment_status', 'payment_proof', 'snap_token'])]
class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seedling_id',
        'quantity',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'snap_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seedling()
    {
        return $this->belongsTo(Seedling::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
