<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Alterar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="alterar.css">
</head>
<body>
    <section class="h-100">
        <div class="container-center">
            <div class="login-box">
                <?php if (isset($_SESSION['msg'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= $_SESSION['msg']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <?php unset($_SESSION['msg']); endif; ?>

                    <h2 class="fw-bold m-0">Alterar Senha</h2>
                    <p>Redefina sua senha aqui.</p>

                    <form action="processar_alteracao.php" method="POST">
                        <div class="mb-3 text-start">
                            <label for="nova_senha" class="form-label fw-bold">Nova Senha</label>
                            <input type="password" class="form-control form-control-md border-2" name="nova_senha"
                                placeholder="Digite a nova senha" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="confirmar_senha" class="form-label fw-bold">Confirmar Nova Senha</label>
                            <input type="password" class="form-control form-control-md border-2" name="confirmar_senha"
                                placeholder="Digite a nova senha novamente" required>
                        </div>
                        <button type="submit" class="btn btn-alterar">Alterar Senha</button>
                    </form>
                </div>
            </div>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>