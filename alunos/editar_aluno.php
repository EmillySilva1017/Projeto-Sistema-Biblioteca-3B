<?php
session_start();
include '../includes/conexao.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../turmas/visualizar_turma.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql_select = "SELECT * FROM alunos WHERE id_aluno = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    header('Location: ../turmas/visualizar_turma.php');
    exit();
}

// Busca das turmas para o select do formulário
$sql_turmas = "SELECT * FROM turmas ORDER BY curso ASC";
$resTurmas = mysqli_query($conn, $sql_turmas);

$dados = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --verde-eeep: #2d572c; --laranja-eeep: #f39200; }
        .navbar { background-color: var(--verde-eeep) !important; border-bottom: 4px solid var(--laranja-eeep); }
        .card-form { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
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
                        <h2 class="fw-bold m-0">Editar Aluno</h2>
                    </div>

                    <form action="atualizar.php" method="post">
                        <input type="hidden" name="id_aluno" value="<?= $dados['id_aluno'] ?>">
                        <!-- Campos do formulário -->
                        <div class="row">
                            <div class="col-4 col-md-2 mb-3">
                                <label for="numero_chamada" class="form-label fw-bold">Nº</label>
                                <input type="number" class="form-control" name="numero_chamada"
                                    value="<?= $dados['numero_chamada'] ?>" required>
                            </div>
                            <div class="col-8 col-md-10 mb-3">
                                <label for="nome" class="form-label fw-bold">Nome do Aluno</label>
                                <input type="text" class="form-control" name="nome"
                                    value="<?= $dados['nome_aluno'] ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <!---Campo Turma--->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="fk_id_turma" class="form-label fw-bold">Turma</label>
                                <select name="fk_id_turma" class="form-select" required>
                                    <?php while ($turma = mysqli_fetch_assoc($resTurmas)): ?>
                                        <option value="<?= $turma['id_turma']; ?>"
                                            <?= ($turma['id_turma'] == $dados['fk_id_turma']) ? 'selected' : '' ?>>
                                            <?= $turma['serie_atual'] ?>º <?= $turma['identificador_curso'] ?> - <?= $turma['curso'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <!---Campo Matricula--->
                            <div class="col-12 col-md-6 mb-3">
                                <label for="matricula" class="form-label fw-bold">Matrícula</label>
                                <input type="text" name="matricula" class="form-control"
                                    value="<?= $dados['matricula'] ?>" required>
                            </div>
                        </div>
                        <!---Botao de cadastro--->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="../turmas/visualizar_turma.php?id=<?= $dados['fk_id_turma'] ?>" class="btn btn-danger px-4 fw-bold">Cancelar</a>
                            <button type="submit" class="btn btn-success px-4 shadow fw-bold">Editar Aluno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>