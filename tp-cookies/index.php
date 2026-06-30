<?php require_once 'db.php';
$stmt = $pdo->query("SELECT * FROM produits ORDER BY nom");
$produits = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Magasin - TP Cookies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-shop me-2"></i> Magasin en ligne</h1>
        <div class="alert alert-info">
            <i class="bi bi-cookie me-2"></i>
            Ce site utilise des <strong>cookies</strong> pour conserver votre panier même si vous fermez le navigateur !
        </div>

        <div class="text-end mb-3">
            <a href="panier.php" class="btn btn-primary btn-lg">
                <i class="bi bi-cart4 me-2"></i> Voir mon panier
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach($produits as $p): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($p['nom']) ?></h5>
                        <p class="card-text mt-auto">
                            <strong class="text-success fs-4"><?= number_format($p['prix'], 2) ?> €</strong>
                        </p>
                        <form method="post" action="ajouter.php" class="mt-3">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <input type="hidden" name="nom" value="<?= htmlspecialchars($p['nom']) ?>">
                            <input type="hidden" name="prix" value="<?= $p['prix'] ?>">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-cart-plus me-2"></i> Ajouter au panier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <hr class="my-5">
        <p class="text-center">
            <a href="vider.php" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Vider le panier</a>
            <a href="vider.php?demo_expire=1" class="btn btn-outline-warning ms-3">
                <i class="bi bi-clock"></i> Démo expiration (30 s)
            </a>
        </p>
    </div>
</body>
</html>
