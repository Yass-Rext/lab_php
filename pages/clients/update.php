<?php
include '../../includes/config.php';

// Récupérer l'ID du client depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les données actuelles du client
$stmt = $pdo->prepare("SELECT * FROM client WHERE id_client = ?");
$stmt->execute([$id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

// Si le client n'existe pas, rediriger
if (!$client) {
    header("Location: read.php");
    exit();
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $telephone = htmlspecialchars($_POST['telephone']);

    if ($email) {
        $sql = "UPDATE client SET nom = ?, email = ?, telephone = ? WHERE id_client = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $email, $telephone, $id_client]);
        
        header("Location: read.php?success=1");
        exit();
    } else {
        $error = "Email invalide !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Modifier le Client #<?= $client['id_client'] ?></h2>
        <a href="read.php" class="btn btn-secondary mb-3">Retour à la liste</a>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="text" class="form-control" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date de Naissance</label>
                <input type="date" class="form-control" name="date_naiss" value="<?= htmlspecialchars($client['date_naiss']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Sexe</label>
                <select class="form-select" name="sexe" required>
                    <option value="">Sélectionner</option>
                    <option value="masculin" <?= $client['sexe'] == 'masculin' ? 'selected' : '' ?>>Masculin</option>
                    <option value="feminin" <?= $client['sexe'] == 'feminin' ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="read.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>