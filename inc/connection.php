<?php
function getdataBase() {
    if ($dataBase = mysqli_connect('localhost', 'root', '', 'gestion_emprunt')) {
        // echo "Connexion réussie";
    } else {
        // echo "Echec de connexion";
    }
    return $dataBase;
}
?>