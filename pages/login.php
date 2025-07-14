<?php
session_start();
require('../inc/connection.php');
require('../inc/function.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Emprunte Moi - Login</title>
  <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-secondary bg-opacity-10">

  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Emprunte Moi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="inscription.php">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <section class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
      <article class="card shadow p-4 w-100" style="max-width: 400px;">
        <h1 class="mb-4 text-center text-dark">Connexion</h1>
        <form action="traitement_login.php" method="get" novalidate>
          <div class="mb-3">
            <label for="mail" class="form-label">Email</label>
            <input type="email" class="form-control" id="mail" name="mail" required>
          </div>
          <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
          </div>
          <button type="submit" class="btn btn-dark w-100">Se connecter</button>
        </form>
        <p class="mt-3 text-center">
          Pas encore de compte ? 
          <a href="inscription.php" class="link-dark">Inscrivez-vous</a>
        </p>
      </article>
    </section>
  </main>

  <footer class="text-center py-3">
    <small>&copy; <?= date('Y') ?> Emprunte Moi</small>
  </footer>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
