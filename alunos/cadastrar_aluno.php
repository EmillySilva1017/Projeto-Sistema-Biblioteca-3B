<?php
session_start();
include('../includes/conexao.php');
// Busca todas as turmas cadastradas
$sqlTurmas = "SELECT id_turma, curso, identificador_curso, serie_atual FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$resTurmas = mysqli_query($conn, $sqlTurmas);
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
    <?php include('../includes/menu.php'); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['mensagem']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['mensagem']);
                endif; ?>

                <div class="card card-form p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                        <h2 class="fw-bold m-0">Cadastrar Novo Aluno</h2>
                    </div>

                    <form action="add_aluno.php" method="post">
                        <!-- Campos do formulário -->
                        <div class="row">
                            <div class="col-4 col-md-2 mb-3">
                                <label for="numero_chamada" class="form-label fw-bold">Nº</label>
                                <input type="number" class="form-control" name="numero_chamada" min="1" max="99" placeholder="01" required>
                            </div>
                            <!---Campo Nome--->
                            <div class="col-8 col-md-10 mb-3">
                                <label for="nome" class="form-label fw-bold">Nome do Aluno</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                        </div>
                        <div class="row">
                            <!---Campo Turma--->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="fk_id_turma" class="form-label fw-bold">Turma</label>
                                <select name="fk_id_turma" class="form-select" required>
                                    <option value="">Selecione a Turma</option>
                                    <?php while ($turma = mysqli_fetch_assoc($resTurmas)): ?>
                                        <option value="<?= $turma['id_turma']; ?>">
                                            <?= $turma['serie_atual'] ?>º <?= $turma['identificador_curso'] ?> - <?= $turma['curso'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <!---Campo Matricula--->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="matricula" class="form-label fw-bold">Matrícula</label>
                                <input type="text" name="matricula" class="form-control" placeholder="Ex: 25034279" required>
                            </div>
                        </div>
                        <!---Botao de cadastro--->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="visualizar.php" class="btn btn-light btn-lg px-4 fw-bold">Cancelar</a>
                            <button type="submit" class="btn btn-success px-5 shadow text-uppercase">Cadastrar Aluno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>