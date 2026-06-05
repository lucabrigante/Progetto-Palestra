
//qua vado a recuperare gli elementi html per gestire il sistema di prenotazioni
const giornoInput = document.getElementById("giorno"); //input data prenotazione
const fasciaSelect = document.getElementById("fascia"); //select fascia oraria
const prenotaBtn = document.getElementById("prenota-btn"); //bottone prenota
const prenotaMsg = document.getElementById("prenota-msg"); //messaggi di feedback utente
const listaPrenotazioni = document.getElementById("lista-prenotazioni"); //lista prenotazioni visualizzate

//STATO DELLE PRENOTAZIONI
let prenotazioniAttuali = [];

//PRENOTAZIONI DAL SERVER
async function caricaPrenotazioni() { 
    try { 
        const resp = await fetch("../php/get_prenotazioni_sala.php", { credentials: "same-origin" });
        const result = await resp.json(); 
        
        if(result.success){ 
            listaPrenotazioni.innerHTML = ""; 
            prenotazioniAttuali = result.prenotazioni; //salviamo le pren. ricevute
            
            result.prenotazioni.forEach(p=>{ 
                const li = document.createElement("li"); 
                li.textContent = `${p.giorno} → ${p.fascia}`; 
                
                const btnCanc = document.createElement("button");
                btnCanc.textContent = "✖";
                btnCanc.style.marginLeft="10px";
                btnCanc.addEventListener("click",()=>cancellaPrenotazione(p.id));
                
                li.appendChild(btnCanc); 
                listaPrenotazioni.appendChild(li); 
            });
        } else { 
            listaPrenotazioni.innerHTML = "<li>Nessuna prenotazione presente.</li>";
            prenotazioniAttuali = []; //resetta l'array se non ci sono prenotazioni
        }
    } catch { 
        prenotaMsg.textContent = "Errore di connessione al server";
        prenotaMsg.className = "error";
    }
}

//funzione aggiungi prenotazione
async function aggiungiPrenotazione(){

    const giorno = giornoInput.value; //data selezionata
    const fascia = fasciaSelect.value; //fascia selezionata

    //controllo campi obbligatori
    if(!giorno || !fascia){ prenotaMsg.textContent="Seleziona giorno e fascia oraria.";
    prenotaMsg.className="error";
    return; 
}

//controllo client: solo 7 giorni e non passato
    const oggi = new Date();
    oggi.setHours(0, 0, 0, 0); //azzera l'ora corrente per confrontare solo i giorni

    const dataSelezionata = new Date(giorno);
    dataSelezionata.setHours(0, 0, 0, 0); //azzera l'ora della data scelta

    //differenza in millisecondi convertita in giorni interi
    const diffTempo = dataSelezionata.getTime() - oggi.getTime();
    const diffGiorni = Math.round(diffTempo / (1000 * 60 * 60 * 24));

    //non consentito prenotare nel passato
    if (diffGiorni < 0) {
        prenotaMsg.textContent = "Non puoi prenotare un giorno già passato.";
        prenotaMsg.className = "error"; 
        return;
    }
    //limite massimo prenotazione (7 giorni)
    if (diffGiorni > 7) {
        prenotaMsg.textContent = "Puoi prenotarti solo entro 7 giorni da oggi.";
        prenotaMsg.className = "error";
        return;
    }

    //controllo anti duplicato cioè max 1 pren al giorno
    const giaPrenotato = prenotazioniAttuali.some(p => p.giorno === giorno);
    if (giaPrenotato) {
        prenotaMsg.textContent = "Hai già una prenotazione per questo giorno. Massimo una sessione al giorno.";
        prenotaMsg.className = "error";
        return; //blocca l'invio della richiesta
    }
    try {
        //costruzione dati da inviare al server
        const fd = new FormData();
        fd.append("giorno",giorno);
        fd.append("fascia",fascia);

        //richiesta POST al backend
        const resp = await fetch("../php/prenota_sala.php",{
            method:"POST",
            body:fd, //viene inviato un oggettoFormData(fd) che contine i dati della prenotazione, questo permette al backend di riceverli tramite il post in modo semplice
            credentials:"same-origin" //mantiene sessione utente
        });

        //risposta JSON dal server
        const result = await resp.json();
        
        //messaggio feedback
        prenotaMsg.textContent = result.success
         ? "✅ Prenotazione confermata!" 
         : `⚠ ${result.error}`;
        prenotaMsg.className = result.success?"success":"error";
        caricaPrenotazioni(); //aggiorno lista prenotazioni
    } catch {
        prenotaMsg.textContent = "Errore di connessione al server";
        prenotaMsg.className="error";
    }
}

