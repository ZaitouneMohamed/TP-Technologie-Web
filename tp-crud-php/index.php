<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP CRUD PHP/MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:700px">
    <h1 class="mb-2"><i class="bi bi-database me-2"></i>TP CRUD PHP/MySQL</h1>
    <p class="text-muted mb-5">Gestion d'utilisateurs avec PDO, Bootstrap 5 et AJAX.</p>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-primary">
                <div class="card-body text-center py-4">
                    <i class="bi bi-files display-4 text-primary"></i>
                    <h5 class="card-title mt-3">Étape 1</h5>
                    <p class="card-text text-muted small">Fichiers séparés<br>(index, create, view, edit, delete)</p>
                    <a href="etape1/index.php" class="btn btn-primary mt-2">Ouvrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-success">
                <div class="card-body text-center py-4">
                    <i class="bi bi-file-code display-4 text-success"></i>
                    <h5 class="card-title mt-3">Étape 2</h5>
                    <p class="card-text text-muted small">Fichier unique<br>(routing par ?action=)</p>
                    <a href="etape2/index.php" class="btn btn-success mt-2">Ouvrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-warning">
                <div class="card-body text-center py-4">
                    <i class="bi bi-lightning-charge display-4 text-warning"></i>
                    <h5 class="card-title mt-3">Étape 3</h5>
                    <p class="card-text text-muted small">AJAX + API REST<br>(Fetch API, JSON)</p>
                    <a href="etape3/index.php" class="btn btn-warning mt-2">Ouvrir</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
