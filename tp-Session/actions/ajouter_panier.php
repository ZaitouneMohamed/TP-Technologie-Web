<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? null;
    $price = $_POST['price'] ?? null;
    $thumbnail = $_POST['thumbnail'] ?? null;
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    $produit_existe = false;
    foreach ($_SESSION['panier'] as &$item) {
        if ($item['id'] === $id) {
            $item['quantite']++;
            $produit_existe = true;
            break;
        }
    }
    unset($item);
    if (!$produit_existe) {
        $_SESSION['panier'][] = [
            'id' => $id,
            'title' => $title,
            'price' => $price,
            'thumbnail' => $thumbnail,
            'quantite' => 1,
        ];
    }
}
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
exit();
?>