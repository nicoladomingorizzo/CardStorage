@extends('layouts.cards')

@section('title', 'Aggiungi Nuova Carta')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">🆕 Aggiungi Nuova Carta</h2>
            <a href="{{ route('admin.cards.index') }}" class="btn btn-outline-secondary">Torna alla lista</a>
        </div>

        <div class="card shadow-sm border-0 p-4">
            <form action="{{ route('admin.cards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nome Pokémon</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                    </div>

                    {{-- CAMPO IBRIDO: Scelta rapida + Scrittura libera --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Tipo GCC</label>
                        <input type="text" name="type" list="typeSuggestions"
                            class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}"
                            placeholder="Scegli o scrivi nuovo..." required>
                        <datalist id="typeSuggestions">
                            @php $defaults = ['Fuoco', 'Acqua', 'Erba', 'Elettro', 'Psico', 'Lotta', 'Oscurità', 'Metallo', 'Drago', 'Incolore', 'Folletto', 'Allenatore']; @endphp
                            @foreach (collect($defaults)->merge($existingTypes)->unique()->sort() as $type)
                                <option value="{{ $type }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Punti Salute (HP)</label>
                        <input type="number" name="hp" class="form-control" value="{{ old('hp') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Espansione</label>
                        <select name="expansion_id" class="form-select" required>
                            <option value="">Seleziona Set...</option>
                            @foreach ($expansions as $expansion)
                                <option value="{{ $expansion->id }}"
                                    {{ old('expansion_id') == $expansion->id ? 'selected' : '' }}>
                                    {{ $expansion->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Rarità</label>
                        <input type="text" name="rarity" class="form-control" value="{{ old('rarity') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Valore (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control"
                            value="{{ old('price') }}">
                    </div>
                </div>

                <div class="mb-4 mt-3">
                    <label class="form-label fw-bold">Descrizione Carta</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Carica Immagini</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 shadow">💾 Salva Nuova Carta</button>
            </form>
        </div>
    </div>
@endsection
