<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Cadastro de Livros</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
     <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['mensagem']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['mensagem']); endif; ?>

                <div class="card card-form p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-book fs-4"></i>
                        </div>
                        <h2 class="fw-bold m-0">Cadastrar Novo Livro</h2>
                    </div>

                    <form action="cadastro.php" method="POST">
                        <div class="row">
                            <div class="col-12 col-md-8 mb-3">
                                <label class="form-label fw-bold">Titulo</label>
                                <input type="text" class="form-control form-control-lg border-2" name="titulo" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label fw-bold">N° Registro</label>
                                <input type="text" class="form-control form-control-lg border-2" name="n_registro" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Autor</label>
                                <input type="text" class="form-control form-control-lg border-2" name="autor" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Editora</label>
                                <input type="text" class="form-control form-control-lg border-2" name="editora" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Gênero</label>
                                <input type="text" class="form-control form-control-lg border-2" name="genero" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Data Aquisição</label>
                                <input type="date" class="form-control form-control-lg border-2" name="ano_aquisicao" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">CDD</label>
                                <input type="text" class="form-control form-control-lg border-2" name="cdd" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">CDU</label>
                                <input type="text" class="form-control form-control-lg border-2" name="cdu" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Selo</label>
                                <input type="text" class="form-control form-control-lg border-2" name="selo" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="visualizar_livros.php" class="btn btn-light btn-lg px-4 fw-bold">Cancelar</a>
                            <button type="submit" class="btn btn-orange btn-lg px-5 shadow text-uppercase">Cadastrar Livro</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>