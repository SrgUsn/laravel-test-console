<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'age',
        'location',
        'country_code',
    ];


    protected $casts = [
        'age' => 'date',
    ];

    
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->age,
            set: fn ($value) => Carbon::now()->subYears($value),
        );
    }
}
