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
                <h3 class="title">Cadastrar Novo Funcionário</h3>
                <p class="subtitle">Adicione um novo membro à equipa da biblioteca</p>

                <?php include '../includes/alerta.php'; ?>

                <form action="cadastro.php" method="POST" class="needs-validation" novalidate="" autocomplete="off">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="nome" class="form-control" placeholder="Nome completo" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="E-mail Institucional"
                            required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                        <select name="nivel_novo" class="form-select" required
                            style="border-left: none; border-radius: 10px; border: 2px solid #ddd;">
                            <option value="" disabled selected>Escolha o nível de acesso...</option>
                            <option value="bibliotecario">Bibliotecário</option>
                            <option value="adm">Administrador (Acesso Total)</option>
                        </select>
                    </div>

                    <div class="input-group mb-4">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="senha" class="form-control" placeholder="Senha Provisória"
                            required>
                    </div>

                    <div class="align-items-center d-flex mb-3">
                        <button type="submit" class="btn-main">Criar Conta de Funcionário</button>
                    </div>
                </form>

                <div class="mt-3 link">
                    <a href="perfil.php" class="text-muted small text-decoration-none"><i
                            class="bi bi-arrow-left"></i> Voltar para o Painel Geral</a>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>