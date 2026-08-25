<?php 
include '../../includes/config.php';

// Récupération avec jointure pour afficher le numéro d'arrondissement
$sql = "SELECT l.*, a.num_arrondissement as arrondissement_num 
        FROM logement l
        LEFT JOIN arrondissement a ON l.id_arrondissement = a.id_arrondissement
        ORDER BY l.id_logement DESC";
$logements = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Logements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive { overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Liste des Logements</h2>
        <a href="create.php" class="btn btn-success mb-3">
            <i class="bi bi-plus-circle"></i> Nouveau Logement
        </a>
         <!-- Bouton Accueil -->
        <a href="../../index.php" class="btn btn-info">
            <i class="bi bi-house-fill"></i> Retour à l'accueil
        </a>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pieces</th>
                        <th>Superficie</th>
                        <th>Budget</th>
                        <th>Arrondissement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logements as $l): ?>
                    <tr>
                        <td><?= $l['id_logement'] ?></td>
                        <td><?= htmlspecialchars($l['pieces']) ?></td>
                        <td><?= htmlspecialchars($l['superficie']) ?></td>
                        <td><?= htmlspecialchars($l['budget']) ?></td>
                        <td>
                            <?= $l['arrondissement_num'] ? htmlspecialchars($l['arrondissement_num']) : 'Non spécifié' ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="update.php?id=<?= $l['id_logement'] ?>" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $l['id_logement'] ?>" class="btn btn-sm btn-outline-danger">
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>