<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FavoriteMeal;
class MealController extends Controller
{
    
    public function getUserRole(Request $request)
    {
        $user = Auth::user();
        return response()->json(['role' => $user->role]);
    }

    public function index()
    {
        $meals = Meal::all();

        return response()->json($meals);
    }

  
    public function store(Request $request)
    {
        
        $user = Auth::user();
    
        $meal = new Meal([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $request->input('image'),
            'user_id' => $user->id,
            'SousCategorieID' => $request->input('SousCategorieID') //add this ligne
        ]);
    
       
        $meal->save();
    
        return response()->json($meal, 200);
    }
    
    
    public function showMeal($id)
    {
       
        $meal = Meal::find($id);
    
        if (!$meal) {
            return response()->json(['error' => 'Meal not found.'], 404);
        }
    
        return response()->json($meal);
   
    }
    

    public function usersMeal()
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
    
            $meals = Meal::where('user_id', $userId)->get();
    
            return response()->json($meals, 200);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('Error fetching user meals: ' . $e->getMessage());
    
            return response()->json(['error' => 'Error fetching user meals.'], 500);
        }
    }
    
   
    public function updateMeal($id, Request $request)
    {
        $meal= Meal::find($id);
        $meal->update($request->all());
        return response()->json('Meal updated successfully');
    }
    


    public function deleteMeal($id)
{
    $user = Auth::user();
    $meal = $user->meal()->find($id);
    

    if (!$meal) {
        return response()->json(['error' => 'Meal not found.'], 404);
    }

    $meal->delete();

    return response()->json('Meal deleted successfully.');
}


    public function deletefavoritemeal($mealId)
{
    $user = Auth::user();

    // Find the favorite meal based on user and meal_id
    $favoriteMeal = FavoriteMeal::where('user_id', $user->id)
        ->where('meal_id', $mealId)
        ->first();

    if (!$favoriteMeal) {
        return response()->json(['error' => 'Favorite meal not found.'], 404);
    }

    $favoriteMeal->delete();

    return response()->json('Favorite meal removed!');
}

    public function addToFavorite(Request $request)
    {
        $user = Auth::user();
        $mealId = $request->input('meal_id');

        // Check if the meal is already a favorite
        $existingFavoriteMeal = FavoriteMeal::where('user_id', $user->id)
            ->where('meal_id', $mealId)
            ->first();

        if (!$existingFavoriteMeal) {
            // If not, create a new favorite meal record
            $favoriteMeal = new FavoriteMeal([
                'user_id' => $user->id,
                'meal_id' => $mealId,
            ]);

            $favoriteMeal->save();

            return response()->json(['message' => 'Meal added to favorites'], 200);
        }

        return response()->json(['message' => 'Meal is already in favorites'], 200);
    }

    public function userFavoriteMeal()
    {
        try {
            $user = Auth::user();
            $userId = $user->id;

            $favoriteMeals = FavoriteMeal::where('user_id', $userId)->with('meal')->get();
            
            return response()->json($favoriteMeals, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching user meals: ' . $e->getMessage());

            return response()->json(['error' => 'Error fetching user meals.'], 500);
        }
    }
}
