<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
    $request->validate([
    'name' => 'required',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6',
    'selectedItems' => 'required|array',
    'adresse' => 'required',
    'telephone' => 'required',
    ]);

    $selectedItems = json_encode($request->input('selectedItems', [])); // Convert array to JSON string

    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'selected_items' => $selectedItems,
    'adresse' => $request->adresse,
    'telephone' => $request->telephone,
    ]);
    $token = $user->createToken('token-name')->plainTextToken;
return response()->json([
'status' => true,
'message' => 'User Created Successfully',
'token' => $token
], 200);
}
}