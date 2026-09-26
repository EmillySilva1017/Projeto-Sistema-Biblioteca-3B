<?php
session_start();
require_once '../includes/verifica_aluno.php';
include '../includes/conexao.php';
/** @var mysqli $conn */

// 1. TRAVA DE SEGURANÇA
if (!isset($_SESSION['nome_aluno'])) {
    header('Location: ../login/login.php?role=aluno');
    exit();
}

$nome_aluno = mysqli_real_escape_string($conn, $_SESSION['nome_aluno']);
$ano_atual = date('Y');
$mes_atual = date('m');

// 2. CONSULTAS SQL PARA MÉTRICAS
$sql_mes = "SELECT COUNT(*) AS total FROM emprestimos 
            WHERE nome_aluno = '$nome_aluno' 
              AND status = 'entregue' 
              AND MONTH(data_devolucao) = '$mes_atual' 
              AND YEAR(data_devolucao) = '$ano_atual'";
$res_mes = mysqli_query($conn, $sql_mes);
$total_mes = ($res_mes) ? mysqli_fetch_assoc($res_mes)['total'] : 0;

$sql_ano = "SELECT COUNT(*) AS total FROM emprestimos 
            WHERE nome_aluno = '$nome_aluno' 
              AND status = 'entregue' 
              AND YEAR(data_devolucao) = '$ano_atual'";
$res_ano = mysqli_query($conn, $sql_ano);
$total_ano = ($res_ano) ? mysqli_fetch_assoc($res_ano)['total'] : 0;

$sql_ativos_count = "SELECT COUNT(*) AS total FROM emprestimos 
                     WHERE nome_aluno = '$nome_aluno' 
                       AND (status = 'pendente' OR status = 'atrasado')";
$res_ativos_count = mysqli_query($conn, $sql_ativos_count);
$total_ativos = ($res_ativos_count) ? mysqli_fetch_assoc($res_ativos_count)['total'] : 0;

// 3. EMPRÉSTIMOS ATIVOS
$sql_ativos = "SELECT e.*, l.titulo_livro, l.autor, l.genero
               FROM emprestimos e
               JOIN livros l ON e.fk_id_livro = l.id
               WHERE e.nome_aluno = '$nome_aluno' 
                 AND (e.status = 'pendente' OR e.status = 'atrasado')
               ORDER BY e.data_prevista ASC";
$res_ativos = mysqli_query($conn, $sql_ativos);

// 4. ÚLTIMAS DEVOLUÇÕES (RÁPIDO - MÁX 5)
$sql_recentes = "SELECT e.*, l.titulo_livro, l.autor, l.genero 
                 FROM emprestimos e
                 JOIN livros l ON e.fk_id_livro = l.id
                 WHERE e.nome_aluno = '$nome_aluno' 
                   AND e.status = 'entregue'
                 ORDER BY e.data_devolucao DESC LIMIT 5";
$res_recentes = mysqli_query($conn, $sql_recentes);

// 5. HISTÓRICO COMPLETO (PARA O MODAL)
$sql_historico_todos = "SELECT e.*, l.titulo_livro, l.autor, l.genero 
                        FROM emprestimos e
                        JOIN livros l ON e.fk_id_livro = l.id
                        WHERE e.nome_aluno = '$nome_aluno' 
                          AND e.status = 'entregue'
                        ORDER BY e.data_devolucao DESC";
