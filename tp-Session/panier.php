<?php
include __DIR__ . '/includes/header.php';

$total = 0;
?>

<div class="container my-4">
    <h1 class="mb-4">Mon Panier</h1>
 
    <?php if (empty($_SESSION['panier'])): ?>
        <div class="alert alert-info">
            Votre panier est vide. <a href="index.php" class="alert-link">Voir les produits</a>.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantite</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['panier'] as $article): ?>
                        <?php
                            $quantite = (int) ($article['quantite'] ?? 0);
                            $prix = (float) ($article['price'] ?? 0);
                            $sous_total = $prix * $quantite;
                            $total += $sous_total;
                        ?>
                        <tr>
                            <td class="d-flex align-items-center">
                                <img
                                    src="<?= htmlspecialchars($article['thumbnail'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    alt="<?= htmlspecialchars($article['title'] ?? 'Produit', ENT_QUOTES, 'UTF-8') ?>"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                    class="me-2"
                                >
                                <?= htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td><?= number_format($prix, 2) ?> &euro;</td>
                            <td><?= htmlspecialchars((string) $quantite, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= number_format($sous_total, 2) ?> &euro;</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total :</th>
                        <th><?= number_format($total, 2) ?> &euro;</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <a class="btn btn-secondary" href="index.php">Continuer les achats</a>
            <form method="POST" action="actions/vider_panier.php" class="mb-0">
                <button type="submit" class="btn btn-danger">Vider le panier</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
