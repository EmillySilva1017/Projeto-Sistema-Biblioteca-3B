<?php 
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

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
    <title>Edição de Usuário | Manoteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <section class="h-100">
        <div class="container-center">
            <div class="box">
                <h3 class="title"><i class="bi bi-person-fill me-2"></i>Editar Usuário</h3>
                <p class="subtitle">Altere as informações do funcionário</p>

                <?php include '../includes/alerta.php'; ?>

                <form action="atualizar.php" method="POST" autocomplete="off">
                    <input type="hidden" name="id" value="<?= $dados['id_user']; ?>">

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($dados['nome_user']);?>" placeholder="Nome Completo" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($dados['email']); ?>" placeholder="E-mail Institucional" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <select name="nivel_novo" class="form-select" required style="border-left: none; border-radius: 10px; border: 2px solid #ddd;">
                            <option value="bibliotecario" <?= ($dados['nivel'] === 'bibliotecario') ? 'selected' : ''; ?>>Bibliotecário</option>
                            <option value="adm" <?= ($dados['nivel'] === 'adm') ? 'selected' : ''; ?>>Administrador (Acesso Total)</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="senha" class="form-control" placeholder="Nova Senha (deixe em branco se não quiser alterar)">
                    </div>

                    <div class="align-items-center d-flex mb-3">
                        <button type="submit" class="btn-main">Atualizar Usuário</button>
                    </div>
                </form>

                <div class="mt-3 link">
                    <a href="perfil.php" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Voltar para o Perfil
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>