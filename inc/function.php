<?php

function connection($mail, $mdp){

    $requete = "SELECT * FROM gestion_emprunt_membre WHERE email = '%s' AND mdp = '%s'";
    $requete = sprintf($requete, $mail, $mdp);

    $resultat = mysqli_query(getdataBase(), $requete);
    $donne = mysqli_fetch_assoc($resultat);

    if ($donne) {
        $_SESSION['id'] = $donne['id_membre'];
        $_SESSION['nom'] = $donne['nom'];
        $_SESSION['mail'] = $donne['email'];
        $_SESSION['ddn'] = $donne['date_naissance'];
        $_SESSION['IdMembre'] = $donne['id_membre'];
        $_SESSION['genre'] = $donne['genre'];
        $_SESSION['ville'] = $donne['ville'];
        $_SESSION['image'] = $donne['image_profil'] ?? 'default.png';
        header("Location: ../pages/accueil.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur de connexion";
        header("Location: ../pages/login.php");
        exit;
    }
}

function create_compte($nom, $mail, $mdp, $date, $genre, $ville) {
    $checkEmailQuery = "SELECT * FROM gestion_emprunt_membre WHERE email = '%s'";
    $checkEmailQuery = sprintf($checkEmailQuery, $mail);
    $checkEmailResult = mysqli_query(getdataBase(), $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $_SESSION['deja'] = "Email déjà utilisé";
        header("Location: ../pages/inscription.php");
        exit;
    }

    $insert = "INSERT INTO gestion_emprunt_membre (nom, email, mdp, date_naissance, genre ,ville) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')";
    $insert = sprintf($insert, $nom, $mail, $mdp, $date, $genre, $ville);

    if (mysqli_query(getdataBase(), $insert)) {
        header("Location: ../pages/login.php");
        exit;
    }

    header("Location: ../pages/inscription.php");
}

function getObjet($idCategorie = null) {
    $requete = "SELECT * FROM v_objets_emprunt";
    if ($idCategorie) {
        $requete .= " WHERE id_categorie = " . intval($idCategorie);
    }
    $resultat = mysqli_query(getdataBase(), $requete);
    $objets = [];
    while ($donne = mysqli_fetch_assoc($resultat)) {
        $objets[] = $donne;
    }
    return $objets;
}

function getCategories() {
    $requete = "SELECT * FROM gestion_emprunt_categorie_objet";
    $resultat = mysqli_query(getdataBase(), $requete);
    $categories = [];
    while ($donne = mysqli_fetch_assoc($resultat)) {
        $categories[] = $donne;
    }
    return $categories;
}

function createObjet($nomObjet, $idCategorie, $idMembre) {
    $conn = getdatabase();

    $requeteObjet = "INSERT INTO gestion_emprunt_objet (nom_objet, id_categorie, id_membre) VALUES ('%s', %d, %d)";
    $requeteObjet = sprintf($requeteObjet, mysqli_real_escape_string($conn, $nomObjet), intval($idCategorie), intval($idMembre));
    
    if (!mysqli_query($conn, $requeteObjet)) {
        return false;
    }

    $idObjet = mysqli_insert_id($conn);

    return $idObjet;
}

function createImageObjet($id_objet, $nom_image) {
    $db = getdatabase();
    $nom_image = mysqli_real_escape_string($db, $nom_image);

    $requete = "INSERT INTO gestion_emprunt_images_objet (id_objet, nom_image) VALUES (%s, '%s')";
    $requete = sprintf($requete, $id_objet, $nom_image);

    $result = mysqli_query($db, $requete);
    return $result;
}


function getObjetById($id) {
  $id = intval($id);
  $sql = "SELECT * FROM vue_objet_details WHERE id_objet = $id";
  $result = mysqli_query(getdataBase(), $sql);
  return mysqli_fetch_assoc($result);
}

function getImagesObjet($idObjet) {
  $idObjet = intval($idObjet);
  $sql = "SELECT nom_image FROM vue_images_objet WHERE id_objet = $idObjet";
  $result = mysqli_query(getdataBase(), $sql);
  
  $images = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $images[] = $row;
  }
  return $images;
}

function getHistoriqueEmprunts($idObjet) {
  $idObjet = intval($idObjet);
  $sql = "SELECT nom_membre, date_emprunt, date_retour 
          FROM vue_historique_emprunts 
          WHERE id_objet = $idObjet 
          ORDER BY date_emprunt DESC";
  $result = mysqli_query(getdataBase(), $sql);
  
  $historique = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $historique[] = $row;
  }
  return $historique;
}

function getMembreById($idMembre) {
    $conn = getdatabase();
    $id = (int)$idMembre;

    $sql = "SELECT * FROM gestion_emprunt_membre WHERE id_membre = $id LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        return mysqli_fetch_assoc($res);
    }
    return null;
}

function emprunter($id_objets, $nb_jours) {
    $requete = "INSERT INTO gestion_emprunt_emprunt (id_objet, id_membre, date_emprunt, date_retour) 
                VALUES (%d, %d, NOW(), DATE_ADD(NOW(), INTERVAL %d DAY))";
    $requete = sprintf($requete, intval($id_objets), intval($_SESSION['id']), intval($nb_jours));
    $resultat = mysqli_query(getdataBase(), $requete);
    
    header("Location: ../pages/accueil.php");
    // return $resultat;
}
function getEmpruntsAvecEtat($idMembre) {

    $sql = "SELECT o.nom_objet, e.date_emprunt, e.date_retour, e.etat_retour
            FROM emprunts e
            JOIN objets o ON e.id_objet = o.id_objet
            WHERE e.id_membre_emprunt = $idMembre";

    $result = mysqli_query(getdataBase(), $sql);
    if (!$result) {
        exit('Erreur SQL : ' . mysqli_error(getdataBase()));
    }

    $emprunts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emprunts[] = $row;
    }

    return $emprunts;
}



?>