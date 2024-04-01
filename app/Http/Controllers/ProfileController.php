<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!$user = auth()->user()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Valider les données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
        ]);

        // Mettre à jour le profil de l'utilisateur
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
        ]);

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }
}
