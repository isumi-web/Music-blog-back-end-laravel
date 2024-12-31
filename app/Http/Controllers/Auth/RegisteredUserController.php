<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['sometimes', 'string', 'in:admin,artist'],
            'image' => ['sometimes', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'artist',
            'image' => $request->image ?? 'https://thumbs.dreamstime.com/b/cheerful-rock-singer-funny-cute-cartoon-d-illustration-white-background-creative-avatar-305089825.jpg',
        ]);

        return response()->json([
            'user' => $user
        ], 201);
    }

    public function index(): JsonResponse
    {
        // Fetch all users
        $users = User::all();

        // Return as JSON response
        return response()->json([
            'users' => $users
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function getPublicArtists()
    {
        try {
            $artists = User::where('role', 'artist')
                ->select('id', 'name', 'email', 'image')
                ->get();
    
            return response()->json([
                'users' => $artists,
                'message' => 'Artists fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching artists',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
