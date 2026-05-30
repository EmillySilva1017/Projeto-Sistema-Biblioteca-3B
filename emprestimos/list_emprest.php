<?php session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Lógica para STATUS "Atrasado"
date_default_timezone_set('America/Fortaleza');
$hoje_formatado = date('Y-m-d');

// Query que atualiza para "Atrasado" se passou da data prevista e não foi entregue
$sql_atualiza_atrasados = "UPDATE emprestimos 
                           SET status = 'Atrasado' 
                           WHERE (status = 'Pendente' OR status = 'Renovado') 
                           AND data_prevista < '$hoje_formatado'";

mysqli_query($conn, $sql_atualiza_atrasados);

// --- 1. CONFIGURAÇÃO DA PAGINAÇÃO ---
$itens_por_pagina = 8;
$pagina_atual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

// --- 2. CAPTURA DOS FILTROS ---
$busca  = isset($_GET['busca']) ? mysqli_real_escape_string($conn, $_GET['busca']) : '';
$turma_filtro = isset($_GET['turma']) ? intval($_GET['turma']) : 0;
$status_filtro = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// --- 3. CONSTRUÇÃO DA QUERY COM FILTROS DINÂMICOS ---
$condicoes = ["1=1"]; // Condição base para facilitar concatenação

if (!empty($busca)) {
    $condicoes[] = "(e.nome_aluno LIKE '%$busca%' OR l.titulo_livro LIKE '%$busca%' OR l.numero_registry LIKE '%$busca%')";
}
if ($turma_filtro > 0) {
    $condicoes[] = "e.fk_id_turma = $turma_filtro";
}
if (!empty($status_filtro)) {
    $condicoes[] = "e.status = '$status_filtro'";
}

$where_clause = implode(" AND ", $condicoes);

// --- 4. CONTA O TOTAL DE REGISTROS FILTRADOS ---
$sql_total = "SELECT COUNT(*) as total FROM emprestimos e 
              INNER JOIN livros l ON e.fk_id_livro = l.id 
              WHERE $where_clause";
$res_total = mysqli_query($conn, $sql_total);
$row_total = mysqli_fetch_assoc($res_total);
$total_registros = $row_total['total'];
$total_paginas = ceil($total_registros / $itens_por_pagina);

// --- 5. BUSCA OS DADOS RELACIONADOS PARA A PÁGINA ATUAL ---
$sql_dados = "SELECT 
                e.id_emprestimos, e.nome_aluno, e.data_saida,
                e.data_prevista, e.data_devolucao, e.status,
                i.nome_user, l.numero_registro, l.titulo_livro,
                CONCAT(t.serie_atual, 'º ', t.identificador_curso, ' - ', t.curso) AS nome_turma
              FROM emprestimos e
              INNER JOIN livros l ON e.fk_id_livro = l.id
              INNER JOIN turmas t ON e.fk_id_turma = t.id_turma
              INNER JOIN usuario i ON e.fk_id_user = i.id_user
              WHERE $where_clause
              ORDER BY e.data_saida DESC 
              LIMIT $itens_por_pagina OFFSET $offset";
$res_dados = mysqli_query($conn, $sql_dados);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimos</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="list.css">
</head>

