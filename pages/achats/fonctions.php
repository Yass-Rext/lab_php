<?php
function formatPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' €';
}

function getClientName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT nom FROM client WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
}
?>