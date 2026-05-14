<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Esqueceu senha?</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="alterar.css">
</head>

<body>
    <section class="h-100">
        <div class="container-center">
            <div class="login-box">
                        <h2 class="fw-bold m-0">Esqueceu a senha?</h2>
                        <p>Informe seu email para redefinir a senha.</p>
                        <?php
                        if (isset($_SESSION['mensagem'])):
                            ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <?= $_SESSION['mensagem']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <?php unset($_SESSION['mensagem']); //limpa mensagem 
                        endif;
                        ?>
                        <form action="redefinir_senha.php" method="POST">
                            <div class="mb-3 text-start">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control form-control-md border-2" name="email"
                                    placeholder="Seu email" required>
                            </div>
                            <button type="submit" class="btn btn-alterar">Redefinir Senha</button>
                        </form>
                    </div>
                </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>