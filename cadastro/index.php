<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Tela de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <section class="h-100">
        <div class="container h-100 mt-5">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
                    <form action="login.php" method="POST" class="needs-validation">
                        <!---Campo Email--->
                        <div class="mb-3">
                            <label for="email" class="mb-2 text-muted">Email</label>
                            <input type="email" name="email" id="" class="form-control" required>
                            <div class="invalid-feedback">
                                Email is invalid.
                            </div>
                        </div>
                        <!---Campo Senha--->
                        <div class="mb-3">
                            <div class="mb-2 w-100">
                                <label for="senha" class="text-muted">Senha</label>
                                <a href="forgot.html" class="float-end">Forgot Password?</a>
                            </div>
                            <input type="password" name="senha" id="" class="form-control" required>
                            <div class="invalid-feedback">
                                Password is required.
                            </div>
                        </div>
                        <!--Botão de Login-->
                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn btn-success ms-auto">Login</button>
                        </div>
                    </form>
                </div>
                <!--Link para Criar uma Conta--->
                <div class="card-footer py-3 border-0">
                    <div class="text-center">
                        Não tem nenhuma conta? <a href="cadastrar_conta.php">Crie uma.</a>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 text-muted">
                Copyright &copy 2026 &mdash; 3°B
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>