$res_historico_todos = mysqli_query($conn, $sql_historico_todos);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno | ManoTeca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container py-2">

        <?php include '../includes/alerta.php'; ?>

        <div class="mb-4 mt-2">
            <h4 class="fw-bold m-0 text-dark">Bem-Vindo, <?= htmlspecialchars($_SESSION['nome_aluno']); ?>!</h4>
        </div>

        <!-- CARDS DE MÉTRICAS -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card-stat border-green d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">LIDOS ESTE MÊS</span>
                        <h2 class="fw-bold m-0 text-dark"><?= $total_mes; ?></h2>
                        <small class="text-muted"><i class="bi bi-calendar-check me-1"></i>Mês atual</small>
                    </div>
                    <div class="stat-icon bg-icon-green">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card-stat border-blue d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">TOTAL ESTE ANO</span>
                        <h2 class="fw-bold m-0 text-dark"><?= $total_ano; ?></h2>
                        <small class="text-muted"><i class="bi bi-trophy me-1"></i>Ano de <?= $ano_atual; ?></small>
                    </div>
                    <div class="stat-icon bg-icon-blue">
                        <i class="bi bi-award"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card-stat border-orange d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block mb-1">EMPRÉSTIMOS ATIVOS</span>
                        <h2 class="fw-bold m-0 text-dark"><?= $total_ativos; ?></h2>
                        <small class="text-muted"><i class="bi bi-book me-1"></i>Com você agora</small>
                    </div>
                    <div class="stat-icon bg-icon-orange">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEÇÕES PRINCIPAIS (EMPILHADAS E LARGURA TOTAL) -->
        <div class="row g-4">

            <!-- EMPRÉSTIMOS ATIVOS -->
            <div class="col-12">
                <div class="card-box">
                    <div class="box-header-green">
                        <span>Empréstimos Ativos</span>
                        <span class="badge bg-light text-dark rounded-pill px-3"><?= $total_ativos; ?> livro(s)</span>
                    </div>

                    <div class="p-3">
                        <?php if (mysqli_num_rows($res_ativos) > 0): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php while ($livro = mysqli_fetch_assoc($res_ativos)):
                                    $atrasado = (strtotime($livro['data_prevista']) < strtotime(date('Y-m-d')));
                                    $data_formatada = date('d/m/Y', strtotime($livro['data_prevista']));
                                    ?>
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 rounded-3 border bg-light">
                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">
                                                    <?= htmlspecialchars($livro['titulo_livro']); ?></h6>
                                                <p class="text-muted small mb-1"><?= htmlspecialchars($livro['autor']); ?></p>
                                                <span class="badge-genero"><?= htmlspecialchars($livro['genero']); ?></span>
                                            </div>
                                        </div>
                                        <div class="text-end ms-2">
                                            <span
                                                class="d-block small <?= $atrasado ? 'text-danger fw-bold' : 'text-muted'; ?>">
                                                <?= $atrasado ? 'Atrasado' : 'Devolução'; ?>
                                            </span>
                                            <small class="<?= $atrasado ? 'text-danger fw-bold' : 'text-dark fw-semibold'; ?>">
                                                <?= $data_formatada; ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-check-circle fs-1 d-block mb-2 text-success"></i>
                                <p class="m-0 fw-medium">Você não possui nenhum livro pendente para devolução!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ÚLTIMAS DEVOLUÇÕES -->
            <div class="col-12 mb-3">
                <div class="card-box">
                    <div class="box-header-green">
                        <span>Últimas Devoluções</span>
                        <button type="button"
                            class="btn btn-link text-white text-decoration-underline p-0 small border-0 fw-medium"
                            data-bs-toggle="modal" data-bs-target="#modalHistorico">
                            Ver Histórico Completo <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                    <div class="p-0">
                        <?php if (mysqli_num_rows($res_recentes) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-divided align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Título / Autor</th>
                                            <th>Gênero</th>
                                            <th class="text-end">Devolvido em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($hist = mysqli_fetch_assoc($res_recentes)):
                                            $data_devolucao = date('d/m/Y', strtotime($hist['data_devolucao']));
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark mb-0">
                                                        <?= htmlspecialchars($hist['titulo_livro']); ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($hist['autor']); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge-genero"><?= htmlspecialchars($hist['genero']); ?></span>
                                                </td>
                                                <td class="text-end text-dark fw-bold small">
                                                    <?= $data_devolucao; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <p class="m-0">Ainda não há histórico de devoluções registradas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- MODAL DO HISTÓRICO COMPLETO -->
    <div class="modal fade" id="modalHistorico" tabindex="-1" aria-labelledby="modalHistoricoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header text-white" style="background-color: #2d572c;">
                    <h5 class="modal-title fw-bold fs-6" id="modalHistoricoLabel">
                        <i class="bi bi-clock-history me-2"></i>Histórico Completo de Leituras
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <?php if (mysqli_num_rows($res_historico_todos) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-divided align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Título / Autor</th>
                                        <th>Gênero</th>
                                        <th class="text-end">Devolvido em</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($item = mysqli_fetch_assoc($res_historico_todos)):
                                        $data_conclusao = date('d/m/Y', strtotime($item['data_devolucao']));
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark mb-0">
                                                    <?= htmlspecialchars($item['titulo_livro']); ?>
                                                </div>
                                                <small class="text-muted"><?= htmlspecialchars($item['autor']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge-genero"><?= htmlspecialchars($item['genero']); ?></span>
                                            </td>
                                            <td class="text-end text-dark fw-bold small">
                                                <?= $data_conclusao; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                            <p class="m-0">Você ainda não devolveu nenhum livro.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-4 fw-medium"
                        data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>