//cancella prenotazione tramite id
async function cancellaPrenotazione(id){
    try{
        const fd = new FormData(); //creo un contenitore per inviare dati al server in POST
        fd.append("id",id); //inserisco l'id della prenotazione da cancellare
        //chiamata al backend PHP che elimina la prenotazione dal database
        const resp = await fetch("../php/cancella_prenotazione_sala.php",{
            method:"POST",
            body:fd,
            credentials:"same-origin" //mantiene attiva sessione
        });

        const result = await resp.json(); //converto risposta server in formato json
        //messaggio di feedback all'utente in base all'esito
        prenotaMsg.textContent = result.success
        ?"Prenotazione cancellata."
        :`⚠ ${result.error}`;
        //cambio stile del messaggio(successso o errore)
        prenotaMsg.className = result.success?"success":"error";
        caricaPrenotazioni(); //carico la lista aggiornata del prenotazioni
    } catch { //caso in cui fallisce la connessione o il server non risponde
        prenotaMsg.textContent = "Errore di connessione al server";
        prenotaMsg.className="error";
    }
}

//evento: quando clicco il bottone prenotazione, creo una nuova prenotazione
prenotaBtn.addEventListener("click",aggiungiPrenotazione);

//caricamento iniziale delle prenotazioni quando la pagina si apre
caricaPrenotazioni();

//SEZIONE SCHEDA ESERCIZI
const workoutGrid = document.getElementById("workout-grid"); //recupero il contenitore dove verrano mostrati gli esercizi
const aggiungiBtn = document.getElementById("aggiungi-esercizio"); //bottone per aggiungere un nuovo esercizio vuoto

async function caricaEsercizi(){ //carica tutti gli esercizi dal database
    try{
        //richiesta al backend per ottenere gli esercizi salvati
        const resp = await fetch("../php/get_esercizi_sala.php",{
            credentials:"same-origin"
        });
        //parsing della risposta in json
        const result = await resp.json();

        //se la richiesta è andata a buon fine
        if(result.success){
            workoutGrid.innerHTML=""; //svuoto la griglia prima di ricaricare i dati 
            result.esercizi.forEach(e=>creaEsercizioDOM(e)); //per ogni esercizio ricevuto dal server
            //creo dinamicamente un elemento grafico nella pagina cosi da visualizzarlo all'interno della griglia workout
        }
    } catch(error) {
        console.error("errore nel caricamento esercizi:", error);
    }
}

//creo un elemento html che rappresenta un esercizio
function creaEsercizioDOM(esercizio=null){
    //creo un contenitore div per l'esercizio
    const div=document.createElement("div");
    div.className="esercizio";

    //inserisco la struttura html dell'esercizio
    div.innerHTML=`
        <input type="text" placeholder="Nome esercizio" class="nome-esercizio" value="${esercizio?.nome||""}">
        <input type="number" placeholder="Serie" class="serie" min="1" value="${esercizio?.serie||""}">
        <input type="number" placeholder="Ripetizioni" class="ripetizioni" min="1" value="${esercizio?.ripetizioni||""}">
        <input type="number" placeholder="Carico (kg)" class="carico" min="0" value="${esercizio?.carico||""}">
        <button class="salva-esercizio">💾</button>
        <button class="rimuovi-esercizio">✖</button>
    `;

    //evento per eliminare l'esercizio (e dal database se esiste id)
    div.querySelector(".rimuovi-esercizio").addEventListener("click",()=>{
        if(esercizio?.id) cancellaEsercizio(esercizio.id);
        div.remove(); //rimuove solo lato frontend
    });

    //evento per salvare o aggiornare esercizio
    div.querySelector(".salva-esercizio").addEventListener("click",()=>salvaEsercizio(div,esercizio?.id));
    workoutGrid.appendChild(div); //aggiungo il blocco alla griglia principale 
}

