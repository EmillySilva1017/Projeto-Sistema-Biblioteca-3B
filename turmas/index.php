<?php
session_start();
include('../includes/conexao.php');

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
    <title>Turmas - EEEP Manoel Mano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="index.css">
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
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <?= $_SESSION['mensagem']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <h2 class="fw-bold m-0">Gerenciar Turmas</h2>
            <a href="form_turma.php" class="btn-add-turma text-uppercase shadow-sm">
                <i class="bi bi-plus-lg"></i> ADD TURMA
            </a>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Série</th>
                            <th>Curso</th>
                            <th>Ano Atual</th>
                            <th>Ano Conclusão</th>
                            <th class="text-center">Visualizar</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_turma as $turma) : ?>
                            <tr>
                                <td class="fw-bold"><?= $turma['serie_atual'] . "° ano"; ?></td>
                                <td><?= $turma['curso']; ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo date('Y'); ?></span></td>
                                <td><?= $turma['ano_conclusao'] ?></td>
                                <td class="text-center">
                                    <a href="visualizar_turma.php?id=<?= $turma['id_turma']; ?>" class="btn btn-sm btn-visu fw-bold px-3">
                                        VISUALIZAR
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="editar.php?id=<?= $turma['id_turma']; ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                                        <a href="excluir.php?id=<?= $turma['id_turma']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Tem certeza que deseja excluir?')">
                                           Excluir
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