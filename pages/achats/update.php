<?php
include '../../includes/config.php';

$id = intval($_GET['id'] ?? 0);

// Requête préparée pour récupérer l'achat
$stmt = $pdo->prepare("SELECT * FROM achat WHERE id_achat = ?");
$stmt->execute([$id]);
$achat = $stmt->fetch();

if (!$achat) {
    header("Location: read.php?error=not_found");
    exit();
}

// Récupération des clients et logements pour les menus déroulants
$clients = $pdo->query("SELECT id_client, nom FROM client")->fetchAll();
$logements = $pdo->query("SELECT id_logement, pieces FROM logement")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_achat' => $id,
        'id_client' => intval($_POST['client_id']),
        'id_logement' => intval($_POST['logement_id']),
        'date_achat' => $_POST['date_achat']
    ];

    try {
        $sql = "UPDATE achat SET 
                id_client = :id_client,
                id_logement = :id_logement,
                date_achat = :date_achat
                WHERE id_achat = :id_achat";
        
        $pdo->prepare($sql)->execute($data);
        header("Location: read.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0">
                            <i class="bi bi-pencil-square"></i> Modifier Achat #<?= $id ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Client</label>
                                <select name="client_id" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id_client'] ?>" <?= $c['id_client'] == $achat['id_client'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nom']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Logement</label>
                                <select name="logement_id" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?php foreach ($logements as $l): ?>
                                    <option value="<?= $l['id_logement'] ?>" <?= $l['id_logement'] == $achat['id_logement'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($l['pieces']) ?> pièces
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date d'achat</label>
                                <input type="date" name="date_achat" class="form-control" 
                                       value="<?= htmlspecialchars($achat['date_achat']) ?>" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="read.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Enregistrer
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