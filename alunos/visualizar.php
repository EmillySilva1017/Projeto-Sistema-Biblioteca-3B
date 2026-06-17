<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Configuração da paginação
$itens_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina_atual < 1) {
    $pagina_atual = 1;
}
$offset = ($pagina_atual - 1) * $itens_por_pagina;

// Inicialização das variáveis de busca e filtros
$busca = isset($_GET['busca']) ? mysqli_real_escape_string($conn, $_GET['busca']) : "";
$curso = isset($_GET['curso']) ? mysqli_real_escape_string($conn, $_GET['curso']) : "";
$turma_id = isset($_GET['turma']) ? mysqli_real_escape_string($conn, $_GET['turma']) : "";

$filtro_ativo = !empty($busca) || !empty($curso) || !empty($turma_id);

$condicoes = " WHERE 1=1";

if (!empty($busca)) {
    $condicoes .= " AND (a.nome_aluno LIKE '%$busca%' OR a.matricula LIKE '%$busca%')";
}
if (!empty($curso)) {
    $condicoes .= " AND t.curso = '$curso'";
}
if (!empty($turma_id)) {
    $condicoes .= " AND a.fk_id_turma = '$turma_id'";
}

// 2. QUERY PARA CONTAR O TOTAL DE REGISTROS (Otimizado para a paginação)
$sql_count = "SELECT COUNT(*) as total 
              FROM alunos a 
              INNER JOIN turmas t ON a.fk_id_turma = t.id_turma" . $condicoes;
$res_count = mysqli_query($conn, $sql_count);
$total_registros = mysqli_fetch_assoc($res_count)['total'] ?? 0;
$total_paginas = max(1, ceil($total_registros / $itens_por_pagina));

// Ajusta a página caso os filtros retornem menos páginas do que a atual
if ($pagina_atual > $total_paginas) {
    $pagina_atual = $total_paginas;
    $offset = ($pagina_atual - 1) * $itens_por_pagina;
}

// 3. QUERY PRINCIPAL (Com a sua lógica de subquery para checar pendências + LIMIT/OFFSET)
$sql = "SELECT a.*, t.serie_atual, t.curso, t.identificador_curso
        FROM alunos a 
        INNER JOIN turmas t ON a.fk_id_turma = t.id_turma" .
    $condicoes . " ORDER BY t.serie_atual, t.identificador_curso ASC LIMIT $itens_por_pagina OFFSET $offset";

$resAlunos = mysqli_query($conn, $sql);

// Busca os cursos e turmas para alimentar os selects dos filtros
$sqlCursos = "SELECT DISTINCT curso FROM turmas ORDER BY curso DESC";
$resCursos = mysqli_query($conn, $sqlCursos);

$sqlTodasTurmas = "SELECT id_turma, serie_atual, identificador_curso, curso FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$resTodasTurmas = mysqli_query($conn, $sqlTodasTurmas);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="alunos.css">
    <link rel="stylesheet" href="botoes.css">
