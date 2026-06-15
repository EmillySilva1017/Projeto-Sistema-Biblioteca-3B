<?php session_start();
include '../includes/conexao.php';

// Verifica se está logado e se é administrador (nível 1)
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] != 1) {
    header('Location: ../cadastro/index.php');
    exit();
}

### CARDS ####

#Consulta 1: Total do acervo e livros atualmente disponíveis na estante
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
    // Garante que se o cálculo der nulo (banco vazio), ele mostre 0
    $total_disponiveis = max(0, (int) $row['total_disponiveis']);
}

#Consulta 2: Gênero mais popular
$top_genero = "SELECT l.genero, COUNT(e.id_emprestimos) AS total_emprest 
FROM emprestimos e
INNER JOIN livros l ON e.fk_id_livro = l.id
GROUP BY l.genero 
ORDER BY total_emprest DESC 
LIMIT 1";
$result_genero = mysqli_query($conn, $top_genero);

if ($result_genero) {
    $row = mysqli_fetch_assoc($result_genero);
    $genero_popular = $row['genero'];
    $total_emprestimos = $row['total_emprest'];
}

#Consulta 3: Turma com maior índice de leitura
$top_turma = "SELECT CONCAT(t.serie_atual, '° ', t.identificador_curso) AS turma, COUNT(e.id_emprestimos) AS total_turma
FROM emprestimos e
INNER JOIN turmas t ON e.fk_id_turma = t.id_turma 
GROUP BY e.fk_id_turma
ORDER BY total_turma DESC 
LIMIT 1";
$result_turma = mysqli_query($conn, $top_turma);
$turma_popular = "Nenhum registro";
$total_leituras_turma = 0;

if ($result_turma && mysqli_num_rows($result_turma) > 0) {
    $row_turma = mysqli_fetch_assoc($result_turma);
    $turma_popular = $row_turma['turma'];
    $total_leituras_turma = $row_turma['total_turma'];
}

### GRÁFICO ###

#Consulta 4: Total de leituras por Turma
$sql_grafico = "SELECT 
                    CONCAT(t.serie_atual, 'º ', t.identificador_curso) AS sigla_turma, 
                    COUNT(e.id_emprestimos) AS total_emprestimos 
                FROM turmas t
                LEFT JOIN emprestimos e ON t.id_turma = e.fk_id_turma
                GROUP BY t.id_turma
                ORDER BY t.serie_atual ASC, t.identificador_curso ASC";

$result_grafico = mysqli_query($conn, $sql_grafico);

$labels_turmas = [];
$dados_leituras = [];

while ($row = mysqli_fetch_assoc($result_grafico)) {
    $labels_turmas[] = $row['sigla_turma'];        // Ex: ["3º A", "3º B", "2º A"]
    $dados_leituras[] = (int) $row['total_emprestimos']; // Ex: [15, 10, 5]
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php include '../includes/menu.php'; ?>

    <main class="container-fluid px-4 py-5">
        <section>
            <div class="row">
                <!-- #Consulta 1: Total do acervo -->
                <div class="col-12 col-md-4 col-lg-4 mb-4">
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

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card card-dashboard border-genero shadow-sm h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted-dashboard fw-bold">Gênero Mais Popular</span>
                                <h3 class="fw-bold text-dark my-1 text-truncate" style="max-width: 220px;"
                                    title="<?php echo $genero_popular; ?>">
                                    <?php echo $genero_popular; ?>
                                </h3>
                                <p class="text-muted small mb-0"><i class="bi bi-graph-up-arrow text-warning"></i>
                                    <?php echo $total_emprestimos; ?> leituras</p>
                            </div>
                            <div class="icon-shape ms-3">
                                <i class="bi bi-bookmark-star fs-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card card-dashboard border-turma shadow-sm h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted-dashboard fw-bold">Turma Mais Ativa</span>
                                <h4 class="fw-bold text-dark my-1 text-truncate" style="max-width: 220px;"
                                    title="<?php echo $turma_popular; ?>">
                                    <?php echo $turma_popular; ?>
                                </h4>
                                <p class="text-muted small mb-0"><i class="bi bi-people text-primary"></i> Maior índice
                                    de leitura: <?php echo $total_leituras_turma; ?> </p>
                            </div>
                            <div class="icon-shape ms-3">
                                <i class="bi bi-mortarboard fs-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="row mb-5">
            <div class="col-12">
                <div class="card card-dashboard shadow-sm p-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Resumo de Livros Atrasados por
                        Turma
                    </h5>
                    <a href="../emprestimos/list_emprest.php">
                        <div class="table-container shadow-sm mb-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                                    <thead class="thead-pers text-center">
                                        <tr>
                                            <th>Turma</th>
                                            <th class="text-center" style="width: 200px;">Alunos em Atraso</th>
                                        </tr>
                                    </thead>
                                    <tbody id="corpoTabelaAtrasos">
                                        <tr>
                                            <td colspan="2" class="text-center py-3">Carregando dados...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </a>

                    <div id="paginacaoAtrasos" class="d-flex justify-content-center mt-3 gap-2"></div>
                </div>
            </div>
        </section>

        <section class="row mb-4">
            <div class="col-12">
                <div class="card card-dashboard shadow-sm p-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-graph-up text-success me-2"></i> Índice de Leitura por Turma
                    </h5>
                    <div class="chart-container">
                        <canvas id="graficoLeituraTurmas"></canvas>
                    </div>
                </div>
            </div>
            </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para carregar a tabela de atrasos via AJAX -->
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