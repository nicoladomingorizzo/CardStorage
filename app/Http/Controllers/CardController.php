<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardImage;
use App\Models\Expansion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carica le carte con l'espansione e la prima immagine per l'anteprima
        $cards = Card::with(['expansion', 'images'])->get();

        return view('cards.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $expansions = Expansion::all();

        return view('cards.create', compact('expansions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validazione completa inclusi i nuovi campi e la galleria
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hp' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'rarity' => 'nullable|string',
            'description' => 'nullable|string',
            'expansion_id' => 'required|exists:expansions,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Creazione della carta (escludendo le immagini grezze dal comando create)
        $card = Card::create($request->except('images'));

        // Gestione Upload Immagini Multiple
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('cards.index')->with('success', 'Carta Pokémon aggiunta con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        // Carica la carta con la galleria completa e il set di appartenenza
        $card->load(['images', 'expansion']);

        return view('cards.show', compact('card'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        $expansions = Expansion::all();
        $card->load('images');

        return view('cards.edit', compact('card', 'expansions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        // Validazione aggiornata
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hp' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'expansion_id' => 'required|exists:expansions,id',
            'rarity' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Eliminazione delle immagini selezionate dalla galleria
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = CardImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        // Caricamento di nuove immagini nella galleria
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        // Aggiornamento dei dati della carta (escluse le immagini caricate)
        $card->update($request->except('images'));

        return redirect()->route('cards.index')->with('success', 'Carta aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        // Elimina fisicamente tutti i file della galleria collegati alla carta
        foreach ($card->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        // Elimina la carta (i record CardImage verranno eliminati dal database grazie al cascadeOnDelete)
        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Carta e immagini eliminate correttamente.');
    }
}
