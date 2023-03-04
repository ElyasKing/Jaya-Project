<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=jaya;charset=utf8';
$username = 'root';
$password = '';
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Vérification de la soumission du formulaire de mise à jour
if (isset($_POST['update'])) {
    // Récupération des données du formulaire
    $id = $_POST['id'];
    $poster = $_POST['poster'];
    $rapport = $_POST['rapport'];
    $soutenance = $_POST['soutenance'];

    // Mise à jour des données de l'étudiant correspondant
    $query = "UPDATE etudiants SET poster = :poster, rapport = :rapport, soutenance = :soutenance WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->bindValue(':poster', $poster);
    $statement->bindValue(':rapport', $rapport);
    $statement->bindValue(':soutenance', $soutenance);
    $result = $statement->execute();

    // Redirection vers la page actuelle pour mettre à jour l'affichage
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

// Récupération des données de la table "etudiants"
$query = "SELECT id, etudiant, poster, appreciation, rapport, soutenance, note_finale FROM etudiants";
$statement = $db->query($query);
if ($statement) {
    $etudiants = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    die('Erreur lors de l\'exécution de la requête : ' . $db->errorInfo()[2]);
}

// Fermeture de la connexion à la base de données
$db = null;
?>

<table class="table">
  <thead>
    <tr>
      <th>Etudiant</th>
      <th>Poster</th>
      <th>Appréciation</th>
      <th>Rapport</th>
      <th>Soutenance</th>
      <th>Note finale</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($etudiants as $etudiant) { ?>
      <tr>
        <td><?php echo $etudiant['etudiant']; ?></td>
        <td><?php echo $etudiant['poster']; ?></td>
        <td><?php echo $etudiant['appreciation']; ?></td>
        <td><?php echo $etudiant['rapport']; ?></td>
        <td><?php echo $etudiant['soutenance']; ?></td>
        <td><?php echo $etudiant['note_finale']; ?></td>
        <td>
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $etudiant['id']; ?>">
            <label>Poster : <input type="number" name="poster" value="<?php echo $etudiant['poster']; ?>"></label>
            <label>Rapport : <input type="number" name="rapport" value="<?php echo $etudiant['rapport']; ?>"></label>
            <label>Soutenance : <input type="number" name="soutenance" value="<?php echo $etudiant['soutenance']; ?>"></label>
            <button type="submit" name="update">Mettre à jour</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>



