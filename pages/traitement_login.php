<?php
session_start();
require('../inc/connection.php');
require('../inc/function.php');
$mail = $_GET['mail'] ?? '';
$mdp = $_GET['mdp'] ?? '';

connection($mail, $mdp);
?>