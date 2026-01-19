import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';

const GuestApp = () => {
    const [cards, setCards] = useState([]);
    const [loading, setLoading] = useState(true);
    const [search, setSearch] = useState('');
    const [selectedCard, setSelectedCard] = useState(null);
    const [currentImgIndex, setCurrentImgIndex] = useState(0);
    const filtered = cards.filter(c => c.name.toLowerCase().includes(search.toLowerCase()));
    const currentIndex = selectedCard ? filtered.findIndex(c => c.id === selectedCard.id) : -1;

    const navigate = useNavigate();
    const { name: nameParam } = useParams(); // Legge lo slug dall'URL (es: pikachu-v)

    // Funzione per creare lo slug (per non visualizzare l'id)
    const createSlug = (text) => {
        if (!text) return '';
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Per rimuovere caratteri speciali
            .replace(/[\s_-]+/g, '-') // Per sostituire spazi e underscore con un singolo trattino
            .replace(/^-+|-+$/g, ''); // Per rimuovere trattini all'inizio o alla fine
    };

    // Caricamento dati Api
    useEffect(() => {
        fetch('/api/cards')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setCards(data.results);
                }
                setLoading(false);
            });
    }, []);

    // Prendi i dai dell'url (Slug)
    useEffect(() => {
        if (nameParam && cards.length > 0) {
            const cardFound = cards.find(c => createSlug(c.name) === nameParam);
            if (cardFound) {
                setSelectedCard(cardFound);
                setCurrentImgIndex(0);
            } else {
                navigate('/gallery');
            }
        } else {
            setSelectedCard(null);
        }
    }, [nameParam, cards, navigate]);

    // Naviogazione per immagini
    const nextImg = (e) => {
        e.stopPropagation();
        if (selectedCard.images.length <= 1) return;
        setCurrentImgIndex((prev) => (prev + 1 === selectedCard.images.length ? 0 : prev + 1));
    };

    const prevImg = (e) => {
        e.stopPropagation();
        if (selectedCard.images.length <= 1) return;
        setCurrentImgIndex((prev) => (prev === 0 ? selectedCard.images.length - 1 : prev - 1));
    };

    // Navigazione tra carte
    const nextPokemon = (e) => {
        e.stopPropagation();
        const filtered = cards.filter(c => c.name.toLowerCase().includes(search.toLowerCase()));
        const currentIndex = filtered.findIndex(c => c.id === selectedCard.id);

        // Se non è l'ultima carta, continua
        if (currentIndex < filtered.length - 1) {
            navigate(`/gallery/${createSlug(filtered[currentIndex + 1].name)}`);
        }
    };

    const prevPokemon = (e) => {
        e.stopPropagation();
        const filtered = cards.filter(c => c.name.toLowerCase().includes(search.toLowerCase()));
        const currentIndex = filtered.findIndex(c => c.id === selectedCard.id);

        // Se non è la prima carta, continua
        if (currentIndex > 0) {
            navigate(`/gallery/${createSlug(filtered[currentIndex - 1].name)}`);
        }
    };

    const filteredCards = cards.filter(card => card.name.toLowerCase().includes(search.toLowerCase()));

    if (loading) return (
        <div className="d-flex justify-content-center align-items-center vh-100 bg-dark text-white text-uppercase fw-bold">
            <div className="spinner-border text-warning me-3"></div> Accesso al Pokédex...
        </div>
    );

    return (
        <div style={{ backgroundColor: '#121212', minHeight: '100vh', color: '#fff', paddingBottom: '50px' }}>
            <div className="container py-5">
                <header className="text-center mb-5">
                    <h1 className="fw-bold display-3 text-warning" style={{ textShadow: '3px 3px #000', letterSpacing: '2px' }}>
                        POKÉMON ARCHIVE
                    </h1>
                    <div className="row justify-content-center mt-4">
                        <div className="col-md-6">
                            <input
                                type="text" className="form-control form-control-lg bg-dark text-white border-warning shadow-lg"
                                placeholder="Cerca nella collezione..." value={search} onChange={(e) => setSearch(e.target.value)}
                                style={{ borderRadius: '50px', paddingLeft: '25px' }}
                            />
                        </div>
                    </div>
                </header>

                <div className="row g-4">
                    {filteredCards.map(card => (
                        <div key={card.id} className="col-6 col-md-4 col-lg-3">
                            <div
                                className="h-100 p-3 card-hover-effect"
                                onClick={() => navigate(`/gallery/${createSlug(card.name)}`)}
                                style={{
                                    background: 'linear-gradient(145deg, #ffd700, #c5a010)',
                                    borderRadius: '15px', border: '4px solid #222', cursor: 'pointer', transition: '0.3s'
                                }}
                            >
                                <div className="bg-white p-2 rounded mb-2 border shadow-inner">
                                    <div className="d-flex justify-content-between px-1 mb-1">
                                        <h6 className="fw-bold text-dark text-truncate mb-0" style={{ maxWidth: '75%' }}>{card.name}</h6>
                                        <span className="text-danger fw-bold small">HP {card.hp}</span>
                                    </div>
                                    <img
                                        src={card.images[0] ? `/storage/${card.images[0].path}` : ''}
                                        className="img-fluid w-100"
                                        style={{ height: '170px', objectFit: 'contain', background: '#f0f0f0', borderRadius: '4px' }}
                                        alt={card.name}
                                    />
                                </div>
                                <div className="text-center mt-3">
                                    <span className="badge w-100 mb-1 shadow-sm" style={{ backgroundColor: card.rarity_color || '#333', color: card.rarity_text_color || 'white' }}>
                                        {card.rarity}
                                    </span>
                                    <span className="badge rounded-pill px-3 shadow-sm" style={{ backgroundColor: card.type_bg_color || '#6c757d', color: card.type_text_color || 'white' }}>
                                        {card.type}
                                    </span>
                                    <div className="fw-bold mt-2 text-dark fs-4">€ {card.price}</div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {selectedCard && (
                    <div className="modal d-block" style={{ background: 'rgba(0,0,0,0.92)', zIndex: 1050 }} onClick={() => navigate('/gallery')}>
                        <div className="modal-dialog modal-lg modal-dialog-centered" onClick={(e) => e.stopPropagation()}>
                            <div className="modal-content border-0 shadow-lg bg-dark text-white" style={{ borderRadius: '30px', overflow: 'hidden' }}>
                                <div className="row g-0">

                                    <div className="col-md-6 d-flex align-items-center justify-content-center position-relative p-4 bg-black" style={{ minHeight: '450px' }}>
                                        {selectedCard.images.length > 1 && (
                                            <>
                                                <button className="btn btn-link text-warning position-absolute start-0 fs-1 px-4" onClick={prevImg} style={{ textDecoration: 'none', zIndex: 10 }}>‹</button>
                                                <button className="btn btn-link text-warning position-absolute end-0 fs-1 px-4" onClick={nextImg} style={{ textDecoration: 'none', zIndex: 10 }}>›</button>
                                            </>
                                        )}
                                        <img
                                            src={`/storage/${selectedCard.images[currentImgIndex]?.path}`}
                                            className="img-fluid rounded shadow-lg"
                                            style={{ maxHeight: '430px', border: '2px solid #444' }}
                                        />
                                        <div className="position-absolute bottom-0 mb-3 badge bg-warning text-dark px-3 py-2 rounded-pill shadow">
                                            {currentImgIndex + 1} / {selectedCard.images.length}
                                        </div>
                                    </div>

                                    <div className="col-md-6 p-5 position-relative d-flex flex-column">
                                        <button className="btn-close btn-close-white position-absolute top-0 end-0 m-4" onClick={() => navigate('/gallery')}></button>
                                        <div className="d-flex justify-content-between mb-4">
                                            <button
                                                className={`btn btn-sm btn-outline-warning rounded-pill px-3 ${currentIndex === 0 ? 'opacity-25' : ''}`}
                                                onClick={prevPokemon}
                                                disabled={currentIndex === 0} // Blocca il click se è la prima
                                            >
                                                ← Prev
                                            </button>

                                            <button
                                                className={`btn btn-sm btn-outline-warning rounded-pill px-3 ${currentIndex === filtered.length - 1 ? 'opacity-25' : ''}`}
                                                onClick={nextPokemon}
                                                disabled={currentIndex === filtered.length - 1} // Blocca il click se è l'ultima
                                            >
                                                Next →
                                            </button>
                                        </div>

                                        <h2 className="fw-bold text-warning mb-1 display-6">{selectedCard.name}</h2>
                                        <h4 className="text-danger fw-bold mb-4">HP {selectedCard.hp}</h4>

                                        <div className="mb-4 d-flex gap-2">
                                            <span className="badge p-2 px-3 shadow" style={{ backgroundColor: selectedCard.rarity_color || '#333', color: selectedCard.rarity_text_color || 'white' }}>
                                                {selectedCard.rarity}
                                            </span>
                                            <span className="badge p-2 px-3 rounded-pill shadow" style={{ backgroundColor: selectedCard.type_bg_color || '#6c757d', color: selectedCard.type_text_color || 'white' }}>
                                                {selectedCard.type}
                                            </span>
                                        </div>
                                        <hr className="border-secondary opacity-25" />
                                        <p className="small text-danger mb-1 fs-3">ESPANSIONE</p>
                                        <p className="mb-4 fw-bold text-info">{selectedCard.expansion?.name || 'Base Set'}</p>

                                        <div className="p-3 bg-secondary bg-opacity-10 rounded border border-secondary border-opacity-25 italic mb-auto shadow-inner">
                                            "{selectedCard.description || "Nessun dato disponibile."}"
                                        </div>

                                        <div className="mt-4 pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                            <span className="h1 fw-bold text-success mb-0">€ {selectedCard.price}</span>
                                            <span className="text-muted small">#{selectedCard.id}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            <style dangerouslySetInnerHTML={{
                __html: `
                .card-hover-effect:hover { 
                    transform: translateY(-10px) rotate(1deg); 
                    box-shadow: 0 20px 40px rgba(255, 215, 0, 0.25) !important; 
                }
                .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
            `}} />
        </div>
    );
};

export default GuestApp;