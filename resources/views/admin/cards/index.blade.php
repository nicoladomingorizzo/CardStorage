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
            <a href="{{ route('admin.cards.create') }}" class="btn btn-primary px-4 shadow-sm fw-bold">
                <i class="bi bi-plus-lg"></i> Nuova Carta
            </a>
        </div>

        {{-- Messaggi di Successo --}}
        @if (session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
                role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Tabella --}}
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
                                <th>Valore</th>
                                <th class="text-end pe-4">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cards as $card)
                                <tr>
                                    <td class="ps-4">
                                        @php $thumb = $card->images->first(); @endphp
                                        <img src="{{ $thumb ? asset('storage/' . $thumb->path) : 'https://via.placeholder.com/150x210?text=N/D' }}"
                                            class="rounded shadow-sm border"
                                            style="height: 65px; width: 48px; object-fit: cover;">
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-bold text-dark fs-5">{{ $card->name }}</div>
                                        <div class="text-muted small">
                                            <span class="badge bg-light text-dark border">HP {{ $card->hp ?? '???' }}</span>
                                            <span>{{ $card->expansion->name ?? 'Set Sconosciuto' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $tcgTypes = [
                                                'Fuoco' => ['bg' => '#F08030', 'text' => 'white'],
                                                'Acqua' => ['bg' => '#6890F0', 'text' => 'white'],
                                                'Erba' => ['bg' => '#78C850', 'text' => 'white'],
                                                'Elettro' => ['bg' => '#F8D030', 'text' => 'black'],
                                                'Psico' => ['bg' => '#F85888', 'text' => 'white'],
                                                'Lotta' => ['bg' => '#C03028', 'text' => 'white'],
                                                'Oscurità' => ['bg' => '#705848', 'text' => 'white'],
                                                'Metallo' => ['bg' => '#B8B8D0', 'text' => 'black'],
                                                'Drago' => ['bg' => '#7038F8', 'text' => 'white'],
                                                'Incolore' => ['bg' => '#A8A878', 'text' => 'white'],
                                                'Allenatore' => ['bg' => '#4a90e2', 'text' => 'white'],
                                            ];
                                            $style = $tcgTypes[$card->type] ?? ['bg' => '#6c757d', 'text' => 'white'];
                                        @endphp
                                        <span class="badge shadow-sm text-uppercase"
                                            style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }}; padding: 8px 12px; min-width: 90px;">
                                            {{ $card->type }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-dark">{{ $card->rarity }}</span></td>
                                    <td><span
                                            class="fw-bold text-success">€{{ number_format($card->price, 2, ',', '.') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('admin.cards.show', $card) }}"
                                                class="btn btn-outline-dark btn-sm"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('admin.cards.edit', $card) }}"
                                                class="btn btn-outline-dark btn-sm"><i class="bi bi-pencil"></i></a>
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
                                    <td colspan="6" class="py-5 text-muted">Nessuna carta in collezione.</td>
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
                    <h5 class="modal-title">Conferma Eliminazione</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p>Vuoi davvero eliminare <strong id="delName" class="text-danger"></strong>?</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <form id="delForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Elimina</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scomparsa automatica alert
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    new bootstrap.Alert(alert).close();
                }, 3000);
            }

            // Gestione Dinamica Modale
            const deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                document.getElementById('delName').textContent = name;

                // L'URL DEVE corrispondere al prefisso 'admin' definito in web.php
                document.getElementById('delForm').action = '/admin/cards/' + id;
            });
        });
    </script>
@endsection
