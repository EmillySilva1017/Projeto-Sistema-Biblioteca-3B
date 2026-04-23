<?php session_start();
if (!isset($_SESSION['id_user'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil | Manoteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="d-flex align-items-center mt-2 mb-4">
            <a href="index.php" class="btn btn-outline-secondary me-3 shadow-sm">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Voltar</span>
            </a>
        </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-person-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        
                        <h3 class="fw-bold mb-1"><?php echo $_SESSION['nome']; ?></h3>
                        <p class="text-muted mb-4">Bibliotecário(a)</p>

                        <div class="text-start bg-light p-3 rounded mb-4">
                            <small class="text-uppercase fw-bold text-muted d-block">E-mail cadastrado:</small>
                            <span><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'E-mail não informado'; ?></span>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-success" onclick="alert('Funcionalidade de trocar senha em breve!')">
                                <i class="bi bi-key me-2"></i> Alterar Senha
                            </button>
                            <a href="../includes/logout.php" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Encerrar Sessão
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>