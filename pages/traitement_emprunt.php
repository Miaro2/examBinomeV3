<?php
session_start();
require("../inc/connection.php");
require("../inc/function.php");

$nombre_de_jour = $_POST['nb_jours'];
$id_objet = $_POST['id_objet'];

emprunter($id_objet, $nombre_de_jour);

?>