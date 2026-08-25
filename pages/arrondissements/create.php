<?php
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'num_arrondissement' => ucwords(strtolower(trim($_POST['num_arrondissement'])))
    ];

    try {
        $sql = "INSERT INTO arrondissement (num_arrondissement) VALUES (:num_arrondissement)";
        $pdo->prepare($sql)->execute($data);
        
        header("Location: read.php?success=1");
        exit();
    } catch (PDOException $e) {
        $error = "Erreur : " . ($e->errorInfo[1] == 1062 ? "Cet arrondissement existe déjà" : $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvel Arrondissement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0">Créer un Arrondissement</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="num_arrondissement" class="form-label">Num*</label>
                                <input type="text" class="form-control" id="num_arrondissement" name="num_arrondissement" value="<?= htmlspecialchars($data['num_arrondissement'] ?? '') ?>"
                                       title="Lettres, espaces et traits d'union uniquement">
                            </div>
                            
                            
                            

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="read.php" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>