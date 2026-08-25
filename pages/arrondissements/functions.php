<?php
function validatePostalCode($code) {
    return preg_match('/^[0-9]{5}$/', $code);
}

function getArrondissementStats($pdo) {
    return $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(IF(code_postal LIKE '75%', 1, 0)) as parisiens
        FROM arrondissement
    ")->fetch(PDO::FETCH_ASSOC);
}

function suggestPostalCode($pdo, $nom) {
    $stmt = $pdo->prepare("
        SELECT code_postal 
        FROM arrondissement 
        WHERE nom LIKE ? 
        LIMIT 1
    ");
    $stmt->execute(["%$nom%"]);
    return $stmt->fetchColumn() ?: '75000';
}
?>