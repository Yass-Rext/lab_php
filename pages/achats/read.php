<?php 
include '../../includes/config.php';

// Requête optimisée avec jointures pour récupérer les informations nécessaires
$sql = "SELECT 
            a.id_achat,
            a.id_client,
            c.nom AS client_nom,
            a.id_logement,
            l.pieces AS logement_pieces,
            a.date_achat
        FROM achat a
        LEFT JOIN client c ON a.id_client = c.id_client
        LEFT JOIN logement l ON a.id_logement = l.id_logement
        ORDER BY a.date_achat DESC, a.id_achat DESC";

$achats = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Achats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive { overflow-x: auto; }
        .badge-client { background-color: #6c757d; }
        .badge-logement { background-color: #0d6efd; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Liste des Achats</h2>
            <a href="create.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouvel Achat
            </a>
            <!-- Bouton Accueil -->
        <a href="../../index.php" class="btn btn-info">
            <i class="bi bi-house-fill"></i> Retour à l'accueil
        </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID Achat</th>
                        <th>Client</th>
                        <th>Logement</th>
                        <th>Date Achat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achats as $a): ?>
                    <tr>
                        <td><?= $a['id_achat'] ?></td>
                        <td>
                            <span class="badge badge-client me-1">#<?= $a['id_client'] ?></span>
                            <?= htmlspecialchars($a['client_nom'] ?? 'Client inconnu') ?>
                        </td>
                        <td>
                            <span class="badge badge-logement me-1">#<?= $a['id_logement'] ?></span>
                            <?= $a['logement_pieces'] ? htmlspecialchars($a['logement_pieces']).' pièces' : 'Logement inconnu' ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($a['date_achat'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="update.php?id=<?= $a['id_achat'] ?>" class="btn btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $a['id_achat'] ?>" class="btn btn-outline-danger" title="Supprimer">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>