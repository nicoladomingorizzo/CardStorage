<div class="modal fade" id="modalExpansion" tabindex="-1" aria-labelledby="modalExpansionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalExpansionLabel">Aggiungi Nuovo Set</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="new_expansion_name" class="form-label fw-bold">Nome dell'Espansione</label>
                    <input type="text" id="new_expansion_name" class="form-control"
                        placeholder="es. Scarlatto e Violetto">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="saveExpansion()">Salva Set</button>
            </div>
        </div>
    </div>
</div>

<script>
    function saveExpansion() {
        const nameInput = document.getElementById('new_expansion_name');
        const name = nameInput.value;
        if (!name) return alert('Per favore, inserisci il nome del set!');

        // Recupero del token CSRF (Laravel lo inserisce automaticamente in ogni form)
        const token = document.querySelector('input[name="_token"]').value;

        fetch('/expansions-store-ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    name: name
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // 1. Aggiungi alla select e seleziona
                    const select = document.getElementById('expansion_id');
                    const newOption = new Option(data.expansion.name, data.expansion.id, true, true);
                    select.add(newOption);

                    // 2. Chiudi la modale
                    const modalEl = document.getElementById('modalExpansion');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    // 3. Rimuovi forzatamente il "grigio" (backdrop)
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                    }, 100);

                    nameInput.value = '';
                }
            })
            .catch(err => alert("Errore nel salvataggio del set"));
    }
</script>
