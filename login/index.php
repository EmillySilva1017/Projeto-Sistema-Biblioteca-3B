<?php 
session_start();
include('../includes/conexao.php');

// Consulta se já existe pelo menos um administrador no sistema
$sql_check = "SELECT COUNT(*) AS total FROM usuario WHERE nivel = 'adm'";
$res_check = mysqli_query($conn, $sql_check);
$total_adms = ($res_check) ? mysqli_fetch_assoc($res_check)['total'] : 0;
?>

<?php if ($total_adms == 0): ?>
    <div class="alert alert-warning text-center mt-3" role="alert">
        <small class="d-block mb-1 fw-bold">Sistema não configurado!</small>
        <a href="cadastrar_master.php" class="btn btn-sm btn-warning fw-bold">Criar Administrador Master</a>
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Biblioteca - Portais de Acesso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="container py-5">
    <div class="text-center mb-4">
        <img src="../img/LOGO MANOTECA - CIRCULAR.png" alt="Logo Manoteca" class="logo-manoteca mb-3">
        
        <h2 class="fw-bold text-dark mb-2">Bem-vindo ao Portal da MANOTECA</h2>
        <p class="text-muted">Selecione o seu portal de acesso para continuar</p>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-12 col-md-4">
            <a href="login.php?role=aluno" class="card card-portal portal-aluno shadow-sm text-center p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="icon-circle">
                        <i class="bi bi-mortarboard fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Portal do Aluno</h5>
                    <p class="text-muted small mb-0">Consulte seus empréstimos, histórico e métricas do sistema.</p>
                </div>
                <div class="mt-4">
                    <span class="btn btn-sm btn-outline-primary rounded-pill px-4">Acessar</span>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="login.php?role=bibliotecario" class="card card-portal portal-biblio shadow-sm text-center p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="icon-circle">
                        <i class="bi bi-book-half fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Bibliotecário</h5>
                    <p class="text-muted small mb-0">Gerencie novos empréstimos, devoluções e cadastros de livros.</p>
                </div>
                <div class="mt-4">
                    <span class="btn btn-sm btn-outline-success rounded-pill px-4">Acessar</span>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-4">
            <a href="login.php?role=adm" class="card card-portal portal-adm shadow-sm text-center p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="icon-circle">
                        <i class="bi bi-shield-lock fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Administração</h5>
                    <p class="text-muted small mb-0">Controle total do sistema, relatórios avançados, turmas e usuários.</p>
                </div>
                <div class="mt-4">
                    <span class="btn btn-sm btn-outline-warning rounded-pill px-4">Acessar</span>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>