<?php
include '../../includes/config.php';

// Récupération sécurisée de l'ID
$id = intval($_GET['id'] ?? 0);

// Requête préparée pour éviter les injections SQL
$stmt = $pdo->prepare("SELECT * FROM arrondissement WHERE id_arrondissement = ?");
$stmt->execute([$id]);
$arrondissement = $stmt->fetch();

if (!$arrondissement) {
    header("Location: read.php?error=not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nettoyage des données
    $data = [
        'id_arrondissement' => $id,
        'num_arrondissement' => ucwords(strtolower(trim($_POST['num_arrondissement']))),
        
    ];

    try {
        // Requête mise à jour avec tous les champs
        $sql = "UPDATE arrondissement SET 
                num_arrondissement = :num_arrondissement
                
                WHERE id_arrondissement = :id_arrondissement";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        
        header("Location: read.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : " . (($e->errorInfo[1] == 1062) ? 
                 "Ce numéro d'arrondissement existe déjà" : 
                 $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Arrondissement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0">Modifier Arrondissement</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Numéro*</label>
                                <input type="text" class="form-control" name="num_arrondissement" 
                                       value="<?= htmlspecialchars($arrondissement['num_arrondissement']) ?>" 
                                        title="Chiffres et lettres ">
                            </div>
                            
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="read.php" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>