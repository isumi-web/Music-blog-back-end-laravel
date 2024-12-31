<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Resources\SongResource;
use App\Http\Resources\SongCollection;
use App\Models\Song;
use App\Models\Album;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{
    public function index()
    {
        return new SongCollection(
            Song::with(['album'])->paginate(12)
        );
    }

    public function store(StoreSongRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Find the album by name
        $album = Album::where('name', $validated['albumName'])->firstOrFail();

        // Create the song with the album ID
        $song = Song::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'url' => $validated['url'],
            'album_id' => $album->id,
            'image' => $validated['image'],
            'user_id' => Auth::id(), 
        ]);

        return response()->json([
            'message' => 'Song created successfully',
            'data' => $song->load(['album'])
        ], 201);
    }

    public function show(Song $song)
    {
        return new SongResource(
            $song->load(['album'])
        );
    }

    public function update(UpdateSongRequest $request, Song $song)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validated();

        // Find the album by name if albumName is provided
        if (isset($validated['albumName'])) {
            $album = Album::where('name', $validated['albumName'])->firstOrFail();
            $validated['album_id'] = $album->id;
        }

        $song->fill([
            'name' => $validated['name'] ?? $song->name,
            'description' => $validated['description'] ?? $song->description,
            'url' => $validated['url'] ?? $song->url,
            'album_id' => $validated['album_id'] ?? $song->album_id,
            'image' => $validated['image'] ?? $song->image,
        ])->save();

        return response()->json([
            'message' => 'Song updated successfully',
            'data' =>  new SongResource($song->load(['album']))
        ]);
    }

    public function destroy(Song $song)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $song->delete();

        return response()->json([
            'message' => 'Song deleted successfully'
        ]);
    }

    public function byAlbum($albumId)
    {
        return new SongCollection(
            Song::with(['album'])
                ->where('album_id', $albumId)
                ->get()
        );
    }
}
