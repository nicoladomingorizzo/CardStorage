@extends('layouts.cards')
@section('content')
    <div class="container py-4">
        <div class="card shadow-lg overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 bg-dark d-flex align-items-center justify-content-center p-3">
                    @if ($card->images->count() > 0)
                        <div id="cardGallery" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($card->images as $k => $img)
                                    <div class="carousel-item {{ $k == 0 ? 'active' : '' }}"><img
                                            src="{{ asset('storage/' . $img->path) }}" class="d-block w-100 rounded shadow">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" data-bs-target="#cardGallery" data-bs-slide="prev"><span
                                    class="carousel-control-prev-icon"></span></button>
                            <button class="carousel-control-next" data-bs-target="#cardGallery" data-bs-slide="next"><span
                                    class="carousel-control-next-icon"></span></button>
                        </div>
                    @else
                        <div class="text-white">Nessuna immagine disponibile</div>
                    @endif
                </div>
                <div class="col-md-7 p-5">
                    <h1 class="fw-bold">{{ $card->name }}</h1>
                    <h4 class="text-muted mb-4">{{ $card->expansion->name }}</h4>
                    <div class="row mb-4">
                        <div class="col-6 mb-2"><strong>Tipo:</strong> {{ $card->type }}</div>
                        <div class="col-6 mb-2"><strong>Rarità:</strong> {{ $card->rarity }}</div>
                        <div class="col-6 mb-2"><strong>HP:</strong> {{ $card->hp ?? 'N/D' }}</div>
                        <div class="col-6 mb-2"><strong>Valore:</strong> <span
                                class="text-success fw-bold">€{{ number_format($card->price, 2) }}</span></div>
                    </div>
                    <h5>Descrizione:</h5>
                    <p class="bg-light p-3 rounded border italic">{{ $card->description ?? 'Nessuna nota aggiuntiva.' }}</p>
                    <a href="{{ route('cards.index') }}" class="btn btn-outline-dark mt-4">← Torna alla Collezione</a>
                </div>
            </div>
        </div>
    </div>
@endsection
