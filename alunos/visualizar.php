<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-dark shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list"></i>
            </button>
            <span class="navbar-brand mb-0 h1 mx-auto text-uppercase">Biblioteca Manoel Mano</span>
        </div>
    </nav>

    <?php include('../includes/menu.php'); ?>

    <div class=" container py-5">
        <div class="row justify-content-md-center">
            <div class="col-md-auto mb-3">
                <a href="../alunos/cadastrar_aluno.php" class="btn btn-success">Cadastrar aluno</a>
            </div>
            <div class="col-md-auto mb-3">
                <a href="../turmas/index.php" class="btn btn-primary">Visualizar Alunos</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>