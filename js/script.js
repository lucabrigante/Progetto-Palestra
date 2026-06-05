const usernameInput = document.querySelector('input[name="username"]'); //recupero l'input del nome utente 
const passwordInput = document.querySelector('input[name="password"]');//recupero l'input della password 
const submitButton = document.querySelector('button[type="submit"]'); //recupero il bottone di submit

function checkInputs() { //controlla se gli input sono falidi
  //trim() rimuove spazi all'inizio e alla fine
  const usernameVal = usernameInput.value.trim();
  const passwordVal = passwordInput.value.trim();

  //abilita il bottone solo se entrambi i campi hanno contenuto
  submitButton.disabled = !(usernameVal && passwordVal);
}

//ascolta ogni input 
usernameInput.addEventListener("input", checkInputs);
passwordInput.addEventListener("input", checkInputs);

// inizializza stato del bottone al caricamento pagina
checkInputs();