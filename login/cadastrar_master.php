<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

// TRAVA: Se já existir ADM, ninguém mais pode acessar esta tela
$sql_check = "SELECT COUNT(*) AS total FROM usuario WHERE nivel = 'adm'";
$res_check = mysqli_query($conn, $sql_check);
$total_adms = ($res_check) ? mysqli_fetch_assoc($res_check)['total'] : 0;

if ($total_adms > 0) {
    $_SESSION['mensagem'] = "O Administrador Master já foi cadastrado no sistema!";
    $_SESSION['msg_tipo'] = "warning";
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Configuração Inicial - Criar Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-4">
                    <h3 class="fw-bold text-center text-success mb-2">Primeiro Acesso</h3>
                    <p class="text-muted text-center small mb-4">Crie a conta do Administrador Supremo do sistema.</p>

                    <form action="processa_master.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" required placeholder="Ex: Administrador">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">E-mail Master</label>
                            <input type="email" name="email" class="form-control" required placeholder="admin@biblioteca.com">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Senha</label>
                            <input type="password" name="senha" class="form-control" required placeholder="••••••••">
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-2">Cadastrar Master e Iniciar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>