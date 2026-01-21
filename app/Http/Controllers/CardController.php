<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardImage;
use App\Models\Expansion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::with(['expansion', 'images'])->get();
        return view('admin.cards.index', compact('cards'));
    }

    public function create()
    {
        $expansions = Expansion::all();
        $existingTypes = Card::select('type')->distinct()->get();
        return view('admin.cards.create', compact('expansions', 'existingTypes'));
    }

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
            // Alzato a 10MB (10240 KB)
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,heic|max:10240',
        ]);

        // Creazione carta con i dati validati
        $card = Card::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'hp' => $validated['hp'],
            'price' => $validated['price'],
            'rarity' => $validated['rarity'],
            'description' => $validated['description'],
            'expansion_id' => $validated['expansion_id'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('cards.index')->with('success', 'Carta creata con successo!');
    }

    public function show(Card $card)
    {
        $card->load(['images', 'expansion']);
        return view('admin.cards.show', compact('card'));
    }

    public function edit(Card $card)
    {
        $expansions = Expansion::all();
        $card->load('images');
        return view('admin.cards.edit', compact('card', 'expansions'));
    }

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
            'images.*' => 'nullable|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        // Rimozione immagini selezionate
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = CardImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
        }

        // Caricamento nuove immagini
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        // Aggiornamento dati testuali usando solo i campi validati
        $card->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'hp' => $validated['hp'],
            'price' => $validated['price'],
            'rarity' => $validated['rarity'],
            'description' => $validated['description'],
            'expansion_id' => $validated['expansion_id'],
        ]);

        return redirect()->route('admin.cards.index')->with('success', 'Carta aggiornata con successo!');
    }

    public function destroy(Card $card)
    {
        foreach ($card->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $card->delete();
        return redirect()->route('cards.index')->with('success', 'Carta eliminata correttamente.');
    }
}
