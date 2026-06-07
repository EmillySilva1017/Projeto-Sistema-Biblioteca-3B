<?php session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Pega o ID da turma pela URL
$id_turma = mysqli_real_escape_string($conn, $_GET['id']);

// Busca dados da turma
$sqlTurma = "SELECT * FROM turmas WHERE id_turma = '$id_turma'";
$resTurma = mysqli_query($conn, $sqlTurma);
$dadosTurma = mysqli_fetch_assoc($resTurma);

// Busca alunos dessa turma específica usando a Chave Estrangeira
$sqlAlunos = "SELECT * FROM alunos WHERE fk_id_turma = '$id_turma' ORDER BY numero_chamada ASC";
$resAlunos = mysqli_query($conn, $sqlAlunos);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Turma - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="lista.css">
</head>

<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
        
        <div class="d-flex align-items-center gap-3 mt-3 mb-4">
            <a href="index.php"
                class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center justify-content-center"
                style="height: 42px; width: 42px; min-width: 42px; border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <h2 class="fw-bold m-0 text-dark fs-3 text-truncate">Alunos da Turma</h2>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-left: 5px solid #2d572c !important;">
            <div class="card-body">
                <h4 class="text-uppercase fw-bold mb-1" style="color: #2d572c;">
                    <?= $dadosTurma['serie_atual'] ?>º <?= $dadosTurma['identificador_curso'] ?> -
                    <?= $dadosTurma['curso'] ?>
                </h4>
                <p class="text-muted mb-0">Ano de Conclusão: <?= $dadosTurma['ano_conclusao'] ?></p>
            </div>
        </div>

        <div class="table-container shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="thead-verde text-center">
                        <tr>
                            <th>Nº Chamada</th>
                            <th>Matrícula</th>
                            <th>Nome do Aluno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($resAlunos) > 0): ?>
                            <?php while ($aluno = mysqli_fetch_assoc($resAlunos)): ?>
                                <tr>
                                    <td class="text-center"><?= $aluno['numero_chamada'] ?></td>
                                    <td class="text-center"><?= $aluno['matricula'] ?></td>
                                    <td class="text-center"><?= $aluno['nome_aluno'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Nenhum aluno cadastrado nesta turma.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>