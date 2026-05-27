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
$itens_por_pagina = 15;
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

// Carrega as turmas para o select do filtro
$sql_all_turmas = "SELECT id_turma, curso, identificador_curso, serie_atual FROM turmas ORDER BY serie_atual ASC";
$res_all_turmas = mysqli_query($conn, $sql_all_turmas);

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

    <!--- Filtros e Botão de Cadastrar Empréstimo--->
    <div class="container-fluid px-4 pt">

        <form action="" method="GET" class="row g-3 mb-3 mt-1 align-items-end">
            <div class="col-12 col-md-3 position-relative">
                <input type="text" name="busca" class="form-control input-custom ps-5"
                    placeholder="Pesquisar por nome, turma ou registro..." value="<?= htmlspecialchars($busca) ?>">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted"></i>
            </div>
            <div class="col-12 col-md-3">
                <select name="turma" class="form-select input-custom">
                    <option value="">Filtrar por Turma</option>
                    <?php while($t = mysqli_fetch_assoc($res_all_turmas)): ?>
                        <option value="<?= $t['id_turma'] ?>" <?= $turma_filtro == $t['id_turma'] ? 'selected' : '' ?>>
                            <?= $t['serie_atual'] ?>º <?= $t['identificador_curso'] ?> - <?= $t['curso'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select input-custom">
                    <option value="">Filtrar por Status</option>
                    <option value="Pendente" <?= $status_filtro == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="Entregue" <?= $status_filtro == 'Entregue' ? 'selected' : '' ?>>Entregue</option>
                    <option value="Atrasado" <?= $status_filtro == 'Atrasado' ? 'selected' : '' ?>>Atrasado</option>
                    <option value="Renovado" <?= $status_filtro == 'Renovado' ? 'selected' : '' ?>>Renovado</option>
                </select>
            </div>
            <div class="col-12 col-lg-2 text-lg-end">
                <button type="submit" class="btn btn-filtrar w-100 shadow-sm fw-bold">
                    Filtrar </button>
            </div>
            <div class="col-12 col-lg-2 text-lg-end">
                <a href="cadastro_emprest.php" class="btn btn-success w-100 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg"></i> Novo Empréstimo
                </a>
            </div>
        </form>

        <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['mensagem']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensagem']); endif; ?>

        <div class="mb-2">
            <h2 class="fw-bold text-dark">Gestão de Empréstimos</h2>
        </div>
        <div class="table-container shadow-sm mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="thead-verde text-center">
                        <tr>
                            <th>N° Registro</th>
                            <th>Obra</th>
                            <th>Aluno</th>
                            <th>Turma</th>
                            <th>Data Saída</th>
                            <th>Data Prevista</th>
                            <th>Data Devolução</th>
                            <th>Biblio</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($res_dados) == 0): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Nenhum empréstimo encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php while($row = mysqli_fetch_assoc($res_dados)): 
                                // Formata as datas para exibição brasileira
                                $dt_saida = date('d/m/Y', strtotime($row['data_saida']));
                                $dt_prevista = date('d/m/Y', strtotime($row['data_prevista']));
                                $dt_devolucao = !empty($row['data_devolucao']) ? date('d/m/Y', strtotime($row['data_devolucao'])) : '-';
                                
                                // Define a cor do Badge baseado no Status
                                $badge_class = 'bg-warning text-dark';
                                if($row['status'] == 'Entregue') $badge_class = 'bg-success';
                                if($row['status'] == 'Atrasado') $badge_class = 'bg-danger';
                                if($row['status'] == 'Renovado') $badge_class = 'bg-info text-dark';
                            ?>
                            <tr>
                                    <td class="text-center fw-bold"><?= $row['numero_registro'] ?></td>
                                    <td><?= $row['titulo_livro'] ?></td>
                                    <td><?= $row['nome_aluno'] ?></td>
                                    <td><?= $row['nome_turma'] ?></td>
                                    <td class="text-center"><?= $dt_saida ?></td>
                                    <td class="text-center"><?= $dt_prevista ?></td>
                                    <td class="text-center"><?= $dt_devolucao ?></td>
                                    <td class="text-center"><?= $row['nome_user'] ?></td>
                                    <td class="text-center"><span class="badge <?= $badge_class ?>"><?= $row['status'] ?></span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if($row['status'] != 'Entregue'): ?>
                                                <a href="acoes_emprest.php?acao=devolver&id=<?= $row['id_emprestimos'] ?>" 
                                                   class="btn btn-success" title="Dar Devolução" onclick="return confirm('Confirmar devolução deste livro?')">
                                                    <i class="bi bi-arrow-bar-up"></i>
                                                </a>
                                                <a href="acoes_emprest.php?acao=renovar&id=<?= $row['id_emprestimos'] ?>" 
                                                   class="btn btn-orange text-dark" title="Renovar +7 dias" onclick="return confirm('Deseja renovar este empréstimo por mais 7 dias?')">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="acoes_emprest.php?acao=excluir&id=<?= $row['id_emprestimos'] ?>" 
                                               class="btn btn-danger" title="Excluir Histórico" onclick="return confirm('Tem certeza que deseja apagar permanentemente este registro do histórico?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginação -->
         <?php if($total_paginas > 1): ?>
            <nav aria-label="Navegação de página">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $pagina_atual <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina_atual - 1 ?>&busca=<?= $busca ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>">Anterior</a>
                    </li>
                    <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $pagina_atual == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&busca=<?= $busca ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $pagina_atual >= $total_paginas ? 'disabled' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $pagina_atual + 1 ?>&busca=<?= $busca ?>&turma=<?= $turma_filtro ?>&status=<?= $status_filtro ?>">Próximo</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>