<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AlbumCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($album) {
                return [
                    'id' => $album->id,
                    'name' => $album->name,
                    'artist' => $album->artist,
                    'image' => $album->image,
                    'userId' => $album->user_id,
                ];
            }),
        ];
    }
}
