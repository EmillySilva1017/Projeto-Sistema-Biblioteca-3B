<?php session_start();
include '../includes/conexao.php';

// Verifica se está logado e se é administrador (nível 1)
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] === 'aluno') {
    header('Location: ../login/index.php');
    exit();
}

setlocale(LC_TIME, 'pt_BR.utf-8', 'pt_BR', 'portuguese');
$nome_mes_atual = date('m/Y'); // Formato Mês/Ano para exibir no topo dos cards

### CARDS ####

# Consulta 1: Total do acervo e livros atualmente disponíveis na estante
$sql_total = "SELECT 
                COUNT(l.id) AS total_livros,
                (COUNT(l.id) - SUM(CASE WHEN e.status IN ('Pendente', 'Renovado', 'Atrasado') THEN 1 ELSE 0 END)) AS total_disponiveis
              FROM livros l
              LEFT JOIN emprestimos e ON l.id = e.fk_id_livro";

$result_total = mysqli_query($conn, $sql_total);
$total_livros = 0;
$total_disponiveis = 0;

if ($result_total) {
    $row = mysqli_fetch_assoc($result_total);
    $total_livros = (int) $row['total_livros'];
    $total_disponiveis = max(0, (int) $row['total_disponiveis']);
}

# Consulta 2: Gênero mais popular NO MÊS ATUAL
$top_genero = "SELECT l.genero, COUNT(e.id_emprestimos) AS total_emprest 
FROM emprestimos e
INNER JOIN livros l ON e.fk_id_livro = l.id
WHERE MONTH(e.data_saida) = MONTH(CURRENT_DATE()) 
  AND YEAR(e.data_saida) = YEAR(CURRENT_DATE())
GROUP BY l.genero 
ORDER BY total_emprest DESC 
LIMIT 1";
$result_genero = mysqli_query($conn, $top_genero);

$genero_popular = "Nenhum no mês";
$total_emprestimos = 0;

if ($result_genero && mysqli_num_rows($result_genero) > 0) {
    $row = mysqli_fetch_assoc($result_genero);
    $genero_popular = $row['genero'];
    $total_emprestimos = $row['total_emprest'];
}

# Consulta 3: Turma mais ativa NO MÊS ATUAL
$top_turma = "SELECT CONCAT(t.serie_atual, '° ', t.identificador_curso) AS turma, COUNT(e.id_emprestimos) AS total_turma
FROM emprestimos e
INNER JOIN turmas t ON e.fk_id_turma = t.id_turma 
WHERE MONTH(e.data_saida) = MONTH(CURRENT_DATE()) 
  AND YEAR(e.data_saida) = YEAR(CURRENT_DATE())
GROUP BY e.fk_id_turma
ORDER BY total_turma DESC 
LIMIT 1";
$result_turma = mysqli_query($conn, $top_turma);
$turma_popular = "Sem registros";
$total_leituras_turma = 0;

if ($result_turma && mysqli_num_rows($result_turma) > 0) {
    $row_turma = mysqli_fetch_assoc($result_turma);
    $turma_popular = $row_turma['turma'];
    $total_leituras_turma = $row_turma['total_turma'];
}

### GRÁFICO ###

# Consulta 4: Leituras por Turma NO MÊS ATUAL
$sql_grafico = "SELECT 
                    CONCAT(t.serie_atual, 'º ', t.identificador_curso) AS sigla_turma, 
                    COUNT(e.id_emprestimos) AS total_emprestimos 
                FROM turmas t
                LEFT JOIN emprestimos e ON t.id_turma = e.fk_id_turma 
                     AND MONTH(e.data_saida) = MONTH(CURRENT_DATE()) 
                     AND YEAR(e.data_saida) = YEAR(CURRENT_DATE())
                GROUP BY t.id_turma
                ORDER BY t.serie_atual ASC, t.identificador_curso ASC";

$result_grafico = mysqli_query($conn, $sql_grafico);

