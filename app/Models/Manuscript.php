<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manuscript extends Model
{
    use HasFactory;

    protected $fillable = ['dna_hash', 'content', 'has_clue'];

    protected $casts = [
        'content' => 'array',
        'has_clue' => 'boolean',
    ];
}