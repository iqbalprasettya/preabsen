<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address', 
        'latitude',
        'longitude',
        'radius'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'radius' => 'integer'
    ];

    // Tambahkan relasi ke users
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
