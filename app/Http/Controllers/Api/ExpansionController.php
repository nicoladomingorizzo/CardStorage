<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expansion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpansionController extends Controller
{
    public function store(Request $request)
    {
        // Validazione con risposta JSON in caso di errore
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expansions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "L'espansione esiste già o il nome non è valido."
            ], 422);
        }

        // Creazione con valori di default
        $expansion = Expansion::create([
            'name' => $request->name,
            'serie' => 'Serie Base',
            'release_date' => now(),
        ]);

        // Restituiamo i dati in un formato leggibile dallo script JS delle tue viste
        return response()->json([
            'success' => true,
            'id' => $expansion->id,
            'name' => $expansion->name
        ]);
    }
}
