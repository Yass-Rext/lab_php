<?php
include '../../includes/config.php';

$id = intval($_GET['id'] ?? 0);

// Vérification de l'existence de l'achat
$stmt = $pdo->prepare("SELECT * FROM achat WHERE id_achat = ?");
$stmt->execute([$id]);
$achat = $stmt->fetch();

if (!$achat) {
    header("Location: read.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Suppression avec requête préparée
        $stmt = $pdo->prepare("DELETE FROM achat WHERE id_achat = ?");
        $stmt->execute([$id]);
        
        $pdo->commit();
        header("Location: read.php?success=1&action=delete");
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer Achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Confirmation
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <div class="alert alert-warning">
                            <h3 class="h6">Vous allez supprimer :</h3>
                            <ul class="mb-0">
                                <li>Achat #<?= $achat['id_achat'] ?></li>
                                <li>Client ID: <?= $achat['id_client'] ?></li>
                                <li>Logement ID: <?= $achat['id_logement'] ?></li>
                                <li>Date: <?= date('d/m/Y', strtotime($achat['date_achat'])) ?></li>
                            </ul>
                        </div>

                        <form method="POST">
                            <div class="d-flex justify-content-between">
                                <a href="read.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash-fill"></i> Confirmer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>