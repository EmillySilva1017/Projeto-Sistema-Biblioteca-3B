<?php session_start();
if (!isset($_SESSION['id_user'])) {
    header('Location: ../index.php');
    exit();
}
// Inclui a conexão para listar os funcionários caso seja o ADM
include '../includes/conexao.php';
/** @var mysqli $conn */
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
            <a href="../painel/painel_adm.php" class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center gap-2"
                style="border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i> <span>Voltar</span>
            </a>
        </div>
        
        <?php include('../includes/alerta.php'); ?>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 mb-4">

                <div class="card shadow-sm profile-card">
                    <div class="card-body text-center p-4 p-sm-5">

                        <div class="mb-3">
                            <i class="bi bi-person-circle text-success" style="font-size: 4.5rem;"></i>
                        </div>

                        <h3 class="fw-bold mb-1 text-dark text-truncate">
                            <?php echo htmlspecialchars($_SESSION['nome']); ?>
                        </h3>

                        <p class="text-muted small text-uppercase fw-semibold tracking-wider mb-4">
                            <?php echo ($_SESSION['nivel'] === 'adm') ? 'Administrador(a) Supremo(a)' : 'Bibliotecário(a)'; ?>
                        </p>

                        <div class="text-start bg-light p-3 rounded-3 mb-4 border-start border-success border-3">
                            <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.75rem;">E-mail
                                cadastrado</small>
                            <span class="text-dark fw-medium text-break">
                                <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'E-mail não informado'; ?>
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a class="btn btn-outline-success py-2 fw-semibold" style="border-radius: 10px;"
                                href="alterar_senha.php">
                                <i class="bi bi-key me-2"></i> Alterar Senha
                            </a>
                            <a href="../includes/logout.php" class="btn btn-outline-danger py-2 fw-semibold"
                                style="border-radius: 10px;">
                                <i class="bi bi-box-arrow-right me-2"></i> Encerrar Sessão
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            <?php if ($_SESSION['nivel'] === 'adm'): ?>
                <div class="col-12 col-lg-7 mb-4">
                    <div class="card shadow-sm profile-card border-0 h-100">
                        <div class="card-body p-4">

                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-4">
                                <div>
                                    <h4 class="fw-bold text-success mb-1">Controle de Equipe</h4>
                                    <p class="text-muted small mb-0">Gerencie contas de Administradores e Bibliotecários.
                                    </p>
                                </div>
                                <a href="cadastrar_conta.php" class="btn btn-warning btn-sm fw-bold text-white px-3 py-2"
                                    style="border-radius: 10px;">
                                    <i class="bi bi-person-plus-fill me-1"></i> Cadastrar Novo
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle" style="font-size: 0.9rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome</th>
                                            <th>Cargo</th>
                                            <th>Email</th>
                                            <th class="text-center">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql_users = "SELECT id_user, nome_user, email, nivel FROM usuario ORDER BY nome_user ASC";
                                        $result_users = mysqli_query($conn, $sql_users);

                                        if ($result_users && mysqli_num_rows($result_users) > 0):
                                            while ($user = mysqli_fetch_assoc($result_users)):
                                                ?>
                                                <tr>
                                                    <td class="fw-semibold text-dark text-truncate" style="max-width: 150px;">
                                                        <?= htmlspecialchars($user['nome_user']); ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($user['nivel'] === 'adm'): ?>
                                                            <span
                                                                class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">Admin</span>
                                                        <?php else: ?>
                                                            <span
                                                                class="badge bg-info-subtle text-info rounded-pill px-2 py-1">Bibliotecário</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($user['email']); ?></td>
                                                    <td class="text-center">
                                                        <a href="resetar_senha.php?id=<?= $user['id_user']; ?>"
                                                            class="btn btn-sm btn-success rounded-3 px-2 py-1"
                                                            onclick="return confirm('Tem certeza que deseja resetar a senha deste usuário?');"
                                                            title="Resetar Senha para o padrão">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </a>
                                                        <a href="editar_usuario.php?id=<?= $user['id_user']; ?>"
                                                            class="btn btn-sm btn-primary rounded-3 px-2 py-1"
                                                            title="Editar Funcionário">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>

                                                        <a href="deletar_usuario.php?id=<?= $user['id_user']; ?>"
                                                            class="btn btn-sm btn-danger rounded-3 px-2 py-1"
                                                            title="Remover Funcionário"
                                                            onclick="return confirm('Tem certeza que deseja remover o acesso deste funcionário?');">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                            endwhile;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Nenhum funcionário
                                                    encontrado.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>