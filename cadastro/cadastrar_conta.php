<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Tela de Cadastro</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="cadastro.css">
</head>

<body>
    <section class="h-100">
        <div class="container-center">
            <div class="box">
                <!--Caixa do Formulario de Cadastro-->
                <h3 class="title">Criar Conta</h3>
                <p class="subtitle">Preencha os campos abaixo para criar sua conta</p>
    
                <!-- Mensagem de cadastro inicio--->
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
                <!-- Mensagem de cadastro final--->
    
                <!---Formulário de Cadrastro--->
                <form action="cadastro.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
                    <!---Campo Nome--->
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
                    </div>
                    <!---Campo Email--->
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                    </div>
                    <!---Campo Senha--->
                    <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="senha" class="form-control" placeholder="Senha" required>
                </div>
    
                    <p class="form-text text-muted mb-3">
                        Ao se registrar você aceita todos os nossos termos e condições.
                    </p>
    
                    <!--Botão de Registrar-se -->
                    <div class="align-items-center d-flex mb-3">
                        <button type="submit" class="btn-main">Registre-se</button>
                    </div>
                </form>
                <!--Link para Login--->
                <div class="mt-3 link">
                        Já tem uma conta? <a href="index.php" class="text-dark">Login.</a>
                </div>
            </div>
        </div>
    </section>
</body>

</html>