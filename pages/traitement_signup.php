<?php
    session_start();
    require('../inc/function.php');
    require('../inc/connection.php');

    $nom = $_GET['name'];
    $mail = $_GET['mail'];
    $mdp = $_GET['mdp'];
    $date = $_GET['date'];
    $genre = $_GET['genre'] ?? 'H';
    $ville = $_GET['ville'];

    create_compte($nom, $mail, $mdp, $date, $genre, $ville);
?>