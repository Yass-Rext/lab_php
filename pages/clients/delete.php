<?php
include '../../includes/config.php';

// Vérifier si l'ID existe et est valide
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Vérifier d'abord si le client existe
    $stmt = $pdo->prepare("SELECT id_client FROM client WHERE id_client = ?");
    $stmt->execute([$id]);
    $clientExists = $stmt->fetch();

    if ($clientExists) {
        // Vérifier si le formulaire de confirmation a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['confirm']) && $_POST['confirm'] === 'oui') {
                // Supprimer le client
                $deleteStmt = $pdo->prepare("DELETE FROM client WHERE id_client = ?");
                $deleteStmt->execute([$id]);
                
                // Redirection avec message de succès
                header("Location: read.php?success=1&action=delete");
                exit();
            } else {
                // Annulation - redirection vers la liste
                header("Location: read.php");
                exit();
            }
        }
    } else {
        // Le client n'existe pas - redirection
        header("Location: read.php?error=not_found");
        exit();
    }
} else {
    // ID invalide - redirection
    header("Location: read.php?error=invalid_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .confirmation-box {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-box bg-light">
            <h2 class="text-center mb-4">Confirmer la suppression</h2>
            <p class="text-center">Êtes-vous sûr de vouloir supprimer le client #<?= $id ?> ?</p>
            
            <form method="POST" class="text-center">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="d-flex justify-content-center gap-3">
                    <button type="submit" name="confirm" value="oui" class="btn btn-danger">
                        Oui, supprimer
                    </button>
                    <a href="read.php" class="btn btn-secondary">
                        Non, annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>