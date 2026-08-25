<?php
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    // Note: 'prenom' is not used in the SQL insert, but you can store it if needed.
    // If you want to store 'prenom', you should add it to the SQL statement.
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];

    $sql = "INSERT INTO client (nom, prenom, email, telephone, datenaiss, sexe) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $telephone, $date_naissance, $sexe]);

    header("Location: read.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Client</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone">
            </div>
            <div class="mb-3">
                <label class="form-label">Date de Naissance</label>
                <input type="date" class="form-control" name="date_naissance">
            </div>
            <div class="mb-3">
                <label class="form-label">Sexe</label>
                <select class="form-select" name="sexe" required>
                    <option value="">Sélectionner</option>
                    <option value="masculin">Masculin</option>
                    <option value="feminin">Féminin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</body>
</html>