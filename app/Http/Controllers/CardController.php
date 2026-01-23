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
        $existingTypes = Card::distinct()->whereNotNull('type')->orderBy('type')->pluck('type');
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
            'expansion_name' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        // Trova o crea l'espansione basandoti sul nome
        $expansion = Expansion::firstOrCreate(['name' => $request->expansion_name]);

        // Prepara i dati per il database
        $data = $request->except(['expansion_name', 'images']);
        $data['expansion_id'] = $expansion->id;
        // Crea la carta
        $card = Card::create($data);

        // Salva le immagini
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('cards/gallery', 'public');
                $card->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.cards.index')->with('success', 'Carta creata con successo!');
    }

    public function edit(Card $card)
    {
        $expansions = Expansion::all();
        $card->load('images');
        $existingTypes = Card::distinct()->whereNotNull('type')->orderBy('type')->pluck('type');
        return view('admin.cards.edit', compact('card', 'expansions', 'existingTypes'));
    }

    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'hp' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'expansion_name' => 'required|string',
            'rarity' => 'nullable|string',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        // Gestione Espansione
        $expansion = Expansion::firstOrCreate(['name' => $request->expansion_name]);

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

        // Aggiornamento dati della carta
        $data = $request->except(['expansion_name', 'images', 'remove_images']);
        $data['expansion_id'] = $expansion->id;  // Convertiamo il nome in ID

        $card->update($data);

        return redirect()->route('admin.cards.index')->with('success', 'Carta aggiornata correttamente!');
    }

    public function destroy(Card $card)
    {
        foreach ($card->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $card->delete();
        return redirect()->route('admin.cards.index')->with('success', 'Carta eliminata!');
    }
}
