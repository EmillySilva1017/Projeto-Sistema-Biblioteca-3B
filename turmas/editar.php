<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */


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
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card card-cadastro">
                    <div class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-pencil-fill me-2"></i> Edição de Turma</h4>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="atualizar.php" method="POST">
                            <input type="hidden" name="id" value="<?= $id; ?>">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-bold">Curso</label>
                                    <input type="text" class="form-control form-control-lg" name="curso" value="<?= htmlspecialchars($dados['curso']); ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold">Letra</label>
                                    <input name="id_curso" class="form-control" maxlength="1" style="text-transform: uppercase;" 
                                    value="<?= htmlspecialchars($dados['identificador_curso']); ?>" required></input>
                                </div>
                            </div>
    
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Série Atual</label>
                                    <select name="serie_atual" class="form-select form-select-lg" required>
                                        <option value="1" <?= ($dados['serie_atual'] == "1") ? 'selected' : ''; ?>>1º Ano</option>
                                        <option value="2" <?= ($dados['serie_atual'] == "2") ? 'selected' : ''; ?>>2º Ano</option>
                                        <option value="3" <?= ($dados['serie_atual'] == "3") ? 'selected' : ''; ?>>3º Ano</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Ano de Conclusão</label>
                                    <input type="number" name="ano_conclusao" class="form-control form-control-lg" value="<?= $dados['ano_conclusao'];?>" required>
                                </div>
                            </div>
    
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-salvar btn-lg px-5 shadow text-uppercase">Salvar</button>
                                <a href="index.php" class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
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