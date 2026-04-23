<?php
session_start();
include('../includes/conexao.php');

//Logica para a barra de pesquisa
$busca = "";
if (isset($_GET['search'])) {
    $busca = mysqli_real_escape_string($conn, $_GET['search']);
}

//Query sql para pegar os dados dos alunos juntamente com as turmas
$sql = "SELECT a.*, t.serie_atual, t.curso, t.identificador_curso
        FROM alunos a
        INNER JOIN turmas t ON a.fk_id_turma = t.id_turma
        WHERE 1=1";

//Se houver uma busca, filtra por nome ou matricula
if (!empty($_GET['busca'])) {
    $busca = mysqli_real_escape_string($conn, $_GET['busca']);
    $sql .= " AND (a.nome_aluno LIKE '%$busca%' OR a.matricula LIKE '%$busca%')";
}
//Filtro por Curso
if (!empty($_GET['curso'])) {
    $curso = mysqli_real_escape_string($conn, $_GET['curso']);
    $sql .= " AND t.curso = '$curso'";
}
//Filtro por Turma
if (!empty($_GET['turma'])) {
    $turma_id = mysqli_real_escape_string($conn, $_GET['turma']);
    $sql .= " AND a.fk_id_turma = '$turma_id'";
}

$sql .= " ORDER BY t.curso ASC, a.nome_aluno ASC";
$resAlunos = mysqli_query($conn, $sql);

// Busca os cursos para o primeiro select
$sqlCursos = "SELECT DISTINCT curso FROM turmas ORDER BY curso DESC";
$resCursos = mysqli_query($conn, $sqlCursos);

// Busca todas as turmas para o segundo select
$sqlTodasTurmas = "SELECT id_turma, serie_atual, identificador_curso, curso FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$resTodasTurmas = mysqli_query($conn, $sqlTodasTurmas);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Aluno - EEEP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<style>
    /* Forçamos o estilo no cabeçalho da tabela */
    .table thead.thead-esverdeada th {
        background-color: #d1e7dd !important;
        /* Verde padrão Bootstrap "success" claro */
        color: #0f5132 !important;
        /* Texto verde escuro */
        border-bottom: 2px solid #badbcc !important;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
</style>

<body>
    <?php include('../includes/menu.php'); ?>

    <!--- Filtros e Botao de cadastrar aluno--->
    <div class="container-fluid px-4 pt-2">
        <?php
        if (isset($_SESSION['msg'])):
        ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $_SESSION['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

        <?php unset($_SESSION['msg']); //limpa mensagem 
        endif;
        ?>
        <div class="mb-2">
            <h2 class="fw-bold text-dark">Gestão de Alunos</h2>
        </div>

        <div class="row g-3 align-items-end mb-5">

            <div class="col-12 col-lg-10">
                <form action="" method="GET" class="row g-2">

                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Pesquisar</label>
                        <input type="text" name="busca" class="form-control border-0 shadow-sm" placeholder="Nome ou matrícula..." value="<?= $_GET['busca'] ?? '' ?>">
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Curso</label>
                        <select name="curso" class="form-select border-0 shadow-sm">
                            <option value="">Todos os Cursos</option>
                            <?php mysqli_data_seek($resCursos, 0);
                            while ($c = mysqli_fetch_assoc($resCursos)): ?>
                                <option value="<?= $c['curso'] ?>" <?= (isset($_GET['curso']) && $_GET['curso'] == $c['curso']) ? 'selected' : '' ?>>
                                    <?= $c['curso'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Turma</label>
                        <select name="turma" class="form-select border-0 shadow-sm">
                            <option value="">Todas as Turmas</option>
                            <?php mysqli_data_seek($resTodasTurmas, 0);
                            while ($t = mysqli_fetch_assoc($resTodasTurmas)): ?>
                                <option value="<?= $t['id_turma'] ?>" <?= (isset($_GET['turma']) && $_GET['turma'] == $t['id_turma']) ? 'selected' : '' ?>>
                                    <?= $t['serie_atual'] ?>º <?= $t['identificador_curso'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Filtro</label>
                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="bi bi-filter"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-lg-2 text-lg-end">
                <a href="cadastrar_aluno.php" class="btn btn-success w-100 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg"></i> CADASTRAR
                </a>
            </div>
        </div>

        <!--- Lista de alunos --->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-stripped mb-0">

                        <thead class="thead-esverdeada">
                            <tr>
                                <th class="py-3 px-4">Nome</th>
                                <th class="py-3">Matrícula</th>
                                <th class="py-3">Série</th>
                                <th class="py-3">Curso</th>
                                <th class="py-3">Ações</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($resAlunos) > 0): ?>
                                <?php while ($aluno = mysqli_fetch_assoc($resAlunos)): ?>
                                    <tr>
                                        <td>
                                            <?= $aluno['nome_aluno'] ?>
                                        </td>

                                        <td>
                                            <?= $aluno['matricula'] ?>
                                        </td>

                                        <td>
                                            <?= $aluno['serie_atual'] ?>º <?= $aluno['identificador_curso'] ?>
                                        </td>

                                        <td>
                                            <?= $aluno['curso'] ?>
                                        </td>

                                        <td>
                                            <a href="editar_aluno.php?id=<?= $aluno['id_aluno'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="excluir_aluno.php?id=<?= $aluno['id_aluno'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Deseja excluir este aluno? Esta ação não pode ser desfeita!')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        Nenhum aluno encontrado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>