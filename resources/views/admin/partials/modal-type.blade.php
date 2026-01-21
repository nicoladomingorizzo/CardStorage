<div class="modal fade" id="modalType" tabindex="-1" aria-labelledby="modalTypeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTypeLabel">
                    <i class="bi bi-plus-lg me-2"></i>Aggiungi Nuovo Tipo GCC
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-2">
                    <label for="new_type_name" class="form-label fw-bold">Nome del Tipo</label>
                    <input type="text" id="new_type_name" class="form-control form-control-lg"
                        placeholder="es: Allenatore - Aiuto, Energia Speciale...">
                    <div class="form-text">Inserisci il nome della nuova categoria di carte.</div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="addNewType()">
                    <i class="bi bi-check2-circle me-1"></i> Conferma e Aggiungi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function addNewType() {
        const nameInput = document.getElementById('new_type_name');
        const name = nameInput.value.trim();

        if (name === "") {
            alert("Per favore, inserisci un nome per il nuovo tipo.");
            return;
        }

        const select = document.getElementById('type_select');

        // Verifica se il tipo esiste già nella tendina per evitare duplicati
        let exists = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value.toLowerCase() === name.toLowerCase()) {
                exists = true;
                break;
            }
        }

        if (exists) {
            alert("Questo tipo esiste già nella lista!");
            return;
        }

        // Crea la nuova opzione e aggiungila alla select
        const option = document.createElement('option');
        option.value = name;
        option.text = name;
        option.selected = true;

        select.add(option);

        // Chiudi la modale
        const modalElement = document.getElementById('modalType');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();

        // Pulisci il campo input per il prossimo utilizzo
        nameInput.value = "";
    }
</script>
