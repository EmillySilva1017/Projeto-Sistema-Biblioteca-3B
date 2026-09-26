<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */
require_once '../includes/verifica_admin.php';

// TRAVA DE SEGURANÇA: Apenas ADM pode acessar
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] !== 'adm') {
    $_SESSION['msg'] = "Acesso negado!";
    $_SESSION['msg_tipo'] = "danger";
    header('Location: perfil.php');
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: perfil.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql_select = "SELECT * FROM usuario WHERE id_user = '$id'";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['msg'] = "Usuário não encontrado!";
    $_SESSION['msg_tipo'] = "danger";
    header('Location: perfil.php');
    exit();
}

$dados = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Funcionário | ManoTeca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../emprestimos/cadastro.css">
</head>

<body>
    <?php include '../includes/menu.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <?php include '../includes/alerta.php'; ?>

                <div class="card card-cadastro">
                    <div
                        class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-person-fill-gear me-2"></i>Editar Funcionário</h4>
                            <p class="small text-white-50 mb-0">Atualize os dados e as permissões da conta.</p>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="atualizar.php" method="POST" autocomplete="off">
                            <input type="hidden" name="id" value="<?= $dados['id_user']; ?>">

                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" name="nome" class="form-control border-2"
                                        value="<?= htmlspecialchars($dados['nome_user']); ?>"
                                        placeholder="Ex: Maria Silva" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">E-mail Institucional</label>
                                    <input type="email" name="email" class="form-control border-2"
                                        value="<?= htmlspecialchars($dados['email']); ?>"
                                        placeholder="nome@escola.ce.gov.br" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nível de Acesso</label>
                                    <select name="nivel_novo" class="form-select border-2" required>
                                        <option value="bibliotecario" <?= ($dados['nivel'] === 'bibliotecario') ? 'selected' : ''; ?>>Bibliotecário</option>
                                        <option value="adm" <?= ($dados['nivel'] === 'adm') ? 'selected' : ''; ?>>
                                            Administrador (Acesso Total)</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nova Senha</label>
                                    <input type="password" name="senha" class="form-control border-2"
                                        placeholder="Deixe em branco para manter a senha atual"
                                        autocomplete="new-password">
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-salvar btn-lg px-5 shadow">
                                        <i class="bi bi-check-lg me-2"></i>Salvar Alterações
                                    </button>
                                    <a href="perfil.php"
                                        class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">
                                        Cancelar
                                    </a>
                                </div>
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