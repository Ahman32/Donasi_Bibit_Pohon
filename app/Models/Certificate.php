<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'certificate_number',
        'recipient_name',
        'seedling_name',
        'tree_count',
        'issued_date',
        'signer_name',
        'signer_title',
        'appreciation_message',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
