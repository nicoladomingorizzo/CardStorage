@extends('layouts.cards')

@section('title', 'La mia Collezione Pokémon')

@section('content')
    <div class="container py-5">
        {{-- Testata --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-0">🎴 La mia Collezione</h2>
                <p class="text-muted">Gestisci le tue carte e monitora il valore della raccolta</p>
            </div>
            <a href="{{ route('cards.create') }}" class="btn btn-primary px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg"></i> Nuova Carta
            </a>
        </div>

        {{-- Messaggio di Successo con Auto-close --}}
        @if (session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
                role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabella Principale --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Foto</th>
                                <th class="text-start">Dettagli Carta</th>
                                <th>Tipo</th>
                                <th>Rarità</th>
                                <th>Valore d'acquisto</th>
                                <th class="text-end pe-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cards as $card)
                                <tr>
                                    {{-- Anteprima Foto --}}
                                    <td class="ps-4">
                                        @php $thumb = $card->images->first(); @endphp
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $thumb ? asset('storage/' . $thumb->path) : 'https://via.placeholder.com/150x210?text=N/D' }}"
                                                alt="{{ $card->name }}" class="rounded shadow-sm border"
                                                style="height: 65px; width: 48px; object-fit: cover;">
                                            @if ($card->images->count() > 1)
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark"
                                                    style="font-size: 0.6rem;">
                                                    +{{ $card->images->count() - 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Nome e HP --}}
                                    <td class="text-start">
                                        <div class="fw-bold text-dark fs-5">{{ $card->name }}</div>
                                        <div class="text-muted small">
                                            <span class="badge bg-light text-dark border">HP {{ $card->hp ?? '???' }}</span>
                                            <span class="ms-1">{{ $card->expansion->name ?? 'Set Sconosciuto' }}</span>
                                        </div>
                                    </td>

                                    {{-- Tipo GCC con colori personalizzati --}}
                                    <td>
                                        @php
                                            $tcgTypes = [
                                                'Fuoco' => ['bg' => '#ff0000', 'text' => 'white'],
                                                'Acqua' => ['bg' => '#0077ff', 'text' => 'white'],
                                                'Erba' => ['bg' => '#22aa22', 'text' => 'white'],
                                                'Elettro' => [
                                                    'bg' => '#ffff99',
                                                    'text' => 'black',
                                                    'border' => '#e6e600',
                                                ], // Giallo Chiaro
                                                'Psico' => ['bg' => '#aa22aa', 'text' => 'white'],
                                                'Lotta' => ['bg' => '#884422', 'text' => 'white'],
                                                'Oscurità' => ['bg' => '#333333', 'text' => 'white'],
                                                'Acciaio' => ['bg' => '#778899', 'text' => 'white'],
                                                'Drago' => ['bg' => '#ccaa00', 'text' => 'white'], // Giallo Scuro
                                                'Incolore' => ['bg' => '#dddddd', 'text' => 'black'],
                                                'Folletto' => ['bg' => '#ff99cc', 'text' => 'black'],
                                            ];
                                            $style = $tcgTypes[$card->type] ?? ['bg' => '#6c757d', 'text' => 'white'];
                                        @endphp
                                        <span class="badge shadow-sm"
                                            style="background-color: {{ $style['bg'] }}; 
                                                color: {{ $style['text'] }}; 
                                                border: {{ $style['border'] ?? 'none' }} 1px solid;
                                                padding: 8px 12px; min-width: 85px; font-weight: 800;">
                                            {{ strtoupper($card->type) }}
                                        </span>
                                    </td>

                                    {{-- Rarità --}}
                                    <td>
                                        @php
                                            $rarityClass = match ($card->rarity) {
                                                'Ultra Rara' => 'bg-danger',
                                                'Rara Segreta' => 'bg-warning text-dark',
                                                'Illustrazione Speciale' => 'bg-primary',
                                                'Rara Holo' => 'bg-success',
                                                'Comune' => 'bg-secondary',
                                                default => 'bg-dark',
                                            };
                                        @endphp
                                        <span class="badge {{ $rarityClass }} py-2 px-3 shadow-sm"
                                            style="font-size: 0.75rem;">
                                            {{ $card->rarity }}
                                        </span>
                                    </td>

                                    {{-- Prezzo --}}
                                    <td>
                                        <span class="fw-bold text-success fs-5">
                                            €{{ number_format($card->price, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    {{-- Pulsanti Azione --}}
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('cards.show', $card) }}" class="btn btn-outline-dark btn-sm"
                                                title="Dettagli">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('cards.edit', $card) }}" class="btn btn-outline-dark btn-sm"
                                                title="Modifica">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-id="{{ $card->id }}" data-name="{{ $card->name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-5 text-muted">
                                        <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                                        Non ci sono ancora carte nella tua collezione.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale Eliminazione --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Conferma Eliminazione</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p>Sei sicuro di voler eliminare definitivamente la carta:</p>
                    <h4 class="fw-bold text-danger" id="delName"></h4>
                    <p class="text-muted small mb-0">L'operazione rimuoverà anche tutte le foto caricate.</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <form id="delForm" method="POST" action="">
                        @csrf
                        @method('destroy')
                        <button type="submit" class="btn btn-danger px-4">Elimina</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Gestione Interfaccia --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chiusura automatica alert dopo 3.5 secondi
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3500);
            }

            // Popolamento dinamico modale eliminazione
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                document.getElementById('delName').textContent = name;
                document.getElementById('delForm').action = '/cards/' + id;
            });
        });
    </script>
@endsection
