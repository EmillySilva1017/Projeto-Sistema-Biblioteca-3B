<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: visualizar.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql_select = "SELECT * FROM alunos WHERE id_aluno = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    header('Location: visualizar.php');
    exit();
}

// Busca das turmas para o select do formulário
$sql_turmas = "SELECT * FROM turmas ORDER BY serie_atual, identificador_curso ASC";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="cadastro.css">
</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['mensagem']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensagem']);
                endif; ?>

                <div class="card card-cadastro">
                    <div
                        class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-pencil-fill me-2"></i> Editar Aluno</h4>
                            <p class="small text-white-50 mb-0">Preencha os dados abaixo para editar o aluno.
                            </p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="atualizar.php" method="post">
                            <input type="hidden" name="id_aluno" value="<?= $dados['id_aluno'] ?>">
                            <!-- Campos do formulário -->
                            <div class="row g-4">
                                <div class="col-12 col-md-2 mb-3">
                                    <label for="numero_chamada" class="form-label fw-bold">Nº</label>
                                    <input type="number" class="form-control" name="numero_chamada"
                                        value="<?= $dados['numero_chamada'] ?>" required>
                                </div>
                                <div class="col-12 col-md-10 mb-3">
                                    <label for="nome" class="form-label fw-bold">Nome do Aluno</label>
                                    <input type="text" class="form-control" name="nome"
                                        value="<?= $dados['nome_aluno'] ?>" required>
                                </div>
                            </div>
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <!---Campo Turma--->
                                    <label for="fk_id_turma" class="form-label fw-bold">Turma</label>
                                    <select name="fk_id_turma" class="form-select" required>
                                        <?php while ($turma = mysqli_fetch_assoc($resTurmas)): ?>
                                        <option value="<?= $turma['id_turma']; ?>"
                                            <?= ($turma['id_turma'] == $dados['fk_id_turma']) ? 'selected' : '' ?>>
                                            <?= $turma['serie_atual'] ?>º
                                            <?= $turma['identificador_curso'] ?> -
                                            <?= $turma['curso'] ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <!---Campo Matricula--->
                                <div class="col-12 col-md-6">
                                    <label for="matricula" class="form-label fw-bold">Matrícula</label>
                                    <input type="text" name="matricula" class="form-control"
                                        value="<?= $dados['matricula'] ?>" required>
                                </div>
                            </div>
                            <!---Botao de edição--->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-salvar btn-lg px-5 shadow text-uppercase">Salvar</button>
                                    <a href="visualizar.php"
                                    class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>