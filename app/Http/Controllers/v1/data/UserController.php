<?php

namespace App\Http\Controllers\v1\data;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return $users;
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'role' => $data['role']
        ]);

        if(!$user){
            return response()->json([
                'message' => 'Failed to Register'
            ], 400);
        }

        return response()->json([
            'message' => 'Registration Successful'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if(empty($user) || !$user){
            return response()->json([
                'message' => 'Failed to fetch user'
            ], 200);
        }

        $update = $user->update($request->all());
        if(!$update){
            return response()->json([
                'message' => 'Failed to Update User'
            ], 400);
        }

        return response()->json([
            'message' => 'Update User Successfully',
            'users' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if(empty($user) || !$user){
            return response()->json([
                'message' => 'Failed to fetch user'
            ], 200);
        }

        $delete = $user->delete();
        if(!$delete){
            return response()->json([
                'message' => 'Failed to Delete User'
            ], 400);
        }

        return response()->json([
            'message' => 'Delete User Successfully',
        ], 200);
    }
}
