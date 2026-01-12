@extends('layouts.cards')
@section('title', 'Modifica Carta')

@section('content')
    <div class="container pb-5">
        <div class="card shadow mx-auto" style="max-width: 900px;">
            <div class="card-header bg-warning py-3">
                <h4 class="mb-0">✏️ Modifica: {{ $card->name }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('cards.update', $card) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nome</label>
                            <input type="text" name="name" class="form-control" value="{{ $card->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo GCC</label>
                            <select name="type" class="form-select">
                                @foreach (['Erba', 'Fuoco', 'Acqua', 'Elettro', 'Psico', 'Lotta', 'Buio', 'Acciaio', 'Drago', 'Incolore', 'Folletto'] as $t)
                                    <option value="{{ $t }}" {{ $card->type == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

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

                        <div class="col-12">
                            <label class="form-label fw-bold">Espansione</label>
                            <select name="expansion_id" class="form-select">
                                @foreach ($expansions as $e)
                                    <option value="{{ $e->id }}"
                                        {{ $card->expansion_id == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <label class="fw-bold mb-2 text-danger">Galleria Attuale (Seleziona le foto da
                                eliminare)</label>
                            <div class="row g-2">
                                @foreach ($card->images as $img)
                                    <div class="col-md-2 position-relative">
                                        <img src="{{ asset('storage/' . $img->path) }}" class="img-fluid rounded border">
                                        <div class="position-absolute top-0 start-0 ms-2 mt-1">
                                            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"
                                                class="form-check-input shadow border-danger">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold">Aggiungi Nuove Immagini</label>
                            <input type="file" name="images[]" multiple class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Descrizione</label>
                            <textarea name="description" class="form-control" rows="3">{{ $card->description }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">AGGIORNA CARTA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