<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container-fluid px-4 pt">

        <div class="row align-items-center mt-3 mb-3">
            <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h2 class="fw-bold text-dark mb-0">Gestão de Empréstimos</h2>
            </div>
            <div class="col-12 col-md-6 text-center mt-3 text-md-end">
                <a href="cadastro_emprest.php" class="btn btn-success btn-mobile-full px-4 py-2 fw-bold rounded-3 shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i> Novo Empréstimo
                </a>
            </div>
        </div>

        <!--- Filtros e Botão de Cadastrar Empréstimo--->
            <form action="" method="GET" class="row g-3 align-items-end mb-4">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <input type="text" name="busca" class="form-control input-custom ps-5"    
                    placeholder="Pesquisar por Aluno, Titulo ou Registro..." value="<?= htmlspecialchars($busca) ?>">
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <select name="turma" class="form-select form-control-custom">
                            <option value="0">Todas as Turmas</option>
                            <?php 
                            $sql_t = "SELECT id_turma, CONCAT(serie_atual, 'º ', identificador_curso, ' - ', curso) as t_nome FROM turmas ORDER BY serie_atual, identificador_curso";
                            $res_t = mysqli_query($conn, $sql_t);
                            while($t = mysqli_fetch_assoc($res_t)):
                            ?>
                                <option value="<?= $t['id_turma'] ?>" <?= $turma_filtro == $t['id_turma'] ? 'selected' : '' ?>><?= $t['t_nome'] ?></option>
                            <?php endwhile; ?>
                        </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <select name="status" class="form-select form-control-custom">
                            <option value="">Todos os Status</option>
                            <option value="Pendente" <?= $status_filtro == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="Renovado" <?= $status_filtro == 'Renovado' ? 'selected' : '' ?>>Renovado</option>
                            <option value="Atrasado" <?= $status_filtro == 'Atrasado' ? 'selected' : '' ?>>Atrasado</option>
                            <option value="Entregue" <?= $status_filtro == 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                        </select>
                    </div>
                <div class="col-12 col-sm-6 col-md-12 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-filtrar flex-grow-1">
                            <i class="bi bi-funnel-fill me-2"></i> Filtrar
                        </button>
                        <?php if(!empty($busca) || $turma_filtro > 0 || !empty($status_filtro)): ?>
                            <a href="list_emprest.php" class="btn d-inline-flex align-items-center justify-content-center rounded-3 px-3" style="height: 45px;" title="Limpar Filtros">
                                <i class="bi bi-x-circle fs-5"></i>
                            </a>
                        <?php endif; ?>
                </div>
            </form>

        <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['mensagem']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensagem']); endif; ?>

        <div class="table-container shadow-sm mb-4">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    
                    <colgroup>
                        <col class="col-registro">
                        <col class="col-obra">
                        <col class="col-aluno">
                        <col class="col-turma">
                        <col class="col-datas"> <col class="col-datas"> <col class="col-datas"> <col class="col-status">
                        <col class="col-acoes">
                    </colgroup>

                    <thead class="thead-verde text-center">
                        <tr>
                            <th>N° Reg.</th>
                            <th>Obra / Título</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Saída</th>
                            <th>Prevista</th>
                            <th>Devolução</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($res_dados) == 0): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Nenhum empréstimo encontrado para os filtros aplicados.</td>
                            </tr>
                        <?php else: 
                            while($row = mysqli_fetch_assoc($res_dados)):
                                $dt_saida = date('d/m/Y', strtotime($row['data_saida']));
                                $dt_prevista = date('d/m/Y', strtotime($row['data_prevista']));
                                $dt_devolucao = !empty($row['data_devolucao']) ? date('d/m/Y H:i', strtotime($row['data_devolucao'])) : '-';
                                
                                $badge_class = 'bg-secondary';
                                if($row['status'] == 'Pendente') $badge_class = 'bg-primary';
                                if($row['status'] == 'Renovado') $badge_class = 'bg-info text-dark';
                                if($row['status'] == 'Atrasado') $badge_class = 'bg-danger';
                                if($row['status'] == 'Entregue') $badge_class = 'bg-success';
                        ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $row['numero_registro'] ?></td>
                                
                                <td class="fw-bold text-dark">
                                    <span class="text-truncate-custom" title="<?= htmlspecialchars($row['titulo_livro']) ?>">
                                        <?= htmlspecialchars($row['titulo_livro']) ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <span class="text-truncate-custom" title="<?= htmlspecialchars($row['nome_aluno']) ?>">
                                        <?= htmlspecialchars($row['nome_aluno']) ?>
                                    </span>
                                </td>
                                
                                <td class="text-center text-muted small">
                                    <span class="text-truncate-custom" title="<?= $row['nome_turma'] ?>">
                                        <?= $row['nome_turma'] ?>
                                    </span>
                                </td>
                                
                                <td class="text-center small"><?= $dt_saida ?></td>
                                <td class="text-center fw-bold small"><?= $dt_prevista ?></td>
                                <td class="text-center text-muted small"><?= $dt_devolucao ?></td>
                                <td class="text-center">
                                    <span class="badge <?= $badge_class ?> px-2 py-2 rounded-pill w-100" style="max-width: 95px; font-size: 0.75rem;">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($row['status'] != 'Entregue'): ?>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="acoes_emprest.php?acao=devolver&id=<?= $row['id_emprestimos'] ?>" class="btn btn-sm btn-success px-2" title="Devolver Livro">
                                                <i class="bi bi-check-lg"></i>
                                            </a>
                                            <a href="acoes_emprest.php?acao=renovar&id=<?= $row['id_emprestimos'] ?>" class="btn btn-sm btn-warning text-dark px-2" title="Renovar Prazo">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-success small fw-bold" style="font-size: 0.8rem;"><i class="bi bi-cloud-check-fill"></i> Ok</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginação -->
        <?php if ($total_paginas > 1): ?>
            <div class="d-flex justify-content-center align-items-center my-4 gap-2">
                
                <a href="?pagina=<?= $pagina_atual - 1 ?>&busca=<?= urlencode($busca) ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>"
                   class="seta-paginacao <?= ($pagina_atual <= 1) ? 'desativada' : '' ?>" title="Página Anterior">
                    <i class="bi bi-caret-left-fill"></i>
                </a>

                <?php
                // Configuração matemática para exibir no máximo 5 bolinhas centrais
                $max_bolinhas = 5; 
            
                $inicio = max(1, $pagina_atual - floor($max_bolinhas / 2));
                $fim = min($total_paginas, $inicio + $max_bolinhas - 1);

                // Ajusta o início caso o fim tenha encostado no limite máximo de páginas
                if ($fim - $inicio + 1 < $max_bolinhas) {
                    $inicio = max(1, $fim - $max_bolinhas + 1);
                }

                // Renderiza as bolinhas dinâmicas mantendo os filtros ativos na URL
                for ($i = $inicio; $i <= $fim; $i++): ?>
                    <a href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>"
                       class="paginacao-link <?= ($i == $pagina_atual) ? 'ativa' : '' ?>" title="Página <?= $i ?>">
                        <i class="bi bi-circle-fill"></i>
                    </a>
                <?php endfor; ?>

                <a href="?pagina=<?= $pagina_atual + 1 ?>&busca=<?= urlencode($busca) ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>"
                   class="seta-paginacao <?= ($pagina_atual >= $total_paginas) ? 'desativada' : '' ?>" title="Próxima Página">
                    <i class="bi bi-caret-right-fill"></i>
                </a>

            </div>
        <?php endif; ?>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>