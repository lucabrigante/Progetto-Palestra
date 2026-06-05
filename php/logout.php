<?php
session_start(); //accede alla sessione corrente
session_destroy(); //cancella tutti i dati della sessione (svuota il login)

//reindirizza alla nuova pagina di login dentro la cartella views
header("Location: ../views/login.php");
exit;
?>