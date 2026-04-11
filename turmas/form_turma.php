<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Turma - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --verde-eeep: #2d572c; --laranja-eeep: #f39200; }
        body { background-color: #f4f7f6; }
        .navbar { background-color: var(--verde-eeep) !important; border-bottom: 4px solid var(--laranja-eeep); }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .btn-orange { background-color: var(--laranja-eeep); color: white; font-weight: bold; border: none; }
        .btn-orange:hover { background-color: #d88200; color: white; }
    </style>
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
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <h2 class="fw-bold m-0">Cadastrar Nova Turma</h2>
                    </div>

                    <form action="add_turma.php" method="POST">
                        <div class="row">
                            <div class="col-12 col-md-8 mb-3">
                                <label class="form-label fw-bold">Curso</label>
                                <input type="text" class="form-control form-control-lg border-2" name="curso" placeholder="Ex: Informática" required>
                            </div>
                            <div class="col-12 col-md-4 mb-3">
                                <label class="form-label fw-bold">Letra / ID</label>
                                <select name="id_curso" class="form-select form-select-lg border-2" required>
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="A">A</option><option value="B">B</option>
                                    <option value="C">C</option><option value="D">D</option><option value="E">E</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Série Atual</label>
                                <select name="serie_atual" class="form-select form-select-lg border-2" required>
                                    <option value="1">1º Ano</option>
                                    <option value="2">2º Ano</option>
                                    <option value="3">3º Ano</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ano de Conclusão</label>
                                <input type="number" name="ano_conclusao" class="form-control form-control-lg border-2" value="<?= date('Y');?>" required>
                                <small class="text-muted">Ano final da jornada.</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php" class="btn btn-light btn-lg px-4 fw-bold">Cancelar</a>
                            <button type="submit" class="btn btn-orange btn-lg px-5 shadow text-uppercase">Cadastrar Turma</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>