<?php
if (!isset($_POST['id'], $_POST['nom'], $_POST['prix'])) {
    header('Location: index.php');
    exit;
}

$id   = (int)$_POST['id'];
$nom  = $_POST['nom'];
$prix = (float)$_POST['prix'];

$panier = isset($_COOKIE['panier']) ? unserialize($_COOKIE['panier'], ["allowed_classes" => false]) : [];

if (isset($panier[$id])) {
    $panier[$id]['quantite']++;
} else {
    $panier[$id] = ['nom' => $nom, 'prix' => $prix, 'quantite' => 1];
}

setcookie('panier', serialize($panier), time() + (30 * 86400), '/', '', false, true);
header('Location: panier.php');
exit;
?>
