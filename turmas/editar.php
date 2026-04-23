<?php
session_start();
include '../includes/conexao.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql_select = "SELECT * FROM turmas WHERE id_turma = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$dados = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --verde-eeep: #2d572c; --laranja-eeep: #f39200; }
        body { background-color: #f4f7f6; }
        .navbar { background-color: var(--verde-eeep) !important; border-bottom: 4px solid var(--laranja-eeep); }
        .card-edit { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 8px solid var(--laranja-eeep); }
        .btn-update { background-color: var(--verde-eeep); color: white; font-weight: bold; border: none; }
        .btn-update:hover { background-color: #1e3a1d; color: white; }
    </style>
</head>
<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card card-edit p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-pencil-fill fs-4"></i>
                        </div>
                        <div>
                            <h2 class="fw-bold m-0">Editar Turma</h2>
                            <p class="text-muted mb-0">Alterando dados da turma ID: #<?= $id ?></p>
                        </div>
                    </div>

                    <form action="atualizar.php" method="POST">
                        <input type="hidden" name="id" value="<?= $id; ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Curso</label>
                                <input type="text" class="form-control form-control-lg" name="curso" value="<?= htmlspecialchars($dados['curso']); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Letra</label>
                                <select name="id_curso" class="form-select form-select-lg" required>
                                    <?php foreach(['A','B','C','D','E'] as $letra): ?>
                                        <option value="<?= $letra ?>" <?= ($dados['identificador_curso'] == $letra) ? 'selected' : ''; ?>><?= $letra ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Série Atual</label>
                                <select name="serie_atual" class="form-select form-select-lg" required>
                                    <option value="1" <?= ($dados['serie_atual'] == "1") ? 'selected' : ''; ?>>1º Ano</option>
                                    <option value="2" <?= ($dados['serie_atual'] == "2") ? 'selected' : ''; ?>>2º Ano</option>
                                    <option value="3" <?= ($dados['serie_atual'] == "3") ? 'selected' : ''; ?>>3º Ano</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ano de Conclusão</label>
                                <input type="number" name="ano_conclusao" class="form-control form-control-lg" value="<?= $dados['ano_conclusao'];?>" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="index.php" class="btn btn-light btn-lg px-4 fw-bold">Voltar</a>
                            <button type="submit" class="btn btn-update btn-lg px-5 shadow text-uppercase">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>