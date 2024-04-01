<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = SousCategory::with('SousCategorie')->get();
        return $posts;
    }

    public function store(Request $request)
    {
        $post = new Post([
            'nom' => $request->input('contenu'),
            'description' => $request->input('dateCreation'),
            'SousCategorieID' => $request->input('SousCategorieID'),
            ]);
            $post->save();
            return response()->json($post,201);
    }

    public function show($id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->all());
        return response()->json($post,200);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json('Post supprimée !');
    }
}
