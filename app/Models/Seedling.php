<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'description', 'price', 'quantity_available', 'image_path'])]
class Seedling extends Model
{
    use HasFactory;

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
