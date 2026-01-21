@extends('layouts.cards')
@section('title', 'Nuova Carta')

@section('content')
    <div class="container pb-5">
        <div class="card shadow mx-auto" style="max-width: 900px;">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0">➕ Aggiungi Carta GCC</h4>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm">
                        <p class="fw-bold">Attenzione! Ci sono degli errori:</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('cards.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        {{-- Nome --}}
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Nome Carta</label>
                            <input type="text" name="name" class="form-control"
                                placeholder="es. Charizard o Ricerca Accademica" required>
                        </div>

                        {{-- HP --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">HP</label>
                            <input type="number" name="hp" class="form-control"
                                placeholder="es. 120 (lascia vuoto per Allenatori)">
                        </div>

                        {{-- Tipo GCC --}}
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold">Tipo GCC</label>
                            <div class="input-group">
                                <select name="type" id="type_select" class="form-select" required>
                                    <option value="" disabled selected>Scegli il tipo...</option>
                                    @foreach ($existingTypes as $t)
                                        <option value="{{ $t->type }}">
                                            {{ $t->type }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalType">
                                    <i class="bi bi-plus-circle"></i> Nuovo Tipo
                                </button>
                            </div>
                        </div>

                        {{-- Rarità e Prezzo --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rarità</label>
                            <select name="rarity" class="form-select">
                                @foreach (['Comune', 'Non Comune', 'Rara', 'Rara Holo', 'Ultra Rara', 'Illustrazione Rara', 'Illustrazione Speciale', 'Rara Segreta'] as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prezzo (€)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                        </div>

                        {{-- Espansione --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Espansione</label>
                            <div class="input-group">
                                <select name="expansion_id" id="expansion_id" class="form-select" required>
                                    <option value="" disabled selected>Scegli il set...</option>
                                    @foreach ($expansions as $e)
                                        <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalExpansion">
                                    <i class="bi bi-plus-circle"></i> Nuovo Set
                                </button>
                            </div>
                        </div>

                        {{-- Galleria --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Galleria Immagini</label>
                            <input type="file" name="images[]" multiple class="form-control">
                            <small class="text-muted">Puoi selezionare più file contemporaneamente.</small>
                        </div>

                        {{-- Descrizione --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Effetto Carta / Descrizione</label>
                            <textarea name="description" class="form-control" rows="3"
                                placeholder="Dettagli aggiuntivi o testo della carta..."></textarea>
                        </div>

                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">SALVA CARTA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.partials.modal-expansion')
    @include('admin.partials.modal-type')
@endsection
