<?php
session_start();
require('../inc/function.php');
require('../inc/connection.php');

$nom = $_SESSION['nom'] ?? 'Visiteur';

$idCategorie = isset($_GET['categorie']) ? intval($_GET['categorie']) : null;
$getObjet = getObjet($idCategorie);

$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Emprunte Moi - Liste des objets</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .modal-header {
      border-bottom: none;
    }
    .modal-footer {
      border-top: none;
    }
  </style>
</head>
<body class="bg-secondary bg-opacity-10">

  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Emprunte Moi</a>
        <div class="collapse navbar-collapse justify-content-end">
          <ul class="navbar-nav">
            <li class="nav-item">
              <span class="nav-link text-light"><a href="membre.php">Bienvenue <?= $nom ?></a></span>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main class="container mb-5">

    <!-- Bouton pour ouvrir le modal -->
    <div class="text-end mb-4">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajoutObjetModal">
        + Ajouter un objet
      </button>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="ajoutObjetModal" tabindex="-1" aria-labelledby="ajoutObjetModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="ajoutObjetModalLabel">Publier une photo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>

          <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="mb-3">
                <label for="fichier" class="form-label">Choisir une image</label>
                <input type="file" class="form-control" id="fichier" name="fichier" accept="image/jpeg,image/png" />
              </div>

              <div class="mb-3">
                <label for="categorie_nouvelle_objet" class="form-label">Catégorie</label>
                <select name="categorie_nouvelle_objet" id="categorie_nouvelle_objet" class="form-select" required>
                  <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($idCategorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Uploader</button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <h1 class="mb-4 text-center">Liste des objets</h1>

    <section>
      <form method="get" class="row mb-4">
        <div class="col-md-6">
          <select name="categorie" class="form-select">
            <option value="">-- Toutes les catégories --</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id_categorie'] ?>" <?= ($idCategorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                <?= $cat['nom_categorie'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100">Filtrer</button>
        </div>
      </form>
    </section>

    <section class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Nom Objet</th>
            <th>Nom Membre</th>
            <th>Date Emprunt</th>
            <th>Date Retour</th>
            <th>Emprunt</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($getObjet) > 0): ?>
            <?php foreach ($getObjet as $objet): ?>
              <?php
                $estDisponible = !isset($objet['nom_membre']);
                if ($estDisponible) {
                  $objet['nom_membre'] = '-----';
                  $objet['date_emprunt'] = '-----';
                  $objet['date_retour'] = '-----';
                }
              ?>
              <tr>
                <td><a href="fiche.php?id=<?= $objet['id_objet'] ?>"><?= htmlspecialchars($objet['nom_objet']) ?></a></td>
                <td><?= htmlspecialchars($objet['nom_membre']) ?></td>
                <td><?= htmlspecialchars($objet['date_emprunt']) ?></td>
                <td><?= htmlspecialchars($objet['date_retour']) ?></td>

                <?php if ($estDisponible): ?>
                  <td>
                    <form action="traitement_emprunt.php" method="post" class="d-flex">
                      <input type="hidden" name="id_objet" value="<?= $objet['id_objet'] ?>">
                      <input type="number" name="nb_jours" class="form-control form-control-sm me-2" placeholder="Jours" min="1" max="30" required style="width: 80px;">
                      <button type="submit" class="btn btn-sm btn-success">Emprunter</button>
                    </form>
                  </td>
                <?php else: ?>
                  <td>-</td>
                <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center">Aucun objet trouvé.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

  <footer class="text-center py-4 bg-dark text-light">
    &copy; <?= date('Y') ?> Emprunte Moi
  </footer>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
