<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

$query = "SELECT * FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$result = mysqli_query($conn, $query);
$lista_turma = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="lista.css">
</head>

<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container-fluid px-4 pt">
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <?= $_SESSION['mensagem']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <div class="row align-items-center mt-3 mb-3">
            <div class="col-12 col-md-6 text-center text-md-start mb-md-0">
                <h2 class="fw-bold m-0">Gerenciar Turmas</h2>
            </div>
            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-end align-items-center gap-2 mt-3">
                <a href="promover_turmas.php"
                    class="btn btn-promover px-4 py-2 fw-bold rounded-3 shadow-sm w-sm-auto flex-fill flex-md-none"
                    onclick="return confirm('Deseja promover todas as turmas? O 3º ano será arquivado!')">
                    <i class="bi bi-arrow-up-circle me-1"></i> Promover Turmas
                </a>
            </div>
            <div class="col-12 col-md-3 d-flex justify-content-center justify-content-md-end align-items-center gap-2 mt-3">
                <a href="form_turma.php"
                    class="btn btn-cadastro px-4 py-2 fw-bold rounded-3 shadow-sm w-sm-auto flex-fill flex-md-none">
                    <i class="bi bi-plus-lg me-1"></i> NOVA TURMA
                </a>
            </div>
        </div>

        <div class="table-container shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="thead-verde text-center">
                        <tr>
                            <th>Série</th>
                            <th>Curso</th>
                            <th>Ano Atual</th>
                            <th>Ano Conclusão</th>
                            <th>Alunos</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_turma as $turma): ?>
                            <tr>
                                <td class="fw-bold text-center"><?= $turma['serie_atual'] . "° ano"; ?></td>
                                <td class="text-center"><?= $turma['curso']; ?></td>
                                <td class="text-center"><?php echo date('Y'); ?></td>
                                <td class="text-center"><?= $turma['ano_conclusao'] ?></td>
                                <td class="text-center">
                                    <a href="visualizar_turma.php?id=<?= $turma['id_turma']; ?>"
                                        class="btn btn-sm btn-success px-3" title="Ver Alunos">
                                        <i class="bi bi-person-fill me-1"></i>Ver alunos
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="editar.php?id=<?= $turma['id_turma'] ?>"
                                            class="btn btn-sm btn-warning me-2">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="excluir.php?id=<?= $turma['id_turma'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Deseja excluir esta turma?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>