<?php
require_once '../config.php';
session_start();
$pdo = getConnection();

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['msg'] = ['type' => 'warning', 'text' => 'Utilisateur supprimé.'];
}

header('Location: index.php');
exit;
