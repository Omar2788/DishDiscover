<?php

namespace App\Http\Controllers;

use App\Models\SousCategorie;
use Illuminate\Http\Request;

class SousCategorieController extends Controller
{

    public function index()
    {
        $scategories = SousCategory::with('categorie')->get();
        return $scategories;
    }

    public function store(Request $request)
    {
        $scategorie = new SousCategorie([
            'nomscategorie' => $request->input('nomscategorie'),
            'categorieID' => $request->input('categorieID'),
            ]);
            $scategory->save();
            return response()->json($scategorie,201);
    }

    public function show($id)
    {
        $scategorie = SousCategorie::find($id);
        return response()->json($scategorie);
    }

    public function update(Request $request, $id)
    {
        $scategorie = SousCategorie::find($id);
        $scategorie->update($request->all());
        return response()->json($scategorie,200);
    }

    public function destroy($id)
    {
        $scategorie = SousCategorie::find($id);
        $scategorie->delete();
        return response()->json('Scategorie supprimée !');
    }

    public function showSCategorieByCAT($idcat)
    {
        $scategorie= SousCategorie::where('categorieID', $idcat)->with('categorie')->get();
        return response()->json($scategorie);
    }
}
