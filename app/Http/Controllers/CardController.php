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
     * Visualizza la lista delle carte.
     */
    public function index()
    {
        $cards = Card::with(['expansion', 'images'])->get();
        return view('admin.cards.index', compact('cards'));
    }

    /**
     * Mostra il form di creazione.
     */
    public function create()
    {
        $expansions = Expansion::all();
        // Recupera i tipi unici per i suggerimenti (datalist)
        $existingTypes = Card::distinct()->whereNotNull('type')->orderBy('type')->pluck('type');

        return view('admin.cards.create', compact('expansions', 'existingTypes'));
    }

    /**
     * Salva una nuova carta nel database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hp' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'rarity' => 'nullable|string',
            'description' => 'nullable|string',
            'expansion_id' => 'required|exists:expansions,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,heic|max:10240',
        ]);

        // Creazione carta
        $card = Card::create($validated);

        // Gestione caricamento immagini multiple
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        // Corretto redirect alla rotta admin
        return redirect()->route('admin.cards.index')->with('success', 'Carta creata con successo!');
    }

    /**
     * Mostra il dettaglio di una singola carta.
     */
    public function show(Card $card)
    {
        $card->load(['images', 'expansion']);
        return view('admin.cards.show', compact('card'));
    }

    /**
     * Mostra il form di modifica.
     */
    public function edit(Card $card)
    {
        $expansions = Expansion::all();
        $card->load('images');
        // Recupera i tipi unici per i suggerimenti
        $existingTypes = Card::distinct()->whereNotNull('type')->orderBy('type')->pluck('type');

        return view('admin.cards.edit', compact('card', 'expansions', 'existingTypes'));
    }

    /**
     * Aggiorna la carta esistente.
     */
    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hp' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'expansion_id' => 'required|exists:expansions,id',
            'rarity' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,heic|max:10240',
        ]);

        // Rimozione immagini selezionate (checkbox nell'edit)
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = CardImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        // Caricamento nuove immagini aggiuntive
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        // Aggiornamento dati testuali
        $card->update($validated);

        return redirect()->route('admin.cards.index')->with('success', 'Carta aggiornata con successo!');
    }
}
