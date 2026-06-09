<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header('Location: ../index.php');
    exit();
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_user'];
    
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if (empty($senha_atual) || empty($nova_senha) || empty($confirma_senha)) {
        $_SESSION['msg'] = "Por favor, preencha todos os campos.";
        $_SESSION['msg_tipo'] = "danger";
    } 
    elseif ($nova_senha !== $confirma_senha) {
        $_SESSION['msg'] = "A nova senha e a confirmação não coincidem.";
        $_SESSION['msg_tipo'] = "danger";
    }
    else {
        // Busca a senha atual no banco (Ajuste o nome da tabela/colunas se necessário)
        $sql = "SELECT senha FROM usuario WHERE id_user = '$id_usuario'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $usuario = mysqli_fetch_assoc($res);
            
            if (password_verify($senha_atual, $usuario['senha'])) {
                
                $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                
                $sqlUpdate = "UPDATE usuario SET senha = '$nova_senha_hash' WHERE id_user = '$id_usuario'";
                if (mysqli_query($conn, $sqlUpdate)) {
                    // SUCESSO: Define a mensagem e Redireciona para o Perfil
                    $_SESSION['msg'] = "Senha alterada com sucesso!";
                    $_SESSION['msg_tipo'] = "success";
                    header("Location: perfil.php");
                    exit();
                } else {
                    $_SESSION['msg'] = "Erro ao atualizar a senha no banco de dados.";
                    $_SESSION['msg_tipo'] = "danger";
                }
                
            } else {
                $_SESSION['msg'] = "A senha atual informada está incorreta.";
                $_SESSION['msg_tipo'] = "danger";
            }
        } else {
            $_SESSION['msg'] = "Usuário não encontrado.";
            $_SESSION['msg_tipo'] = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha | Manoteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .card-custom {
            border-radius: 16px;
            border: none;
        }
        .form-control-custom {
            height: 45px;
            border-radius: 10px;
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-4 px-3">
        
        <div class="mb-4">
            <a href="perfil.php" class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i> <span>Voltar ao Perfil</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                
                <div class="card shadow-sm card-custom">
                    <div class="card-body p-4 p-sm-5">
                        
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-lock text-success" style="font-size: 3.5rem;"></i>
                            <h3 class="fw-bold mt-2 text-dark">Alterar Senha</h3>
                            <p class="text-muted small">Crie uma nova senha de acesso para sua conta</p>
                        </div>

                        <?php if (isset($_SESSION['msg'])): ?>
                        <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['msg'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['msg']); 
                            unset($_SESSION['msg_tipo']); ?> 
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Senha Atual</label>
                                <input type="password" name="senha_atual" class="form-control form-control-custom" placeholder="Digite sua senha atual" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nova Senha</label>
                                <input type="password" name="nova_senha" class="form-control form-control-custom" placeholder="Digite a nova senha" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Confirmar Nova Senha</label>
                                <input type="password" name="confirma_senha" class="form-control form-control-custom" placeholder="Repita a nova senha" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success py-2 fw-semibold" style="border-radius: 10px;">
                                    <i class="bi bi-check-circle me-2"></i>Salvar Nova Senha
                                </button>
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