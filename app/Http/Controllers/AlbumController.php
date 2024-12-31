<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class AlbumController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'artist' => 'required|string',
            'image' => 'required|string',
        ]);

        $album = Album::create(array_merge($data, ['user_id' => Auth::id()])); 

        return response()->json([
            'album' => $album
        ], 201);
    }

    public function index()
    {
        return AlbumResource::collection(
            Album::with(['songs'])->paginate(12)
        );
    }


    // Fetch album by id
    public function show(Album $album)
    {
        return new AlbumResource(
            $album->load(['songs']) 
        );
    }

    public function destroy(Album $album)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $album->delete();
        return response()->json([
            'message' => 'Album deleted successfully'
        ]);
    }

    public function update(Album $album)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = request()->validate([
            'name' => 'required|string|max:255',
            'artist' => 'required|string',
            'image' => 'required|string',   
        ]);

        $album->update($data);

        return response()->json([
            'album' => $album
        ]);
    }
}
