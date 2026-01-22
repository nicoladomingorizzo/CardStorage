@extends('layouts.cards')

@section('title', 'Modifica Carta')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">✏️ Modifica: {{ $card->name }}</h2>
            <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Annulla</a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('admin.cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nome Pokémon</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $card->name) }}"
                            required>
                    </div>

                    {{-- QUI PUOI SCEGLIERE O SCRIVERE UN TIPO NUOVO --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tipo GCC</label>
                        <input type="text" name="type" list="typeSuggestions" class="form-control"
                            value="{{ old('type', $card->type) }}" placeholder="Scegli o scrivi nuovo..." required>
                        <datalist id="typeSuggestions">
                            @php $defaults = ['Fuoco', 'Acqua', 'Erba', 'Elettro', 'Psico', 'Lotta', 'Oscurità', 'Metallo', 'Drago', 'Incolore', 'Folletto', 'Allenatore']; @endphp
                            @foreach (collect($defaults)->merge($existingTypes)->unique()->sort() as $type)
                                <option value="{{ $type }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">HP</label>
                        <input type="number" name="hp" class="form-control" value="{{ old('hp', $card->hp) }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Espansione</label>
                        <select name="expansion_id" class="form-select" required>
                            @foreach ($expansions as $expansion)
                                <option value="{{ $expansion->id }}"
                                    {{ $card->expansion_id == $expansion->id ? 'selected' : '' }}>
                                    {{ $expansion->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Rarità</label>
                        <input type="text" name="rarity" class="form-control"
                            value="{{ old('rarity', $card->rarity) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Valore (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ old('price', $card->price) }}">
                    </div>
                </div>

                <div class="mb-4 mt-3">
                    <label class="form-label fw-bold">Descrizione Pokédex</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $card->description) }}</textarea>
                </div>

                {{-- Immagini Esistenti --}}
                <div class="mb-4">
                    <label class="form-label fw-bold d-block mb-3">Immagini Caricate (Seleziona per rimuovere)</label>
                    <div class="row g-3">
                        @foreach ($card->images as $image)
                            <div class="col-6 col-md-2 text-center">
                                <div class="card h-100 border p-2 shadow-sm">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        class="card-img-top rounded shadow-sm" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-1 mt-2">
                                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}"
                                            class="form-check-input border-danger">
                                        <label class="small text-danger fw-bold">Elimina</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Aggiungi Nuove Immagini</label>
                    <input type="file" name="images[]" class="form-control shadow-sm" multiple accept="image/*">
                </div>

                <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow">Aggiorna Carta</button>
            </form>
        </div>
    </div>
@endsection
