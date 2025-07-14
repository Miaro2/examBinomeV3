<?php
session_start();
require('../inc/function.php');
require('../inc/connection.php');

$nom = $_SESSION['nom'] ?? 'Visiteur';

$idObjet = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$idObjet) {
  header('Location: liste.php');
  exit;
}

$objet = getObjetById($idObjet);
if (!$objet) {
  echo "Objet introuvable.";
  exit;
}

$images = getImagesObjet($idObjet);
$historique = getHistoriqueEmprunts($idObjet);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fiche de l’objet</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-light">

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="liste.php">Emprunte Moi</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <span class="nav-link text-light">Bienvenue <?= htmlspecialchars($nom) ?></span>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<main class="container mb-5">
  <h1 class="mb-4 text-center"><?= htmlspecialchars($objet['nom_objet']) ?></h1>

  <!-- Images de l'objet -->
  <div class="row mb-4">
    <?php if (count($images) > 0): ?>
      <?php foreach ($images as $index => $img): ?>
        <div class="col-md-4 mb-3">
          <img src="../assets/image/<?= htmlspecialchars($img['nom_image']) ?>" class="img-fluid rounded border <?= $index === 0 ? 'shadow-lg' : '' ?>" alt="Image de l'objet">
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Aucune image disponible.</p>
    <?php endif; ?>
  </div>

  <!-- Historique des emprunts -->
  <section>
    <h2 class="h4 mb-3">Historique des emprunts</h2>
    <?php if (count($historique) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead class="table-dark">
            <tr>
              <th>Nom du membre</th>
              <th>Date emprunt</th>
              <th>Date retour</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historique as $ligne): ?>
              <tr>
                <td><?= htmlspecialchars($ligne['nom_membre']) ?></td>
                <td><?= $ligne['date_emprunt'] ?></td>
                <td><?= $ligne['date_retour'] ?: 'Non retourné' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>Aucun historique d'emprunt pour cet objet.</p>
    <?php endif; ?>
  </section>
</main>

<footer class="text-center py-4 bg-dark text-light">
  &copy; <?= date('Y') ?> Emprunte Moi
</footer>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
