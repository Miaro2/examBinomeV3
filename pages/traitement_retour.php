<?php
session_start();
require('../inc/function.php');
require('../inc/connection.php');

$idEmprunt = $_GET['id_emprunt'] ?? null;

if (!$idEmprunt) {
    // Redirection si pas d'id_emprunt
    header('Location: accueil.php');
    exit;
}

// Traitement du formulaire après soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etatRetour = $_POST['etat_retour'] ?? null;

    if (!in_array($etatRetour, ['ok', 'abime'])) {
        $error = "Veuillez choisir un état valide.";
    } else {
        // Ici tu peux enregistrer dans la base l'état du retour,
        // par exemple via une fonction saveRetour($idEmprunt, $etatRetour)
        // ou tu peux rediriger vers la liste des retours en passant les paramètres.

        // Exemple simple : on redirige vers la page liste_retour.php avec paramètres GET (ou POST via session)
        $_SESSION['etat_retour'] = $etatRetour;
        $_SESSION['id_emprunt_retour'] = $idEmprunt;

        header('Location: liste_retour.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Retour Objet Emprunté</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

  <div class="container py-5">
    <h2 class="mb-4 text-center">Retour de l'objet emprunté</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="w-50 mx-auto">
      <div class="mb-3">
        <label class="form-label">État du retour :</label>
        <div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="etat_retour" id="etat_ok" value="ok" required>
            <label class="form-check-label" for="etat_ok">OK</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="etat_retour" id="etat_abime" value="abime" required>
            <label class="form-check-label" for="etat_abime">Abîmé</label>
          </div>
        </div>
      </div>

      <input type="hidden" name="id_emprunt" value="<?= htmlspecialchars($idEmprunt) ?>">

      <div class="d-flex justify-content-between">
        <a href="accueil.php" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">Valider</button>
      </div>
    </form>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