//salva un esercizio nel database(nuovo o aggiornamento)
function salvaEsercizio(div,id=null){

    const nome = div.querySelector(".nome-esercizio").value.trim();
    const serieRaw = div.querySelector(".serie").value;
    const ripetizioniRaw = div.querySelector(".ripetizioni").value;
    const caricoRaw = div.querySelector(".carico").value;

    const serie = Number(serieRaw);
    const ripetizioni = Number(ripetizioniRaw);
    const carico = Number(caricoRaw);

    //controllo vero numerico
    if (
        nome === "" ||
        !Number.isInteger(serie) ||
        !Number.isInteger(ripetizioni) ||
        !Number.isFinite(carico)
    ) {
        prenotaMsg.textContent = "Serie, ripetizioni e carico devono essere numeri validi.";
        prenotaMsg.className = "error";
        return;
    }

    const fd = new FormData();

    if(id) fd.append("id",id);

    fd.append("nome",nome);
    fd.append("serie",serie);
    fd.append("ripetizioni",ripetizioni);
    fd.append("carico",carico);

    fetch("../php/salva_esercizio.php",{
        method:"POST",
        body:fd,
        credentials:"same-origin"
    })
    .then(r=>r.json())
    .then(res=>{
        prenotaMsg.textContent = res.success
            ? "Esercizio salvato"
            : (res.error || "Errore");
        prenotaMsg.className = res.success ? "success" : "error";

        if(res.success) caricaEsercizi();
    });
}
//cancella un esercizio dal database
async function cancellaEsercizio(id){

    //preparo i dati per backend
    const fd=new FormData();
    fd.append("id",id);

    //richiesta eliminazione
    await fetch("../php/cancella_esercizio.php",{
        method:"POST",
        body:fd,
        credentials:"same-origin"
    });
    
    //aggiorno
    caricaEsercizi();
}

//quando clicco il bottone creo un blocco vuoto
aggiungiBtn.addEventListener("click",()=>creaEsercizioDOM());
caricaEsercizi(); //caricamento iniziale esercizi





//CRONOMETRO
const display=document.getElementById("display-cronometro"); //elemento display del timer
//bottoni controllo cronometro
const startBtn=document.getElementById("start-crono");
const pauseBtn=document.getElementById("pause-crono");
const resetBtn=document.getElementById("reset-crono");


let timer = 0;//tempo totale in secondi

let interval = null; //riferimento intervallo (serve per stop/start)

//funzione che aggiorna il display del cronometro
function aggiornaDisplay(){

    //converte i secondi totali in minuti interi (divisione per 60)
    //padstart è una funzione delle stringhe che aggiunge i caratteri all'inziio della stringa fino a ragigungere una lunghezza minima
    //altrimenti avresti gli orari non simmetrici
    const minuti=String(Math.floor(timer/60)).padStart(2,"0");

    //ricava i secondi rimanenti dopo aver tolto i minuti
    const secondi=String(timer%60).padStart(2,"0");

    //serve a mostrare a schermo il tempo del cronometro
    display.textContent=`${minuti}:${secondi}`;
}
//funzione per avviare il cronometro
function startCrono(){

    //controllo per evitare di avviare piu intervalli contemporanemaente 
     if(!interval)

        //avvia un timer che esegue il codice ogni 1000 ms(1secondo)
         interval=setInterval(()=>{

        //incrementa il tempo totale di 1 secondo
        timer++;
        
        //aggiorna il display ad ogni tick del timer
        aggiornaDisplay();

    },1000);
}

//funzione per mettere in pausa il cronometro
function pauseCrono(){

    //ferma l'intervallo attivo del cronometro
     clearInterval(interval);

     //resetta la variabile per permettere un nuovo start futuro
      interval=null;
    }

//funzione per resettare completamente il cronometro
function resetCrono(){
    //prima interrompe il timer se attivo
    pauseCrono();
    //riporta il tempo totale a zero
    timer=0;
    //aggiorna subito il display per mostrare 00;00
    aggiornaDisplay();
}


startBtn.addEventListener("click",startCrono); // evento click: avvio cronometro
pauseBtn.addEventListener("click",pauseCrono); // evento click: pausa cronometro
resetBtn.addEventListener("click",resetCrono); // evento click: reset cronometro


//bottone per tornare alla pagina principale
document.getElementById("torna-home").addEventListener("click",()=>{ window.location.href="../php/home.php"; });