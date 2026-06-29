<?php
include __DIR__ . '/includes/header.php';
$url = 'https://dummyjson.com/products?limit=30';
$json = @file_get_contents($url);
$data = $json ? json_decode($json, true) : [];
$produits = $data['products'] ?? []; 
?>

<div class="container my-4">
    <h1 class="mb-4">Nos Produits</h1>
    
    <div class="row">
        <!-- TODO 3: Boucler sur les produits avec foreach -->
        <?php foreach ($produits as $produit): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <img
                        src="<?= htmlspecialchars($produit['thumbnail'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($produit['title'] ?? 'Produit', ENT_QUOTES, 'UTF-8') ?>"
                        class="card-img-top"
                        style="height: 200px; object-fit: cover;"
                    >
                   
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($produit['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h5>
                        <p class="card-text"><?= htmlspecialchars($produit['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="h5 mb-0"><?= htmlspecialchars((string)($produit['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?> €</span>
                            <form method="POST" action="actions/ajouter_panier.php">
                                <input type="hidden" name="id" value="<?= htmlspecialchars((string)($produit['id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="title" value="<?= htmlspecialchars($produit['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="price" value="<?= htmlspecialchars((string)($produit['price'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="thumbnail" value="<?= htmlspecialchars($produit['thumbnail'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart-plus"></i> Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>
