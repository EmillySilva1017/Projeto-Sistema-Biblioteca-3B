<?php
session_start();
require_once '../includes/verifica_aluno.php';
include '../includes/conexao.php';
/** @var mysqli $conn */

if (!isset($_SESSION['nome_aluno'])) {
    header('Location: ../login/login.php?role=aluno');
    exit();
}

$nome_aluno = mysqli_real_escape_string($conn, $_SESSION['nome_aluno']);

// Busca os dados cadastrais do aluno
$sql = "SELECT * FROM alunos WHERE nome_aluno = '$nome_aluno' LIMIT 1";
$res = mysqli_query($conn, $sql);
$aluno = ($res) ? mysqli_fetch_assoc($res) : null;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | ManoTeca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <div class="mb-4">
            <a href="dashboard_aluno.php"
                class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center gap-2"
                style="border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i> <span>Voltar</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <!-- CARD DE PERFIL -->
                <div class="card-box p-4">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-icon-green rounded-circle mb-3"
                            style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <i class="bi bi-person"></i>
                        </div>
                        <h5 class="fw-bold m-0 text-dark"><?= htmlspecialchars($_SESSION['nome_aluno']); ?></h5>
                        <span class="badge bg-secondary mt-1">Aluno</span>
                    </div>

                    <hr class="text-muted opacity-25">

                    <!-- INFORMAÇÕES DO ALUNO -->
                    <div class="d-flex flex-column gap-3 my-4">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded bg-light">
                            <span class="text-muted small fw-bold">NOME COMPLETO</span>
                            <span
                                class="fw-semibold text-dark"><?= htmlspecialchars($aluno['nome'] ?? $_SESSION['nome_aluno']); ?></span>
                        </div>

                        <?php if (isset($aluno['turma'])): ?>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded bg-light">
                                <span class="text-muted small fw-bold">TURMA / SÉRIE</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($aluno['turma']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($aluno['matricula'])): ?>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded bg-light">
                                <span class="text-muted small fw-bold">MATRÍCULA</span>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($aluno['matricula']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>