@extends('layouts.cards')

@section('title', 'Modifica Carta')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-warning">✏️ Modifica: {{ $card->name }}</h2>
            <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Annulla</a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('admin.cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label fw-bold">Nome Pokémon</label>
                        <input type="text" name="name" class="form-control" value="{{ $card->name }}" required>
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">Tipo GCC</label>
                        <input type="text" name="type" list="typeSuggestions" class="form-control"
                            value="{{ $card->type }}" required>
                        <datalist id="typeSuggestions">
                            @foreach ($existingTypes as $type)
                                <option value="{{ $type }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">HP</label>
                        <input type="number" name="hp" class="form-control" value="{{ $card->hp }}">
                    </div>
                </div>

                <div class="row mt-3 text-start">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Espansione / Set</label>
                        <input type="text" name="expansion_name" list="expansionSuggestions" class="form-control"
                            value="{{ $card->expansion->name ?? '' }}" required>
                        <datalist id="expansionSuggestions">
                            @foreach ($expansions as $expansion)
                                <option value="{{ $expansion->name }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Rarità</label>
                        <input type="text" name="rarity" list="raritySuggestions" class="form-control"
                            value="{{ $card->rarity }}">
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
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ $card->price }}">
                    </div>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold d-block mb-3 text-danger">Galleria Attuale (Seleziona per
                        rimuovere)</label>
                    <div class="row g-2">
                        @foreach ($card->images as $image)
                            <div class="col-md-2 text-center border p-2 rounded shadow-sm bg-white mx-1">
                                <img src="{{ asset('storage/' . $image->path) }}" class="img-fluid rounded mb-2"
                                    style="height: 80px; object-fit: cover;">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Aggiungi altre foto</label>
                    <input type="file" name="images[]" class="form-control shadow-sm" multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="mt-2">
                        <small class="text-muted d-block">I formati delle foto devono assolutamente essere solo:
                            <strong>jpeg, png, jpg, webp</strong>.</small>
                        <small class="text-primary italic">Stiamo lavorando per implementare altri formati
                            consentiti.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow">AGGIORNA CARTA</button>
            </form>
        </div>
    </div>
@endsection
