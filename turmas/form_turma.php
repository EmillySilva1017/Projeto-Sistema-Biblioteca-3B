<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['mensagem']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['mensagem']); endif; ?>

                <div class="card card-cadastro">
                    <div class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-mortarboard-fill me-2"></i> Cadastro de Turma</h4>
                            <p class="small text-white-50 mb-0">Insira as informações técnicas para integrar a turma ao sistema.</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="add_turma.php" method="POST">
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-8">
                                    <label class="form-label">Curso</label>
                                    <input type="text" class="form-control" name="curso" placeholder="Ex: Informática" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Letra / ID</label>
                                    <select name="id_curso" class="form-select" required>
                                        <option value="" selected disabled>Selecione</option>
                                        <option value="A">A</option><option value="B">B</option>
                                        <option value="C">C</option><option value="D">D</option><option value="E">E</option>
                                    </select>
                                </div>
                            </div>
    
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Série Atual</label>
                                    <select name="serie_atual" class="form-select" required>
                                        <option value="1">1º Ano</option>
                                        <option value="2">2º Ano</option>
                                        <option value="3">3º Ano</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Ano de Conclusão</label>
                                    <input type="number" name="ano_conclusao" class="form-control" value="<?= date('Y');?>" required>
                                </div>
                            </div>
    
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-salvar btn-lg px-5 shadow text-uppercase">Cadastrar
                                    Turma</button>
                                <a href="index.php"
                                    class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>