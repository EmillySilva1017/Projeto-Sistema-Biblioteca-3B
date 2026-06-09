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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | Manoteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .profile-card {
            border-radius: 16px;
            border: none;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-4 px-3">
        
        <div class="mb-4">
            <a href="painel_adm.php" class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i> <span>Voltar</span>
            </a>
        </div>
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['msg']); 
              unset($_SESSION['msg_tipo']); ?> 
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5">
                
                <div class="card shadow-sm profile-card">
                    <div class="card-body text-center p-4 p-sm-5">
                        
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-success" style="font-size: 4.5rem;"></i>
                        </div>
                        
                        <h3 class="fw-bold mb-1 text-dark text-truncate"><?php echo htmlspecialchars($_SESSION['nome']); ?></h3>
                        <p class="text-muted small text-uppercase fw-semibold tracking-wider mb-4">Bibliotecário(a)</p>

                        <div class="text-start bg-light p-3 rounded-3 mb-4 border-start border-success border-3">
                            <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.75rem;">E-mail cadastrado</small>
                            <span class="text-dark fw-medium text-break">
                                <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'E-mail não informado'; ?>
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a class="btn btn-outline-success py-2 fw-semibold" style="border-radius: 10px;" href="alterar_senha.php">
                                <i class="bi bi-key me-2"></i> Alterar Senha
                            </a>
                            <a href="../includes/logout.php" class="btn btn-outline-danger py-2 fw-semibold" style="border-radius: 10px;">
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