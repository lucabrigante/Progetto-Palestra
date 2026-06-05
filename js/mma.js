

const orariMMA = [ //array con orari del corso mma
    "Lunedì 15:30 - 17:00",
    "Mercoledì 17:30 - 19:00",
    "Venerdì 16:30 - 18:00"
];

//riferimenti per rendere il codice modulare
let select = null;
let messaggio = null;
let listaPrenotazioni = null;



//funz per caricare prenotazioni dal db
async function caricaPrenotazioni() {
    try {
        const response = await fetch("../php/get_prenotazioni.php?corso=MMA", {
            credentials: "same-origin"
        });
        const result = await response.json();

        if (result.success) {
            listaPrenotazioni.innerHTML = "";
            result.prenotazioni.forEach(item => {
                const li = document.createElement("li");
                li.textContent = item.orario;

                const btnCancella = document.createElement("button");
                btnCancella.textContent = "✖";
                btnCancella.addEventListener("click", () => cancellaPrenotazione(item.id));

                li.appendChild(btnCancella);
                listaPrenotazioni.appendChild(li);
            });
        } else {
            messaggio.textContent = "Errore nel caricamento prenotazioni";
            messaggio.className = "error"; 
        }
    } catch (err) {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    }
}

//prenotazione di un nuovo orario
async function aggiungiPrenotazione() {
    const scelta = select.value;
    if (!scelta) return;

    const formData = new FormData();
    formData.append("corso", "MMA");
    formData.append("orario", scelta);

    try {
        //disabilita momentaneamente la select per evitare clic multipli durante la fetch
        select.disabled = true;

        const response = await fetch("../php/prenota.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin" //serve per mantenere la sessione
        });

        const result = await response.json();

        if (result.success) {
            messaggio.textContent = `✅ Prenotazione confermata: ${scelta}`;
            messaggio.className = "success";
            caricaPrenotazioni();
        } else {
            messaggio.textContent = `⚠ Errore: ${result.error}`;
            messaggio.className = "error"; 
        }
    } catch (err) {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    } finally {
        //il reset del valore avviene in modo sicuro solo dopo la fine della fetch asincrona
        select.disabled = false;
        select.value = "";
    }
}

//cancella prenotazione dal DB
async function cancellaPrenotazione(id) {
    try {
        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("../php/cancella_prenotazione.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin" // <-- anche qui
        });

        const result = await response.json();

        if (result.success) {
            messaggio.textContent = "Prenotazione cancellata";
            messaggio.className = "success"; 
            caricaPrenotazioni();
        } else {
            messaggio.textContent = `⚠ Errore: ${result.error}`;
            messaggio.className = "error"; 
        }
    } catch (err) {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    }
}

// Torna alla home
window.tornaHome = () => {
    window.location.href = "../php/home.php";
};

//inizializzo eventi
window.addEventListener("DOMContentLoaded", () => {
    //inizializzazione degli elementi html per gestire il sistema di prenotazioni
    select = document.getElementById("orari");
    messaggio = document.getElementById("messaggio");
    listaPrenotazioni = document.getElementById("lista-prenotazioni");

    //popola la select con gli orari disponibili
    orariMMA.forEach(orario => {
        const option = document.createElement("option");
        option.value = orario;
        option.textContent = orario;
        select.appendChild(option);
    });

    //associa l'evento change alla funzione di prenotazione esterna
    select.addEventListener("change", aggiungiPrenotazione);

    //carica prenotazioni iniziali
    caricaPrenotazioni();
});