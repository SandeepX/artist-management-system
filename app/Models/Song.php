<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    const GENRE = [
        'rnb',
        'classic',
        'country',
        'rock',
        'jazz'
    ];

    protected $table = 'songs';

    protected $fillable = [
        'artist_id',
        'title',
        'album_name',
        'genre',
    ];
}
