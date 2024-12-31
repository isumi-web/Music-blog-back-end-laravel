<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SongCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($song) {
                return [
                    'id' => $song->id,
                    'name' => $song->name,
                    'description' => $song->description,
                    'url' => $song->url,
                    'albumId' => $song->album->id,
                    'albumName' => $song->album->name,
                    'image' => $song->image,
                ];
            }),
        ];
    }
}
