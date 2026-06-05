const orariBoxing = [ //array con orari
    "Lunedì 17:00 - 18:30",
    "Mercoledì 19:00 - 20:30",
    "Venerdì 18:00 - 19:30"
];


let select = null;
let messaggio = null;
let listaPrenotazioni = null;



//carica prenotazioni dal db
async function caricaPrenotazioni() {
    try {
        // Il percorso esce da views/ ed entra in php/
        const response = await fetch("../php/get_prenotazioni.php?corso=Boxing");
        const result = await response.json();//attende la risposta del server e la converte da formato JSON in un oggetto JavaScript.

        if (result.success) {//controlla se la proprietà 'success' della risposta json è true
            listaPrenotazioni.innerHTML = "";
            result.prenotazioni.forEach(item => {//itera
                const li = document.createElement("li");
                li.textContent = item.orario;//imposta il testo dell'elemento <li> con l'orario della singola prenotazione.

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
    } catch {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    }
}

//qui sempre funz. asincrona per aggiungere una nuova pren
async function aggiungiPrenotazione() {
    const scelta = select.value;
    if (!scelta) return;

    const formData = new FormData();
    formData.append("corso", "Boxing");
    formData.append("orario", scelta);

    try { //avvio blocco try per gestione degli errori
        select.disabled = true;

        //il percorso esce da views/ ed entra in php/
        const response = await fetch("../php/prenota.php", {
            method: "POST", //specifico metodo http(post)
            body: formData //inserisco oggettoFormData compilato come corpo della richiesta
        });
        const result = await response.json(); //attende e converte la risposta json del server

        if (result.success) {
            messaggio.textContent = `✅ Prenotazione confermata: ${scelta}`;
            messaggio.className = "success"; 
            caricaPrenotazioni();
        } else {
            messaggio.textContent = `⚠ Errore: ${result.error}`;
            messaggio.className = "error"; 
        }
    } catch { //se c'è problema di comunicazione con server
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    } finally {
        select.disabled = false;
        select.value = "";
    }
}

// Cancella prenotazione
async function cancellaPrenotazione(id) {
    try {
        const formData = new FormData();
        formData.append("id", id);

        //il percorso esce da views/ ed entra in php/
        const response = await fetch("../php/cancella_prenotazione.php", {
            method: "POST",
            body: formData
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
    } catch {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    }
}

//torna home
window.tornaHome = () => {
    window.location.href = "../php/home.php";
};

//inizializzo eventi
window.addEventListener("DOMContentLoaded", () => {
    select = document.getElementById("orari");
    messaggio = document.getElementById("messaggio");
    listaPrenotazioni = document.getElementById("lista-prenotazioni");

    orariBoxing.forEach(orario => {//esegue un ciclo per ogni stringa all'interno dell'array 'orariBoxing'.
        const option = document.createElement("option");
        option.value = orario;
        option.textContent = orario;
        select.appendChild(option);
    });

    select.addEventListener("change", aggiungiPrenotazione);

    caricaPrenotazioni();
});