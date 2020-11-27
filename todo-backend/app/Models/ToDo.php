<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;

    public const PRIORITIES = [
        1 => 'LOW',
        2 => 'MEDIUM',
        2 => 'HIGH'
     ];

    protected $fillable = [
        'title',
        'description',
        'priority',
        'completed'
    ];
}
