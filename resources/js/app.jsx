import './bootstrap';
import '~resources/scss/app.scss';
import '~icons/bootstrap-icons.scss';
import * as bootstrap from 'bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import GuestApp from './GuestApp';

const rootElement = document.getElementById('react-root');

if (rootElement) {
    const root = ReactDOM.createRoot(rootElement);
    root.render(
        <React.StrictMode>
            <BrowserRouter>
                <Routes>
                    {/* Se l'utente va su /guest, lo portiamo su /gallery*/}
                    <Route path="/guest" element={<Navigate to="/gallery" />} />

                    {/* Gestiamo sia la lista che il dettaglio sulla rotta /gallery */}
                    <Route path="/gallery/:name?" element={<GuestApp />} />

                    {/* Fallback: se sbaglia URL lo rimandiamo alla gallery */}
                    <Route path="*" element={<Navigate to="/gallery" />} />
                </Routes>
            </BrowserRouter>
        </React.StrictMode>
    );
}