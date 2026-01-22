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

                <div class="row mt-3">
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label fw-bold">Espansione / Set</label>
                        <div class="input-group">
                            <select name="expansion_id" id="expansion_select" class="form-select" required>
                                <option value="">Seleziona Set...</option>
                                @foreach ($expansions as $expansion)
                                    <option value="{{ $expansion->id }}">{{ $expansion->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#newExpansionModal">+</button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">Rarità</label>
                        <input type="text" name="rarity" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3 text-start">
                        <label class="form-label fw-bold">Prezzo (€)</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="mb-4 mt-3 text-start">
                    <label class="form-label fw-bold">Descrizione</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Carica Immagini</label>
                    <input type="file" name="images[]" class="form-control" multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="mt-2">
                        <small class="text-muted d-block">
                            <i class="bi bi-info-circle me-1"></i> Formati consentiti: <strong>JPEG, PNG, JPG,
                                WEBP</strong>.
                        </small>
                        <small class="text-primary italic">Stiamo lavorando per implementare ulteriori formati
                            consentiti.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 shadow fw-bold">SALVA CARTA</button>
            </form>
        </div>
    </div>

    {{-- Modale e Script rimangono identici --}}
    <div class="modal fade" id="newExpansionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Nuovo Set</h5><button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start"><label class="form-label">Nome Set</label><input type="text"
                        id="new_expansion_name" class="form-control"></div>
                <div class="modal-footer"><button type="button" class="btn btn-primary"
                        id="save_expansion_btn">Salva</button></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('save_expansion_btn').addEventListener('click', function() {
            const name = document.getElementById('new_expansion_name').value;
            fetch("{{ route('expansions.ajax') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: name
                    })
                })
                .then(res => res.json()).then(data => {
                    if (data.success) {
                        const select = document.getElementById('expansion_select');
                        select.add(new Option(data.name, data.id, true, true));
                        bootstrap.Modal.getInstance(document.getElementById('newExpansionModal')).hide();
                    }
                });
        });
    </script>
@endsection
