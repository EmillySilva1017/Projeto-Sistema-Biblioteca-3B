<?php
session_start();

// Captura a role da URL (se não vier nada, joga o padrão 'aluno')
$role = isset($_GET['role']) ? $_GET['role'] : 'aluno';

// Define títulos e configurações visuais baseados na role
$titulo_portal = "Portal do Aluno";
$cor_botao = "btn-primary";

if ($role === 'bibliotecario') {
    $titulo_portal = "Painel do Bibliotecário";
    $cor_botao = "btn-success";
} elseif ($role === 'adm') {
    $titulo_portal = "Painel Administrativo";
    $cor_botao = "btn-warning";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= $titulo_portal; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="login.css?v=1.2">
</head>

<body class="d-flex align-items-center">
    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">

                <div class="card card-login">
                    <img src="../img/LogoManoteca-removebg-preview.png" alt="Logo Manoteca" class="logo-manoteca">
                    <h4 class="fw-bold text-center mb-4 text-dark"><?= $titulo_portal; ?></h4>

                    <?php include '../includes/alerta.php'; ?>

                    <form action="processa_login.php" method="POST">
                        <input type="hidden" name="role" value="<?= $role; ?>">

                        <?php if ($role === 'aluno'): ?>
                            <div class="mb-4">
                                <label class="form-label fw-medium text-secondary">Número da Matrícula</label>
                                <input type="text" name="matricula" class="form-control" required autofocus
                                    placeholder="Ex: 202612345">
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label fw-medium text-secondary">E-mail Institucional</label>
                                <input type="email" name="usuario" class="form-control" required
                                    placeholder="nome@biblioteca.com">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-medium text-secondary">Senha</label>
                                <input type="password" name="senha" class="form-control" required placeholder="••••••••">
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn <?= $cor_botao; ?> w-100 fw-bold shadow-sm">Entrar</button>

                        <div class="text-center mt-4">
                            <a href="index.php" class="text-muted small text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Voltar para seleção de portal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>