<?php
if (!isset($_GET['id'])) {
    header('Location: panier.php');
    exit;
}
$id = (int)$_GET['id'];

$panier = isset($_COOKIE['panier']) ? unserialize($_COOKIE['panier'], ["allowed_classes" => false]) : [];

if (isset($panier[$id])) {
    unset($panier[$id]);
    if (empty($panier)) {
        setcookie('panier', '', time() - 3600, '/');
    } else {
        setcookie('panier', serialize($panier), time() + (30 * 86400), '/', '', false, true);
    }
}
header('Location: panier.php');
exit;
?>
