<?php session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

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
    <link rel="stylesheet" href="livro.css">n

</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <div class="container-fluid px-5">
        <form action="" method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-12 col-md-5 position-relative">
                <input type="text" name="busca" class="form-control input-custom ps-5"
                    placeholder="Pesquisar por título, autor ou registro..." value="<?= htmlspecialchars($busca) ?>">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted"></i>
            </div>

            <div class="col-12 col-md-4">
                <select name="genero" class="form-select input-custom">
                    <option value="">Gênero V</option>
                    <option value="Literatura" <?= ($macro_genero == 'Literatura') ? 'selected' : '' ?>>Literatura</option>
                    <option value="Didáticos" <?= ($macro_genero == 'Didáticos') ? 'selected' : '' ?>>Didáticos</option>
                    <option value="Humanas" <?= ($macro_genero == 'Humanas') ? 'selected' : '' ?>>Humanas</option>
                    <option value="Ciências" <?= ($macro_genero == 'Ciências') ? 'selected' : '' ?>>Ciências</option>
                    <option value="Outros" <?= ($macro_genero == 'Outros') ? 'selected' : '' ?>>Outros</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-filtrar w-100 shadow-sm">Filtrar</button>
            </div>
        </form>

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

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
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