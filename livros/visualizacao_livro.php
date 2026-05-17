<?php session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// configuração da paginação
$itens_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($pagina_atual < 1)
    $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $itens_por_pagina;

// inicialização da barra de busca e filtro
$busca = "";
$macro_genero = "";

if (isset($_GET['busca'])) {
    $busca = mysqli_real_escape_string($conn, $_GET['busca']);
}
if (isset($_GET['genero'])) {
    $macro_genero = mysqli_real_escape_string($conn, $_GET['genero']);
}

// inicialização das variáveis de controle da tabela
$resLivros = false;
$total_paginas = 1;
$busca_ativa = !empty($busca) || !empty($macro_genero);

//  a lógica do Banco de Dados executa APENAS se houver uma busca/filtro ativo
if ($busca_ativa) {

    // query base para contagem e listagem dos livros
    $sql = "SELECT id, numero_registro, titulo_livro, autor, genero FROM livros WHERE 1=1";
    $sql_count = "SELECT COUNT(*) as total FROM livros WHERE 1=1";
    $where_filtros = "";

    // Filtro por Texto (Título, Autor ou Registro)
    if (!empty($busca)) {
        $where_filtros .= " AND (titulo_livro LIKE '%$busca%' OR autor LIKE '%$busca%' OR numero_registro LIKE '%$busca%')";
    }

    // Filtro por Macro-gênero (Mapeando os termos técnicos do banco de dados)
    if (!empty($macro_genero)) {
        if ($macro_genero == 'Literatura') { //literatura
            $where_filtros .= " AND (genero LIKE 'Biografia%' OR genero LIKE 'Literatura%' OR genero LIKE 'Carta%')";
        } elseif ($macro_genero == 'Didáticos') { //didaticos
            $where_filtros .= " AND (genero LIKE 'Educação%' OR genero LIKE 'Ensino%' OR genero LIKE 'Leitura%' 
            OR genero LIKE 'Escola%' OR genero LIKE 'Norma%')";
        } elseif ($macro_genero == 'Humanas') { //ciencias humanas
            $where_filtros .= " AND (genero LIKE 'História%' OR genero LIKE 'Sociologia%' OR genero LIKE 'Geografia%' 
            OR genero LIKE 'Filosofia%' OR genero LIKE 'Psicologia%')";
        } elseif ($macro_genero == 'Ciências') { //ciencias da natureza
            $where_filtros .= " AND (genero LIKE 'Física%' OR genero LIKE 'Química%' OR genero LIKE 'Biologia%' OR genero = 'Ciências')";
        } elseif ($macro_genero == 'Negócios') { //negocios
            $where_filtros .= " AND (genero LIKE 'Economia%' OR genero LIKE 'Comércio%' OR genero LIKE 'Gestão%' OR genero LIKE 'Contabilidade%')";
        } elseif ($macro_genero == 'Infanto-Juvenil') { //infanto-juvenil
            $where_filtros .= " AND (genero = 'Infanto-Juvenil')";
        } elseif ($macro_genero == 'Romance') { //romance
            $where_filtros .= " AND (genero = 'Romance')";
        } elseif ($macro_genero == 'Ficção') { //ficção
            $where_filtros .= " AND (genero LIKE 'Ficção%')";
        } elseif ($macro_genero == 'Informática') { //informatica
            $where_filtros .= " AND (genero = 'Informática')";
        } elseif ($macro_genero == 'Enfermagem') { //enfermagem
            $where_filtros .= " AND (genero = 'Enfermagem')";
        } elseif ($macro_genero == 'Conto') { //conto
            $where_filtros .= " AND (genero = 'Conto')";
        } elseif ($macro_genero == 'Crônica') { //crônica
            $where_filtros .= " AND (genero LIKE 'Crônica%')";
        } elseif ($macro_genero == 'Gramática') { //gramática
            $where_filtros .= " AND (genero = 'Gramática')";
        } elseif ($macro_genero == 'Matemática') { //matemática
            $where_filtros .= " AND (genero = 'Matemática')";
        } elseif ($macro_genero == 'Outros') { // Categoria para capturar o restante do acervo
            $where_filtros .= " AND (
            genero = 'Sem Gênero' 
            OR (
                genero NOT LIKE 'Biografia%' AND genero NOT LIKE 'Literatura%'  AND genero NOT LIKE 'Carta%'
                AND genero NOT LIKE 'Educação%' AND genero NOT LIKE 'Ensino%' AND genero NOT LIKE 'Leitura%' 
                AND genero NOT LIKE 'Escola%' AND genero NOT LIKE 'Norma%' AND genero NOT LIKE 'História%' 
                AND genero NOT LIKE 'Sociologia%'  AND genero NOT LIKE 'Geografia%' AND genero NOT LIKE 'Filosofia%' 
                AND genero NOT LIKE 'Psicologia%' AND genero NOT LIKE 'Física%' AND genero NOT LIKE 'Química%' 
                AND genero NOT LIKE 'Biologia%'  AND genero != 'Ciências' AND genero NOT LIKE 'Economia%' 
                AND genero NOT LIKE 'Comércio%' AND genero NOT LIKE 'Gestão%' AND genero NOT LIKE 'Contabilidade%'
                AND genero != 'Infanto-Juvenil' AND genero != 'Romance' AND genero NOT LIKE 'Ficção%'
                AND genero != 'Informática' AND genero != 'Enfermagem' AND genero != 'Conto'
                AND genero NOT LIKE 'Crônica%' AND genero != 'Gramática' AND genero != 'Matemática'
        ))";
        }
    }

    // Aplica os filtros nas duas queries
    $sql .= $where_filtros;
    $sql_count .= $where_filtros;

    // Primeiro passo da paginação: Contar quantos registros existem no total para esse filtro
    $resCount = mysqli_query($conn, $sql_count);
    $total_registros = mysqli_fetch_assoc($resCount)['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);

    // Segundo passo da paginação: Trazer apenas as linhas da página atual ordenadas
    $sql .= " ORDER BY titulo_livro ASC LIMIT $itens_por_pagina OFFSET $offset";
    $resLivros = mysqli_query($conn, $sql);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="livro.css">

</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <div class="container-fluid px-5">
        <form action="" method="GET" class="row g-3 mb-4 mt-1 align-items-end">
            <div class="col-12 col-md-5 position-relative">
                <input type="text" name="busca" class="form-control input-custom ps-5"
                    placeholder="Pesquisar por título, autor ou registro..." value="<?= htmlspecialchars($busca) ?>">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted"></i>
            </div>

            <div class="col-12 col-md-4">
                <select name="genero" class="form-select input-custom">
                    <option value="">Gênero</option>
                    <option value="Ciências" <?= ($macro_genero == 'Ciências') ? 'selected' : '' ?>>Ciências</option>
                    <option value="Conto" <?= ($macro_genero == 'Conto') ? 'selected' : '' ?>>Conto</option>
                    <option value="Crônica" <?= ($macro_genero == 'Crônica') ? 'selected' : '' ?>>Crônica</option>
                    <option value="Didáticos" <?= ($macro_genero == 'Didáticos') ? 'selected' : '' ?>>Didáticos</option>
                    <option value="Enfermagem" <?= ($macro_genero == 'Enfermagem') ? 'selected' : '' ?>>Enfermagem</option>
                    <option value="Ficção" <?= ($macro_genero == 'Ficção') ? 'selected' : '' ?>>Ficção</option>
                    <option value="Gramática" <?= ($macro_genero == 'Gramática') ? 'selected' : '' ?>>Gramática</option>
                    <option value="Humanas" <?= ($macro_genero == 'Humanas') ? 'selected' : '' ?>>Humanas</option>
                    <option value="Infanto-Juvenil" <?= ($macro_genero == 'Infanto-Juvenil') ? 'selected' : '' ?>>
                        Infanto-Juvenil</option>
                    <option value="Informática" <?= ($macro_genero == 'Informática') ? 'selected' : '' ?>>Informática
                    </option>
                    <option value="Literatura" <?= ($macro_genero == 'Literatura') ? 'selected' : '' ?>>Literatura</option>
                    <option value="Matemática" <?= ($macro_genero == 'Matemática') ? 'selected' : '' ?>>Matemática</option>
                    <option value="Negócios" <?= ($macro_genero == 'Negócios') ? 'selected' : '' ?>>Negócios</option>
                    <option value="Romance" <?= ($macro_genero == 'Romance') ? 'selected' : '' ?>>Romance</option>
                    <option value="Outros" <?= ($macro_genero == 'Outros') ? 'selected' : '' ?>>Outros</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-filtrar w-100">Filtrar</button>
            </div>
        </form>

        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <?= $_SESSION['mensagem']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['mensagem']); endif; ?>

        <div class="mb-2">
            <h2 class="fw-bold text-dark">Gestão de Livros</h2>
        </div>

        <div class="table-container shadow-sm mb-3">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center mb-0">
                    <thead class="thead-verde">
                        <tr>
                            <th>Nº Registro</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Gênero</th>
                            <th>Situação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$busca_ativa): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    Utilize a barra de pesquisa ou selecione um macro-gênero para listar os livros.
                                </td>
                            </tr>
                        <?php elseif (isset($resLivros) && mysqli_num_rows($resLivros) > 0): ?>
                            <?php while ($livro = mysqli_fetch_assoc($resLivros)): ?>
                                <tr>
                                    <td><?= $livro['numero_registro'] ?></td>
                                    <td class="text-start"><strong><?= $livro['titulo_livro'] ?></strong></td>
                                    <td class="text-start"><?= $livro['autor'] ?></td>
                                    <td><?= $livro['genero'] ?></td>
                                    <td>
                                        <?php $situacao_provisoria = "Disponivel";
                                        if ($situacao_provisoria == "Disponivel") {
                                            echo '<span class="badge bg-success">Disponível</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Emprestado</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="editar_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="excluir_livro.php?id=<?= $livro['id'] ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Deseja excluir este exemplar?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 fw-bold">
                                    Nenhum livro foi encontrado para os termos informados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($busca_ativa && $total_paginas > 1): ?>
            <div class="d-flex justify-content-center align-items-center my-4 gap-2">

                <a href="?busca=<?= urlencode($busca) ?>&genero=<?= urlencode($macro_genero) ?>&pagina=<?= $pagina_atual - 1 ?>"
                    class="seta-paginacao <?= ($pagina_atual <= 1) ? 'desativada' : '' ?>">
                    <i class="bi bi-caret-left-fill"></i>
                </a>

                <?php
                $max_bolinhas = 5; // Define o máximo de bolinhas visíveis na tela
            
                $inicio = max(1, $pagina_atual - floor($max_bolinhas / 2));
                $fim = min($total_paginas, $inicio + $max_bolinhas - 1);

                // Ajusta o início caso o fim tenha chegado ao limite máximo de páginas
                if ($fim - $inicio + 1 < $max_bolinhas) {
                    $inicio = max(1, $fim - $max_bolinhas + 1);
                }

                // Renderiza apenas as bolinhas que estão dentro do intervalo calculado
                for ($i = $inicio; $i <= $fim; $i++): ?>
                    <a href="?busca=<?= urlencode($busca) ?>&genero=<?= urlencode($macro_genero) ?>&pagina=<?= $i ?>"
                        class="paginacao-link <?= ($i == $pagina_atual) ? 'ativa' : '' ?>" title="Página <?= $i ?>">
                        <i class="bi bi-circle-fill"></i>
                    </a>
                <?php endfor; ?>

                <a href="?busca=<?= urlencode($busca) ?>&genero=<?= urlencode($macro_genero) ?>&pagina=<?= $pagina_atual + 1 ?>"
                    class="seta-paginacao <?= ($pagina_atual >= $total_paginas) ? 'desativada' : '' ?>">
                    <i class="bi bi-caret-right-fill"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>