$labels_turmas = [];
$dados_leituras = [];

while ($row = mysqli_fetch_assoc($result_grafico)) {
    $labels_turmas[] = $row['sigla_turma'];
    $dados_leituras[] = (int) $row['total_emprestimos'];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador - ManoTeca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php include '../includes/menu.php'; ?>

    <main class="container-fluid px-4 py-4">
        <!-- Cabeçalho Informativo Ajustado para Mobile e Desktop -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Painel de Controle</h4>
                <p class="text-muted small mb-0">Visão geral do acervo e métricas de uso da biblioteca escolar.</p>
            </div>
            <span
                class="badge bg-white text-secondary border px-3 py-2 shadow-sm rounded-pill fs-7 align-self-start align-self-md-auto">
                <i class="bi bi-calendar3 me-1 text-success"></i> Mês Ref: <strong><?= $nome_mes_atual; ?></strong>
            </span>
        </div>

        <section>
            <div class="row">
                <!-- Consulta 1: Total do acervo -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card card-dashboard border-livros shadow-sm h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <span class="text-muted-dashboard fw-bold">Livros Disponíveis</span>
                                <h3 class="fw-bold text-dark mb-1"><?= $total_disponiveis; ?></h3>
                                <p class="text-muted small mb-0">Total no acervo: <span
                                        class="fw-semibold text-secondary"><?= $total_livros; ?></span></p>
                            </div>
                            <div class="icon-shape ms-3">
                                <i class="bi bi-book-half text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consulta 2: Gênero Popular -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card card-dashboard border-genero shadow-sm h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <span class="text-muted-dashboard fw-bold">Gênero do Mês</span>
                                <h4 class="fw-bold text-dark my-1 text-truncate" style="max-width: 200px;"
                                    title="<?php echo $genero_popular; ?>">
                                    <?php echo $genero_popular; ?>
                                </h4>
                                <p class="text-muted small mb-0"><i class="bi bi-graph-up-arrow text-warning"></i>
                                    <?php echo $total_emprestimos; ?> leituras este mês</p>
                            </div>
                            <div class="icon-shape ms-3">
                                <i class="bi bi-bookmark-star fs-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Consulta 3: Turma Mais Ativa -->
                <div class="col-12 col-md-4 mb-4">
                    <div class="card card-dashboard border-turma shadow-sm h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <span class="text-muted-dashboard fw-bold">Turma Mais Ativa (Mês)</span>
                                <h4 class="fw-bold text-dark my-1 text-truncate" style="max-width: 200px;"
                                    title="<?php echo $turma_popular; ?>">
                                    <?php echo $turma_popular; ?>
                                </h4>
                                <p class="text-muted small mb-0"><i class="bi bi-people text-primary"></i> Total:
                                    <?php echo $total_leituras_turma; ?> empréstimos
                                </p>
                            </div>
                            <div class="icon-shape ms-3">
                                <i class="bi bi-mortarboard fs-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seção de Índice por Turma (Híbrida: Lista no Mobile / Gráfico no Desktop) -->
        <section class="row mb-4">
            <div class="col-12">
                <div class="card card-dashboard shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-bar-chart-fill text-success me-2"></i> Empréstimos por Turma no Mês
                        </h5>
                        <small class="text-muted d-none d-md-inline">
                            <i class="bi bi-info-circle me-1"></i> Dados do mês corrente
                        </small>
                    </div>

                    <!-- VISÃO DESKTOP: Gráfico de Barras (Oculto em telas menores que 'md') -->
                    <div class="chart-container d-none d-md-block">
                        <canvas id="graficoLeituraTurmas"></canvas>
                    </div>

                    <!-- VISÃO MOBILE: Lista de Ranking (Exibida apenas no celular) -->
                    <div class="d-block d-md-none">
                        <?php
                        // Descobre o maior valor para calcular a porcentagem da barra de progresso
                        $max_leituras = count($dados_leituras) > 0 ? max($dados_leituras) : 1;
                        if ($max_leituras == 0)
                            $max_leituras = 1;

                        foreach ($labels_turmas as $index => $turma):
                            $qtd = $dados_leituras[$index];
                            $porcentagem = round(($qtd / $max_leituras) * 100);
                            $e_lider = ($qtd === $max_leituras && $qtd > 0);
                            ?>
                            <div
                                class="mb-3 p-2 rounded bg-light border-start border-4 <?= $e_lider ? 'border-warning' : 'border-success' ?>">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">
                                        <?= $turma; ?>
                                        <?php if ($e_lider): ?>
                                            <span class="badge bg-warning text-dark ms-1">Líder</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="badge bg-white text-dark border fw-bold">
                                        <?= $qtd; ?> livro(s)
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar <?= $e_lider ? 'bg-warning' : 'bg-success' ?>"
                                        role="progressbar" style="width: <?= $porcentagem; ?>%;">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </section>

        <!-- Seção de Alertas/Atrasos -->
        <section class="row mb-4">
            <div class="col-12">
                <div class="card card-dashboard shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Resumo de Livros Atrasados
                            por Turma
                        </h5>
                        <a href="../emprestimos/list_emprest.php" class="btn btn-sm btn-outline-danger">
                            Gerenciar Empréstimos
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="thead-pers text-center">
                                <tr>
                                    <th>Turma</th>
                                    <th class="text-center" style="width: 220px;">Alunos em Atraso</th>
                                </tr>
                            </thead>
                            <tbody id="corpoTabelaAtrasos">
                                <tr>
                                    <td colspan="2" class="text-center py-3">Carregando dados...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="paginacaoAtrasos" class="d-flex justify-content-center mt-3 gap-2"></div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const dadosLabels = <?php echo json_encode($labels_turmas); ?>;
        const dadosValores = <?php echo json_encode($dados_leituras); ?>;

        function carregarResumoAtrasos(pagina = 1) {
            const tbody = document.getElementById('corpoTabelaAtrasos');
            const containerPaginacao = document.getElementById('paginacaoAtrasos');

            fetch(`buscar_atrasos.php?pag_atrasos=${pagina}`)
                .then(response => {
                    if (!response.ok) throw new Error('Falha de comunicação.');
                    return response.json();
                })
                .then(dados => {
                    tbody.innerHTML = dados.linhas;
                    containerPaginacao.innerHTML = '';

                    if (dados.total_paginas > 1) {
                        const btnAnt = document.createElement('button');
                        btnAnt.className = `btn btn-sm btn-outline-secondary ${dados.pagina_atual <= 1 ? 'disabled' : ''}`;
                        btnAnt.innerText = 'Anterior';
                        btnAnt.onclick = () => carregarResumoAtrasos(dados.pagina_atual - 1);
                        containerPaginacao.appendChild(btnAnt);

                        const indicador = document.createElement('span');
                        indicador.className = 'align-self-center text-muted small mx-2';
                        indicador.innerText = `Página ${dados.pagina_atual} de ${dados.total_paginas}`;
                        containerPaginacao.appendChild(indicador);

                        const btnProx = document.createElement('button');
                        btnProx.className = `btn btn-sm btn-outline-secondary ${dados.pagina_atual >= dados.total_paginas ? 'disabled' : ''}`;
                        btnProx.innerText = 'Próximo';
                        btnProx.onclick = () => carregarResumoAtrasos(dados.pagina_atual + 1);
                        containerPaginacao.appendChild(btnProx);
                    }
                })
                .catch(err => {
                    console.error("Erro:", err);
                    tbody.innerHTML = '<tr><td colspan="2" class="text-center text-danger py-3">Erro ao carregar dados do servidor.</td></tr>';
                });
        }

        document.addEventListener("DOMContentLoaded", function () {
            carregarResumoAtrasos(1);
        });
    </script>

    <script src="graficos.js"></script>
</body>

</html>