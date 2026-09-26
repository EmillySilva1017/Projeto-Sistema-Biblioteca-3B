<?php
session_start();
require_once '../includes/verifica_login.php';
include '../includes/conexao.php';
/** @var mysqli $conn */

$titulo = trim($_GET['titulo'] ?? '');
$livros = [];

// Localiza os exemplares que possuem o título completo informado
if ($titulo !== '') {
    $titulo_sql = mysqli_real_escape_string($conn, $titulo);
    $sql = "SELECT numero_registro, titulo_livro, autor, genero
            FROM livros
            WHERE titulo_livro = '$titulo_sql'
            ORDER BY numero_registro";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado) {
        while ($livro = mysqli_fetch_assoc($resultado)) {
            $livros[] = $livro;
        }
    }
}

// Valida os dados e atualiza o gênero de todos os exemplares desse título
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $genero = trim($_POST['genero'] ?? '');

    if ($titulo === '' || $autor === '' || $genero === '' || mb_strlen($genero) > 100) {
        $_SESSION['mensagem'] = 'Informe um título, autor e um gênero válido (até 100 caracteres).';
        $_SESSION['msg_tipo'] = 'warning';
        header('Location: alterar_genero.php');
        exit();
    }

    $titulo_sql = mysqli_real_escape_string($conn, $titulo);
    $autor_sql = mysqli_real_escape_string($conn, $autor);
    $genero_sql = mysqli_real_escape_string($conn, $genero);
    $sql = "UPDATE livros SET genero = '$genero_sql' WHERE titulo_livro = '$titulo_sql' AND autor = '$autor_sql'";

    if (mysqli_query($conn, $sql)) {
        $atualizados = mysqli_affected_rows($conn);
        if ($atualizados > 0) {
            $_SESSION['mensagem'] = "Gênero atualizado em $atualizados exemplar(es).";
            $_SESSION['msg_tipo'] = 'success';
        } else {
            $_SESSION['mensagem'] = 'Nenhum exemplar foi alterado. Verifique o título ou se o gênero já era o mesmo.';
            $_SESSION['msg_tipo'] = 'warning';
        }
    } else {
        $_SESSION['mensagem'] = 'Erro ao atualizar os gêneros dos exemplares.';
        $_SESSION['msg_tipo'] = 'danger';
    }

    header('Location: alterar_genero.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Alterar gênero por título</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="livro.css">
    <link rel="stylesheet" href="../emprestimos/botoes.css">
</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-4">
        <?php include('../includes/alerta.php'); ?>

        <div class="mb-3">
            <a href="visualizacao_livro.php" class="btn btn-voltar">
                <i class="bi bi-arrow-left me-2"></i>Voltar aos livros
            </a>
        </div>
        <h2 class="fw-bold text-dark text-start mb-4">Alterar gênero por título</h2>

        <form action="alterar_genero.php" method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-12 col-md-9">
                <label for="titulo" class="form-label fw-bold">Título do livro</label>
                <div class="position-relative">
                    <div class="input-group">
                        <input type="text" id="titulo" name="titulo" class="form-control"
                            placeholder="Insira um título...."
                            value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off"
                            aria-autocomplete="list" aria-controls="sugestoes-titulos" aria-expanded="false" required>
                        <button type="button" id="limpar-titulo" class="btn btn-outline-secondary"
                            aria-label="Limpar título" title="Limpar título" <?= $titulo === '' ? 'hidden' : '' ?>>
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                    </div>
                    <ul id="sugestoes-titulos" class="list-group sugestoes-titulos" role="listbox" hidden></ul>
                    <div id="estado-sugestoes" class="visually-hidden" aria-live="polite"></div>
                </div>
            </div>
            <div class="col-12 col-md-3 d-grid">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-search me-2"></i>Localizar exemplares
                </button>
            </div>
        </form>

        <?php if ($titulo !== ''): ?>
            <?php if ($livros): ?>
                <p class="mb-3">
                    Encontrados <strong><?= count($livros) ?></strong> exemplar(es) com o título
                    <strong><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></strong>.
                </p>

                <div class="table-container shadow-sm mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="thead-verde text-center">
                                <tr>
                                    <th>N° Registro</th>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>Gênero atual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($livros as $livro): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= htmlspecialchars($livro['numero_registro'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($livro['titulo_livro'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($livro['autor'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= htmlspecialchars($livro['genero'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <form action="alterar_genero.php" method="POST"
                    onsubmit="return confirm('Aplicar este gênero a todos os <?= count($livros) ?> exemplares listados?');"
                    class="row g-3 align-items-end">
                    <input type="hidden" name="titulo" value="<?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="col-12 col-md-9">
                        <label for="genero" class="form-label fw-bold">Novo gênero</label>
                        <input type="text" id="genero" name="genero" class="form-control" maxlength="100" required>
                    </div>
                    <div class="col-12 col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Atualizar exemplares
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Nenhum exemplar foi encontrado com esse título completo.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const campoTitulo = document.getElementById('titulo');
        const botaoLimparTitulo = document.getElementById('limpar-titulo');
        const listaSugestoes = document.getElementById('sugestoes-titulos');
        const estadoSugestoes = document.getElementById('estado-sugestoes');
        let temporizadorSugestoes;
        let indiceSugestaoAtiva = -1;

        function fecharSugestoes() {
            listaSugestoes.hidden = true;
            campoTitulo.setAttribute('aria-expanded', 'false');
            campoTitulo.removeAttribute('aria-activedescendant');
            indiceSugestaoAtiva = -1;
        }

        function selecionarSugestao(item) {
            campoTitulo.value = item.titulo;
            botaoLimparTitulo.hidden = false;
            fecharSugestoes();
            estadoSugestoes.textContent = `Título selecionado: ${item.titulo}`;
        }

        campoTitulo.addEventListener('input', () => {
            botaoLimparTitulo.hidden = campoTitulo.value.length === 0;
            clearTimeout(temporizadorSugestoes);
            const termo = campoTitulo.value.trim();
            listaSugestoes.replaceChildren();

            if (termo.length < 2) {
                fecharSugestoes();
                estadoSugestoes.textContent = '';
                return;
            }

            temporizadorSugestoes = setTimeout(async () => {
                try {
                    const resposta = await fetch(`sugerir_titulos.php?q=${encodeURIComponent(termo)}`);
                    if (!resposta.ok) throw new Error('Falha ao buscar títulos');
                    const itens = await resposta.json();

                    if (campoTitulo.value.trim() !== termo) return;
                    listaSugestoes.replaceChildren();

                    itens.forEach((item, indice) => {
                        const opcao = document.createElement('li');
                        opcao.id = `sugestao-titulo-${indice}`;
                        opcao.className = 'list-group-item list-group-item-action';
                        opcao.dataset.titulo = item.titulo;
                        opcao.setAttribute('role', 'option');
                        opcao.setAttribute('aria-selected', 'false');
                        const quantidade = Number(item.exemplares);
                        opcao.textContent = `${item.titulo} (${quantidade} exemplar${quantidade === 1 ? '' : 'es'})`;
                        opcao.addEventListener('mousedown', evento => evento.preventDefault());
                        opcao.addEventListener('click', () => selecionarSugestao(item));
                        listaSugestoes.appendChild(opcao);
                    });

                    listaSugestoes.hidden = itens.length === 0;
                    campoTitulo.setAttribute('aria-expanded', String(itens.length > 0));
                    estadoSugestoes.textContent = itens.length
                        ? `${itens.length} sugestão(ões) encontrada(s).`
                        : 'Nenhum título correspondente encontrado.';
                } catch (erro) {
                    fecharSugestoes();
                    estadoSugestoes.textContent = 'Não foi possível carregar sugestões de títulos.';
                }
            }, 200);
        });

        botaoLimparTitulo.addEventListener('click', () => {
            fecharSugestoes();
            if (window.location.search) {
                window.location.href = 'alterar_genero.php';
                return;
            }

            campoTitulo.value = '';
            botaoLimparTitulo.hidden = true;
            estadoSugestoes.textContent = '';
            campoTitulo.focus();
        });

        campoTitulo.addEventListener('keydown', evento => {
            const opcoes = [...listaSugestoes.querySelectorAll('[role="option"]')];
            if (!opcoes.length) return;

            if (evento.key === 'ArrowDown' || evento.key === 'ArrowUp') {
                evento.preventDefault();
                indiceSugestaoAtiva = (indiceSugestaoAtiva + (evento.key === 'ArrowDown' ? 1 : -1) + opcoes.length) % opcoes.length;
                opcoes.forEach((opcao, indice) => {
                    const ativa = indice === indiceSugestaoAtiva;
                    opcao.setAttribute('aria-selected', String(ativa));
                    if (ativa) {
                        campoTitulo.setAttribute('aria-activedescendant', opcao.id);
                        opcao.scrollIntoView({ block: 'nearest' });
                    }
                });
            } else if (evento.key === 'Enter' && indiceSugestaoAtiva >= 0) {
                evento.preventDefault();
                selecionarSugestao({ titulo: opcoes[indiceSugestaoAtiva].dataset.titulo });
            } else if (evento.key === 'Escape') {
                fecharSugestoes();
            }
        });

        document.addEventListener('click', evento => {
            if (!listaSugestoes.contains(evento.target) && evento.target !== campoTitulo) fecharSugestoes();
        });
    </script>
</body>

</html>