<?php
include '../../includes/config.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM logement WHERE id_logement = ?");
$stmt->execute([$id]);
$logement = $stmt->fetch();

if (!$logement) {
    header("Location: read.php?error=not_found");
    exit();
}

$arrondissements = $pdo->query("SELECT id_arrondissement, num_arrondissement FROM arrondissement ORDER BY num_arrondissement")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id_logement' => $id,
        'pieces' => intval($_POST['pieces']),
        'superficie' => floatval(str_replace(',', '.', $_POST['superficie'])),
        'budget' => floatval(str_replace(',', '.', $_POST['budget'])),
        'id_arrondissement' => !empty($_POST['arrondissement_id']) ? intval($_POST['arrondissement_id']) : null
    ];

    try {
        $sql = "UPDATE logement SET 
                pieces = :pieces,
                superficie = :superficie,
                budget = :budget,
                id_arrondissement = :id_arrondissement
                WHERE id_logement = :id_logement";
        
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
    <title>Modifier Logement</title>
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
                            <i class="bi bi-pencil-square"></i> Modifier Logement #<?= $id ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Pièces</label>
                                <input type="number" class="form-control" name="pieces" 
                                       value="<?= htmlspecialchars($logement['pieces']) ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Superficie (m²)</label>
                                <input type="text" class="form-control" name="superficie"
                                       value="<?= htmlspecialchars($logement['superficie']) ?>"
                                       pattern="[0-9]+([,\.][0-9]+)?" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Budget (€)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="budget"
                                           value="<?= htmlspecialchars($logement['budget']) ?>"
                                           pattern="[0-9]+([,\.][0-9]+)?" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Arrondissement</label>
                                <select class="form-select" name="arrondissement_id">
                                    <option value="">-- Sans arrondissement --</option>
                                    <?php foreach ($arrondissements as $a): 
                                        $selected = ($a['id_arrondissement'] == $logement['id_arrondissement']) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $a['id_arrondissement'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($a['num_arrondissement']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
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