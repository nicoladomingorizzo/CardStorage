@extends('layouts.cards')

@section('title', 'Modifica: ' . $card->name)

@section('content')
    <div class="container py-5">
        <div class="card shadow mx-auto border-0" style="max-width: 900px;">
            <div class="card-header bg-warning py-3 border-0">
                <h4 class="mb-0 text-dark fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifica Carta: {{ $card->name }}
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        {{-- Nome e Tipo --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome Carta</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                value="{{ $card->name }}" required shadow-sm>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo GCC</label>
                            <select name="type" class="form-select form-select-lg" required shadow-sm>
                                @foreach (['Fuoco', 'Acqua', 'Erba', 'Elettro', 'Psico', 'Lotta', 'Oscurità', 'Acciaio', 'Drago', 'Incolore', 'Folletto'] as $t)
                                    <option value="{{ $t }}" {{ $card->type == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Espansione con Modale --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Espansione / Set</label>
                            <div class="input-group input-group-lg">
                                <select name="expansion_id" id="expansion_id" class="form-select" required>
                                    @foreach ($expansions as $e)
                                        <option value="{{ $e->id }}"
                                            {{ $card->expansion_id == $e->id ? 'selected' : '' }}>
                                            {{ $e->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-primary fw-bold" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalExpansion">
                                    <i class="bi bi-plus-lg"></i> Nuovo Set
                                </button>
                            </div>
                        </div>

                        {{-- HP, Rarità e Prezzo --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">HP</label>
                            <input type="number" name="hp" class="form-control" value="{{ $card->hp }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rarità</label>
                            <select name="rarity" class="form-select">
                                @foreach (['Comune', 'Non Comune', 'Rara', 'Rara Holo', 'Ultra Rara', 'Illustrazione Rara', 'Illustrazione Speciale', 'Rara Segreta'] as $r)
                                    <option value="{{ $r }}" {{ $card->rarity == $r ? 'selected' : '' }}>
                                        {{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Prezzo (€)</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="{{ $card->price }}">
                        </div>

                        {{-- Gestione Immagini Esistenti --}}
                        <div class="col-12 mt-4">
                            <label class="fw-bold mb-2 text-danger"><i class="bi bi-images me-2"></i>Galleria Attuale
                                (Seleziona per rimuovere)</label>
                            <div class="row g-2 bg-light p-3 rounded border">
                                @forelse($card->images as $img)
                                    <div class="col-6 col-md-2 position-relative">
                                        <div class="card h-100 shadow-sm border-0">
                                            <img src="{{ asset('storage/' . $img->path) }}"
                                                class="card-img-top rounded shadow-sm"
                                                style="height: 120px; object-fit: cover;">
                                            <div class="card-body p-2 text-center">
                                                <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"
                                                    class="form-check-input border-danger shadow-sm">
                                                <label class="small text-danger d-block mt-1">Elimina</label>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small italic">Nessuna immagine caricata.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Aggiunta Nuove Immagini --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Aggiungi altre foto</label>
                            <input type="file" name="images[]" multiple class="form-control shadow-sm">
                        </div>

                        {{-- Descrizione --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Descrizione / Note</label>
                            <textarea name="description" class="form-control" rows="3">{{ $card->description }}</textarea>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <a href="{{ route('cards.index') }}" class="btn btn-light px-4 me-2">Annulla</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow">SALVA MODIFICHE</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Includiamo il partial della modale che abbiamo creato --}}
    @include('partials.modal-expansion')

@endsection
