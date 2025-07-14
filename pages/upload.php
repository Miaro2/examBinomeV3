<?php
session_start();
require("../inc/connection.php");
require("../inc/function.php");

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    die("Erreur : utilisateur non connecté !");
}

$id_user = $_SESSION['id'];
$id_categorie = isset($_POST['categorie_nouvelle_objet']) ? $_POST['categorie_nouvelle_objet'] : 0;

$uploadDir = __DIR__ . '/../assets/uploads/';
$maxSize = 20 * 1024 * 1024;
$allowedMimeTypes = ['image/jpeg', 'image/png'];

// Crée le dossier upload s'il n'existe pas
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

// Si aucun fichier, mettre une image par défaut
if (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] === UPLOAD_ERR_NO_FILE) {
    $originalName = "objet_defaut";
    $newName = "default.jpeg";

    // Crée l'objet en BDD
    $id_objet = createObjet($originalName, $id_categorie, $id_user);
    if ($id_objet) {
        createImageObjet($id_objet, $newName);
        // ✅ Redirige vers accueil.php
        header("Location: accueil.php");
        exit;
    } else {
        echo "Erreur lors de la création de l'objet.";
    }
    exit;
}

// Vérifie méthode POST + fichier présent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    $file = $_FILES['fichier'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die('Erreur lors de l’upload : ' . $file['error']);
    }

    if ($file['size'] > $maxSize) {
        die('Le fichier est trop volumineux.');
    }

    // Vérifie le type mime
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimeTypes)) {
        die('Type de fichier non autorisé : ' . $mime);
    }

    // Nettoyage du nom et renommage
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = $originalName . '_' . uniqid() . '.' . $extension;

    // Déplacement du fichier
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
        // Crée l'objet
        $id_objet = createObjet($originalName, $id_categorie, $id_user);

        if ($id_objet) {
            createImageObjet($id_objet, $newName);
            // ✅ Redirige vers accueil.php après succès
            header("Location: accueil.php");
            exit;
        } else {
            echo "Erreur lors de la création de l'objet.";
        }
    } else {
        echo "Échec du déplacement du fichier.";
    }
} else {
    echo "Aucun fichier reçu.";
}
?>
