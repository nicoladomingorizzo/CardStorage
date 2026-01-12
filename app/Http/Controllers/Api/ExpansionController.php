<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expansion;
use Illuminate\Http\Request;

class ExpansionController extends Controller
{
    public function store(Request $request)
    {
        // Validazione minima
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        //  Valori di default
        $expansion = Expansion::create([
            'name' => $request->name,
            'serie' => 'Serie Base',
            'release_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'expansion' => $expansion
        ]);
    }
}
