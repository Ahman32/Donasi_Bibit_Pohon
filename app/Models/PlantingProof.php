<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'type', 'image_path', 'location', 'description', 'planted_at'])]
class PlantingProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'image_path',
        'location',
        'description',
        'planted_at',
    ];

    protected $casts = [
        'planted_at' => 'date',
    ];
}
