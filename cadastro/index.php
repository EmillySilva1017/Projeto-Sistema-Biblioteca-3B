<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Login - Biblioteca</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Link CSS-->
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <section class="h-100">
        <div class="container-center">
            <div class="login-box">
                <!-- logo -->
                <img src="../img/LogoEscola.png" class="logo" alt="Logo">
                <h3 class="login-title">Bem-Vindo à Biblioteca</h3>
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
                <form action="login.php" method="POST" class="needs-validation">
                    <!---Campo Email--->
                    <div class="mb-3 text-start">
                        <label>Email</label>
                        <input type="email" name="email" id="" class="form-control" required>
                    </div>
                    <!---Campo Senha--->
                    <div class="mb-3 text-start">
                        <div class="mb-2 w-100">
                            <label>Senha</label>
                            <a href="###" class="float-end">Forgot Password?</a>
                        </div>
                        <input type="password" name="senha" id="" class="form-control" required>
                    </div>
                    <!--Botão de Login-->
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn-login">Login</button>
                    </div>
                </form>
                <!--Link para Criar uma Conta--->
                <div class="mt-3 link">
                    Não tem conta? <a href="cadastrar_conta.php">Criar conta</a>
                </div>
            </div>
    
        </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>