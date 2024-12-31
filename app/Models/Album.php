<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Song;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albums';

    protected $fillable = [
        'name',
        'artist',
        'image',
        'user_id' 
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
