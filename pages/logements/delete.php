<?php
include '../../includes/config.php';

$id = intval($_GET['id'] ?? 0);

// Requête préparée pour sécurité
$stmt = $pdo->prepare("SELECT * FROM logement WHERE id_logement = ?");
$stmt->execute([$id]);
$logement = $stmt->fetch();

if (!$logement) {
    header("Location: read.php?error=not_found");
    exit();
}

// Vérification des achats associés
$stmt = $pdo->prepare("SELECT COUNT(*) FROM achat WHERE id_logement = ?");
$stmt->execute([$id]);
$isUsed = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm'])) {
        try {
            $pdo->beginTransaction();
            
            if ($isUsed) {
                $pdo->prepare("DELETE FROM achat WHERE id_logement = ?")->execute([$id]);
            }
            
            $pdo->prepare("DELETE FROM logement WHERE id_logement = ?")->execute([$id]);
            $pdo->commit();
            
            header("Location: read.php?success=1&action=delete");
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            header("Location: read.php?error=delete_failed&message=" . urlencode($e->getMessage()));
            exit();
        }
    }
    header("Location: read.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un Logement</title>
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
                        <?php if ($isUsed): ?>
                        <div class="alert alert-warning">
                            <h3 class="h6">Attention !</h3>
                            <p>
                                Ce logement est lié à <?= $isUsed ?> achat(s).<br>
                                <strong>La suppression effacera également ces achats.</strong>
                            </p>
                        </div>
                        <?php endif; ?>

                        <div class="mb-4 p-3 border rounded">
                            <h4 class="h6 text-muted">Détails du logement</h4>
                            <ul class="list-unstyled">
                                <li><strong>ID:</strong> <?= $logement['id_logement'] ?></li>
                                <li><strong>Pièces:</strong> <?= $logement['pieces'] ?></li>
                                <li><strong>Superficie:</strong> <?= $logement['superficie'] ?> m²</li>
                                <li><strong>Budget:</strong> <?= number_format($logement['budget'], 2, ',', ' ') ?> €</li>
                            </ul>
                        </div>

                        <form method="POST">
                            <div class="d-flex justify-content-between">
                                <a href="read.php" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </a>
                                <button type="submit" name="confirm" value="1" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Confirmer
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