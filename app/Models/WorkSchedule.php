<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'check_in_start',
        'check_in_end',
        'check_out_start', 
        'check_out_end'
    ];

    protected $casts = [
        'check_in_start' => 'datetime',
        'check_in_end' => 'datetime',
        'check_out_start' => 'datetime',
        'check_out_end' => 'datetime'
    ];
}
