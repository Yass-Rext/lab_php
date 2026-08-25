<?php
include '../../includes/config.php';

// Validation et sécurisation de l'ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$id) {
    header("Location: read.php?error=invalid_id");
    exit();
}

// Requête préparée pour éviter les injections SQL
$stmt = $pdo->prepare("SELECT * FROM arrondissement WHERE id_arrondissement = ?");
$stmt->execute([$id]);
$arrondissement = $stmt->fetch();

if (!$arrondissement) {
    header("Location: read.php?error=not_found");
    exit();
}

// Vérification des logements associés avec requête préparée
$stmt = $pdo->prepare("SELECT COUNT(*) FROM logement WHERE id_arrondissement = ?");
$stmt->execute([$id]);
$hasLogements = $stmt->fetchColumn();

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$hasLogements) {
        try {
            $pdo->beginTransaction();
            
            // Suppression avec requête préparée
            $stmt = $pdo->prepare("DELETE FROM arrondissement WHERE id_arrondissement = ?");
            $stmt->execute([$id]);
            
            $pdo->commit();
            header("Location: read.php?success=1&action=delete");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Erreur lors de la suppression : " . $e->getMessage();
        }
    } else {
        $error = "Impossible de supprimer : logements associés existants";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un Arrondissement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card {
            border: none;
            border-radius: 10px;
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-danger text-white">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i> Confirmation de suppression
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($hasLogements): ?>
                            <div class="alert alert-warning">
                                <h3 class="h6"><i class="bi bi-exclamation-octagon-fill"></i> Action bloquée</h3>
                                <p class="mb-0">
                                    Cet arrondissement est lié à <?= $hasLogements ?> logement(s).<br>
                                    Supprimez d'abord les logements associés.
                                </p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="read.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center mb-4">
                                <div class="fs-1 text-danger mb-3">
                                    <i class="bi bi-trash-fill"></i>
                                </div>
                                <h3 class="h4"><?= htmlspecialchars($arrondissement['num_arrondissement']) ?></h3>
                                <p class="text-muted">ID: <?= $id ?></p>
                                <p>Êtes-vous sûr de vouloir supprimer définitivement cet arrondissement ?</p>
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>