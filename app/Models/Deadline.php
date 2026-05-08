<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'deadline_at',
        'is_active',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'is_active' => 'boolean',
    ];
}
