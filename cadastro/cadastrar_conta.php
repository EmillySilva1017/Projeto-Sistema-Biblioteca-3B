<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Tela de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
    <section class="h-100">
        <div class="container h-100 mt-5">
                    <!--Caixa do Formulario de Cadastro-->
                    <div class="card shadow-lg">
                        <div class="card-body p-5">
                            <h1 class="fs-4 card-title fw-bold mb-4">Registre-se</h1>
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
                                <div class="mb-3">
                                    <label for="nome" class="mb-2 text-muted">Nome</label>
                                    <input type="text" name="nome" id="" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Name is required
                                    </div>
                                </div>
                                <!---Campo Email--->
                                <div class="mb-3">
                                    <label for="email" class="mb-2 text-muted">Email</label>
                                    <input type="email" name="email" id="" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Email is invalid
                                    </div>
                                </div>
                                <!---Campo Senha--->
                                <div class="mb-3">
                                    <label for="senha" class="mb-2 text-muted">Senha</label>
                                    <input type="password" name="senha" id="" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                </div>

                                <p class="form-text text-muted mb-3">
                                    Ao se registrar você aceita todos os nossos termos e condições.
                                </p>

                                <!--Botão de Registrar-se -->
                                <div class="align-items-center d-flex mb-3">
                                    <button type="submit" class="btn btn-success ms-auto">Registre-se</button>
                                </div>
                                <!--Link para Login--->
                                <div class="card-footer py-3 border-0">
                                    <div class="text-center">
                                        Já tem uma conta? <a href="index.php" class="text-dark">Login.</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-5 text-muted">
                        Copyright &copy; 2026 &mdash; 3°B
                    </div>
        </div>
    </section>
</body>

</html>