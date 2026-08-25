<?php include '../../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Liste des Clients</h2>
        <a href="create.php" class="btn btn-primary mb-3">Ajouter un Client</a>
         <!-- Bouton Accueil -->
        <a href="../../index.php" class="btn btn-info">
            <i class="bi bi-house-fill"></i> Retour à l'accueil
        </a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Date de Naissance</th>
                    <th>Sexe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM client");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['id_client']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['telephone']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['datenaiss']}</td>
                            <td>{$row['sexe']}</td>
                            <td>
                                <a href='update.php?id={$row['id_client']}' class='btn btn-sm btn-warning'>Modifier</a>
                                <a href='delete.php?id={$row['id_client']}' class='btn btn-sm btn-danger'>Supprimer</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>