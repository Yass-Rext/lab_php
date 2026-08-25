<?php
function getArrondissementName($pdo, $id) {
    if (empty($id)) return "Non spécifié";
    
    $stmt = $pdo->prepare("SELECT nom FROM arrondissement WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: "Inconnu";
}

function countLogementsByArrondissement($pdo) {
    return $pdo->query("
        SELECT a.nom, COUNT(l.id) as total 
        FROM arrondissement a
        LEFT JOIN logement l ON a.id = l.id_arrondissement
        GROUP BY a.id
    ")->fetchAll(PDO::FETCH_ASSOC);
}
?>