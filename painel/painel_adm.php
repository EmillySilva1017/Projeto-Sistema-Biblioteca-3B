<?php session_start();

// Verifica se está logado e se é administrador (nível 1)
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] != 1) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-dark shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand mb-0 h1 mx-auto text-uppercase" style="letter-spacing: 1px;">
                Biblioteca Manoel Mano
            </span>
            <div style="width: 45px;"></div>
        </div>
    </nav>
    <?php include '../includes/menu.php'; ?>
    <div class="container">
        <h1>Pagina em Manuteção</h1>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>