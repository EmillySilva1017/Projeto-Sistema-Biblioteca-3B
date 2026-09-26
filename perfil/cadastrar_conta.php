<?php
session_start();
include('../includes/conexao.php');
require_once('../includes/verifica_admin.php');
/** @var mysqli $conn */
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../emprestimos/cadastro.css">
</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <?php include('../includes/alerta.php'); ?>

                <div class="card card-cadastro">
                    <div
                        class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-person-plus-fill me-2"></i> Cadastrar Novo
                                Funcionário</h4>
                            <p class="small text-white-50 mb-0">Adicione um novo membro à equipe da biblioteca.</p>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="cadastro.php" method="POST" class="needs-validation" novalidate=""
                            autocomplete="off">
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Nome Completo</label>
                                    <input type="text" name="nome" class="form-control border-2"
                                        placeholder="Ex: Maria Silva" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">E-mail Institucional</label>
                                    <input type="email" name="email" class="form-control border-2"
                                        placeholder="nome@escola.ce.gov.br" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Nível de Acesso</label>
                                    <select name="nivel_novo" class="form-select border-2" required>
                                        <option value="" disabled selected>Escolha o nível de acesso...</option>
                                        <option value="bibliotecario">Bibliotecário</option>
                                        <option value="adm">Administrador (Acesso Total)</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Senha Provisória</label>
                                    <input type="password" name="senha" class="form-control border-2"
                                        placeholder="••••••••" required>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-salvar btn-lg px-5 shadow">Cadastrar</button>
                                    <a href="perfil.php"
                                        class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>