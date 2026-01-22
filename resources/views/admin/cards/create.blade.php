@extends('layouts.cards')

@section('title', 'Aggiungi Nuova Carta')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">🆕 Nuova Carta Pokémon</h2>
            <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Torna alla lista</a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('admin.cards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label fw-bold">Nome Pokémon</label>
                        <input type="text" name="name" class="form-control" placeholder="es. Charizard" required>
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">Tipo GCC</label>
                        <input type="text" name="type" list="typeSuggestions" class="form-control"
                            placeholder="Scegli o scrivi..." required>
                        <datalist id="typeSuggestions">
                            @foreach ($existingTypes as $type)
                                <option value="{{ $type }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">HP</label>
                        <input type="number" name="hp" class="form-control" placeholder="es. 120">
                    </div>
                </div>

                <div class="row mt-3 text-start">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Espansione / Set</label>
                        <input type="text" name="expansion_name" list="expansionSuggestions" class="form-control"
                            placeholder="Scegli o scrivi set..." required>
                        <datalist id="expansionSuggestions">
                            @foreach ($expansions as $expansion)
                                <option value="{{ $expansion->name }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Rarità</label>
                        <input type="text" name="rarity" list="raritySuggestions" class="form-control"
                            placeholder="Scegli o scrivi rarità...">
                        <datalist id="raritySuggestions">
                            <option value="Comune">
                            <option value="Non Comune">
                            <option value="Rara">
                            <option value="Rara Holo">
                            <option value="Ultra Rara">
                        </datalist>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Prezzo (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="mb-4 mt-3 text-start">
                    <label class="form-label fw-bold">Descrizione</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Es. Condizioni carta..."></textarea>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Carica Immagini</label>
                    <input type="file" name="images[]" class="form-control shadow-sm" multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="mt-2">
                        <small class="text-muted d-block">I formati delle foto devono assolutamente essere solo:
                            <strong>jpeg, png, jpg, webp</strong>.</small>
                        <small class="text-primary italic">Stiamo lavorando per implementare altri formati
                            consentiti.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 shadow fw-bold">SALVA CARTA</button>
            </form>
        </div>
    </div>
@endsection
