const buttons = document.querySelectorAll(".course-btn"); //seleziona tutti gli elementi html che possiedono la classe "course-btn"

buttons.forEach(button => { //scorre tutti i bottone trovati nella pagina
  button.addEventListener("click", () => { //aggiunge un evento click ad ogni bottone
    const page = button.dataset.page; //recupera il valore dell'attributo data-page dal bottone cliccato
    window.location.href = page;//reindirizza l'utente alla pagina indicata-nel datapage
  });
});
