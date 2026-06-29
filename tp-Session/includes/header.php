<?php
session_start();
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}
if (!empty($_SESSION['panier'])) {
    $quantites = array_column($_SESSION['panier'], 'quantite');
    $nombre_articles = array_sum($quantites);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Boutique en Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Ma Boutique</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="panier.php">
                            <i class="bi bi-cart"></i>
                            Panier
                            <span class="badge bg-danger ms-1"><?= htmlspecialchars((string) $nombre_articles, ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>