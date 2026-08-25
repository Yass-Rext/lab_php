<?php
include '../../includes/config.php';

// Récupère tous les arrondissements pour le dropdown
$arrondissements = $pdo->query("SELECT id_arrondissement, num_arrondissement FROM arrondissement ORDER BY num_arrondissement")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation et nettoyage des données
    $data = [
        'pieces' => intval($_POST['pieces']),
        'superficie' => floatval(str_replace(',', '.', $_POST['superficie'])),
        'budget' => floatval(str_replace(',', '.', $_POST['budget'])),
        'id_arrondissement' => !empty($_POST['arrondissement_id']) ? intval($_POST['arrondissement_id']) : null
        
    ];

    try {
        $sql = "INSERT INTO logement 
                (pieces, superficie, budget, id_arrondissement) 
                VALUES 
                (:pieces, :superficie, :budget, :id_arrondissement)";
        
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            header("Location: read.php?success=1");
            exit();
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau Logement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .form-label.required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-house-add"></i> Nouveau Logement
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row g-3">
                                <!-- Adresse -->
                                
                                <!-- Arrondissement -->
                                <div class="col-md-6">
                                    <label for="arrondissement" class="form-label">Arrondissement</label>
                                    <select class="form-select" id="arrondissement" name="arrondissement_id">
                                        <option value="">-- Sans arrondissement --</option>
                                        <?php foreach ($arrondissements as $a): ?>
                                        <option value="<?= $a['id_arrondissement'] ?>">
                                            <?= htmlspecialchars($a['num_arrondissement']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Pièces -->
                                <div class="col-md-6">
                                    <label for="pieces" class="form-label required">Nombre de pièces</label>
                                    <input type="number" class="form-control" id="pieces" name="pieces" 
                                           min="1" max="20" required>
                                </div>

                                <!-- Superficie -->
                                <div class="col-md-6">
                                    <label for="superficie" class="form-label required">Superficie (m²)</label>
                                    <input type="text" class="form-control" id="superficie" name="superficie"
                                           pattern="[0-9]+([,\.][0-9]+)?" required
                                           placeholder="Ex: 85,50">
                                </div>

                                <!-- Budget -->
                                <div class="col-md-6">
                                    <label for="budget" class="form-label required">Budget (€)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="budget" name="budget"
                                               pattern="[0-9]+([,\.][0-9]+)?" required
                                               placeholder="Ex: 250000,50">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <!-- Boutons -->
                                <div class="col-12 mt-4">
                                    <div class="d-flex justify-content-between">
                                        <a href="read.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Retour
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Enregistrer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>