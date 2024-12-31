<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Album;

class Song extends Model
{
    use HasFactory;

    protected $table = 'songs';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'url',
        'album_id',
        'image',
        'user_id' 
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
