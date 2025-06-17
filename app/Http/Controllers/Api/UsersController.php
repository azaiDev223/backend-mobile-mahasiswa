<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function index(){
        return UsersResource::collection(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:100',
            'password' => 'required|string',
            'role' => 'required|string',
        ]);

        $user = user::create($validated);

        return new UsersResource($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|integer|max:10',
            'email' => 'sometimes|required|string|max:100',
            'password' => 'sometimes|required|string',
            'role' => 'sometimes|required|string',
        ]);

        $user->update($validated);

        return new UsersResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Pengumuman berhasil dihapus']);
    }
}


