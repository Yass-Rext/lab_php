<?php
include '../../includes/config.php';
// include '_functions.php'; // À décommenter si vous créez le fichier

$arrondissements = $pdo->query("
    SELECT 
        a.*, 
        COUNT(l.id_logement) as total_logements
    FROM arrondissement a
    LEFT JOIN logement l ON a.id_arrondissement = l.id_arrondissement
    GROUP BY a.id_arrondissement
    -- GROUP BY a.id
    ORDER BY a.num_arrondissement
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Arrondissements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .badge-lg { font-size: 0.9rem; padding: 0.35em 0.65em; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="bi bi-map-fill text-primary"></i> Arrondissements
            </h1>
            <a href="create.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouveau
            </a>
             <!-- Bouton Accueil -->
        <a href="../../index.php" class="btn btn-info">
            <i class="bi bi-house-fill"></i> Retour à l'accueil
        </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Opération réussie !
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Num</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($arrondissements as $a): ?>
                            <tr>
                                <td><?= $a['id_arrondissement'] ?></td>
                                <td><?= htmlspecialchars($a['num_arrondissement']) ?></td>

                               
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="update.php?id=<?= $a['id_arrondissement'] ?>" class="btn btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $a['id_arrondissement'] ?>" 
                                           class="btn btn-outline-danger <?= $a['total_logements'] ? 'disabled' : '' ?>"
                                           title="<?= $a['total_logements'] ? 'Impossible (logements associés)' : 'Supprimer' ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>