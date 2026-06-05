
const orariMuayThai = [ //array mthay corso
    "Martedì 18:00 - 19:30",
    "Giovedì 17:30 - 19:00",
    "Sabato 10:00 - 11:30"
];


let select = null;
let messaggio = null;
let listaPrenotazioni = null;




async function caricaPrenotazioni() {
    try {
       
        const response = await fetch("../php/get_prenotazioni.php?corso=Muay Thai", {
            credentials: "same-origin" // mantiene sessione
        });
        const result = await response.json();

        if (result.success) {
            if (listaPrenotazioni) listaPrenotazioni.innerHTML = "";
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

//gestione prenotazione nuovo orario
async function aggiungiPrenotazione() {
    const scelta = select.value;
    if (!scelta) return;

    const formData = new FormData();
    formData.append("corso", "Muay Thai");
    formData.append("orario", scelta);

    try {
        select.disabled = true;

        const response = await fetch("../php/prenota.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin" //mantiene la sessione
        });
        const result = await response.json();

        if (result.success) {
            messaggio.textContent = `✅ Prenotazione confermata: ${scelta}`;
            messaggio.className = "success"; 
            caricaPrenotazioni(); //ricarica lista
        } else {
            messaggio.textContent = `⚠ Errore: ${result.error}`;
            messaggio.className = "error"; 
        }
    } catch (err) {
        messaggio.textContent = "Errore di connessione al server";
        messaggio.className = "error"; 
    } finally {
        select.disabled = false;
        select.value = "";
    }
}

//Cancella prenotazione
async function cancellaPrenotazione(id) {
    try {
        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("../php/cancella_prenotazione.php", {
            method: "POST",
            body: formData,
            credentials: "same-origin"
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

//torna alla home
window.tornaHome = () => {
    window.location.href = "../php/home.php";
};

//inizializzo eventi
window.addEventListener("DOMContentLoaded", () => {
    select = document.getElementById("orari");
    messaggio = document.getElementById("messaggio");
    listaPrenotazioni = document.getElementById("lista-prenotazioni");

    orariMuayThai.forEach(orario => {
        const option = document.createElement("option");
        option.value = orario;
        option.textContent = orario;
        select.appendChild(option);
    });

    select.addEventListener("change", aggiungiPrenotazione);

    caricaPrenotazioni();
});