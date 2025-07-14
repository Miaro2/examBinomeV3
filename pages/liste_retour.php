<?php
session_start();
require('../inc/connection.php'); // doit définir $dataBase
require('../inc/function.php');

$idMembreConnecte = $_SESSION['IdMembre'] ?? null;

if (!$idMembreConnecte) {
    exit('Erreur : membre non connecté.');
}

$emprunts = getEmpruntsAvecEtat($idMembreConnecte);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des Retours</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<main class="container mt-5">
  <h1 class="mb-4 text-center">Objets empruntés et leur état</h1>

  <?php if (count($emprunts) === 0): ?>
    <p class="text-center">Vous n'avez aucun objet emprunté.</p>
  <?php else: ?>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Nom Objet</th>
          <th>Date Emprunt</th>
          <th>Date Retour</th>
          <th>État au retour</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($emprunts as $emprunt): ?>
          <tr>
            <td><?= htmlspecialchars($emprunt['nom_objet']) ?></td>
            <td><?= htmlspecialchars($emprunt['date_emprunt']) ?></td>
            <td><?= htmlspecialchars($emprunt['date_retour']) ?></td>
            <td>
              <?php
                if ($emprunt['etat_retour'] === 'ok') {
                  echo '<span class="text-success">OK</span>';
                } elseif ($emprunt['etat_retour'] === 'abime') {
                  echo '<span class="text-warning">Abîmé</span>';
                } else {
                  echo '<span class="text-muted">En cours</span>';
                }
              ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
