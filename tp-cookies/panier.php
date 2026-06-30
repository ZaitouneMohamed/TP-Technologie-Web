<?php
$panier = isset($_COOKIE['panier']) ? unserialize($_COOKIE['panier'], ["allowed_classes" => false]) : [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4"><i class="bi bi-cart4 me-2"></i> Votre panier</h1>
        <a href="index.php" class="btn btn-outline-primary mb-4"><i class="bi bi-arrow-left"></i> Continuer mes achats</a>

        <?php if (empty($panier)): ?>
            <div class="alert alert-secondary text-center py-5">
                <i class="bi bi-cart-x display-1"></i>
                <h3>Votre panier est vide</h3>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Produit</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($panier as $id => $item):
                            $sousTotal = $item['prix'] * $item['quantite'];
                            $total += $sousTotal;
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['nom']) ?></strong></td>
                            <td class="text-end"><?= number_format($item['prix'], 2) ?> €</td>
                            <td class="text-center"><span class="badge bg-primary fs-6"><?= $item['quantite'] ?></span></td>
                            <td class="text-end fw-bold"><?= number_format($sousTotal, 2) ?> €</td>
                            <td>
                                <a href="supprimer.php?id=<?= $id ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-success">
                            <th colspan="3" class="text-end">Total général</th>
                            <th class="text-end fs-4"><?= number_format($total, 2) ?> €</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <h5>Cookie brut (pour déboguer)</h5>
                <code class="small"><?= htmlspecialchars($_COOKIE['panier'] ?? '(aucun cookie)') ?></code>
            </div>
        </div>
    </div>
</body>
</html>
