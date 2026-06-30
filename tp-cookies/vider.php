<?php
if (isset($_GET['demo_expire'])) {
    if (isset($_COOKIE['panier'])) {
        setcookie('panier', $_COOKIE['panier'], time() + 30, '/', '', false, true);
        echo "<!DOCTYPE html><html><head><title>Expiration</title>
              <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head>
              <body class='bg-light'><div class='container py-5 text-center'>
              <div class='alert alert-warning'>Le panier expirera dans 30 secondes !</div>
              <a href='panier.php' class='btn btn-primary'>Voir le panier</a></div></body></html>";
    }
} else {
    setcookie('panier', '', time() - 3600, '/');
    unset($_COOKIE['panier']);
    header('Location: index.php');
}
?>