</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <div class="container-fluid mt-4 px-3 px-sm-4">
        <div class="row align-items-center mt-3 mb-4 g-3">
            <div class="col-12 col-md-6 text-center text-md-start">
                <h2 class="fw-bold text-dark mb-0">Gestão de Alunos</h2>
            </div>

            <div class="col-12 col-md-6">
                <div class="d-flex flex-column flex-sm-row justify-content-md-end gap-2">
                    <a href="importar_alunos.php"
                        class="btn btn-outline-info fw-bold d-inline-flex align-items-center justify-content-center shadow-sm px-4 py-2"
                        style="height: 45px; border-radius: 10px;">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i>IMPORTAR PLANILHA
                    </a>
                    <a href="cadastro_aluno.php"
                        class="btn btn-success fw-bold d-inline-flex align-items-center justify-content-center shadow-sm px-4 py-2"
                        style="height: 45px; border-radius: 10px;">
                        <i class="bi bi-plus-lg me-2"></i>CADASTRAR ALUNO
                    </a>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['msg']);
        endif; ?>

        <form action="" method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-12 col-md-4 position-relative">
                <input type="text" name="busca" class="form-control form-control-custom ps-5"
                    placeholder="Pesquisar por nome ou matrícula..." value="<?= htmlspecialchars($busca) ?>">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted"></i>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <select name="curso" class="form-select form-control-custom">
                    <option value="">Todos os Cursos</option>
                    <?php while ($c = mysqli_fetch_assoc($resCursos)): ?>
                        <option value="<?= $c['curso'] ?>" <?= ($curso == $c['curso']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['curso']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-2">
                <select name="turma" class="form-select form-control-custom">
                    <option value="">Todas as Turmas</option>
                    <?php while ($t = mysqli_fetch_assoc($resTodasTurmas)): ?>
                        <option value="<?= $t['id_turma'] ?>" <?= ($turma_id == $t['id_turma']) ? 'selected' : '' ?>>
                            <?= $t['serie_atual'] ?>º ano - <?= htmlspecialchars($t['identificador_curso']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-filtrar flex-grow-1 btn-mobile-full">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>

                    <?php if ($filtro_ativo): ?>
                        <a href="visualizar.php" class="btn btn-limpar btn-mobile-full text-nowrap"
                            title="Limpar todos os filtros">
                            <i class="bi bi-x-circle me-1"></i>Limpar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        <?php if (mysqli_num_rows($resAlunos) > 0): ?>
            <div class="table-container shadow-sm mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0">
                        <thead class="thead-verde text-center">
                            <tr>
                                <th class="py-3">Matrícula</th>
                                <th class="py-3">Nome</th>
                                <th class="py-3">Série</th>
                                <th class="py-3">Curso</th>
                                <th class="py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($aluno = mysqli_fetch_assoc($resAlunos)): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $aluno['matricula'] ?></td>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                                    <td class="text-center">
                                        <?= $aluno['serie_atual'] ?>º <?= $aluno['identificador_curso'] ?>
                                    </td>
                                    <td class="text-start small text-muted"><?= $aluno['curso'] ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="editar_aluno.php?id=<?= $aluno['id_aluno'] ?>"
                                                class="btn btn-sm btn-warning px-2">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="excluir_aluno.php?id=<?= $aluno['id_aluno'] ?>"
                                                class="btn btn-sm btn-danger px-2"
                                                onclick="return confirm('Deseja excluir este aluno? Esta ação não pode ser desfeita!')">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if ($total_paginas > 1): ?>
                <div class="d-flex justify-content-center align-items-center my-4 gap-2">
                    <a href="?busca=<?= urlencode($busca) ?>&curso=<?= urlencode($curso) ?>&turma=<?= urlencode($turma_id) ?>&pagina=<?= $pagina_atual - 1 ?>"
                        class="seta-paginacao <?= ($pagina_atual <= 1) ? 'desativada' : '' ?>">
                        <i class="bi bi-caret-left-fill"></i>
                    </a>

                    <?php
                    $max_bolinhas = 5;
                    $inicio = max(1, $pagina_atual - floor($max_bolinhas / 2));
                    $fim = min($total_paginas, $inicio + $max_bolinhas - 1);

                    if ($fim - $inicio + 1 < $max_bolinhas) {
                        $inicio = max(1, $fim - $max_bolinhas + 1);
                    }

                    for ($i = $inicio; $i <= $fim; $i++): ?>
                        <a href="?busca=<?= urlencode($busca) ?>&curso=<?= urlencode($curso) ?>&turma=<?= urlencode($turma_id) ?>&pagina=<?= $i ?>"
                            class="paginacao-link <?= ($i == $pagina_atual) ? 'ativa' : '' ?>" title="Página <?= $i ?>">
                            <i class="bi bi-circle-fill"></i>
                        </a>
                    <?php endfor; ?>

                    <a href="?busca=<?= urlencode($busca) ?>&curso=<?= urlencode($curso) ?>&turma=<?= urlencode($turma_id) ?>&pagina=<?= $pagina_atual + 1 ?>"
                        class="seta-paginacao <?= ($pagina_atual >= $total_paginas) ? 'desativada' : '' ?>">
                        <i class="bi bi-caret-right-fill"></i>
                    </a>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-warning text-center rounded-4 shadow-sm py-4 border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 d-block mb-2"></i>
                Nenhum aluno foi encontrado com os filtros aplicados.
            </div>
        <?php endif; ?>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>