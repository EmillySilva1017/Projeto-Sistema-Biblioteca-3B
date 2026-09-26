<?php
$rotaAtual = basename(dirname($_SERVER['PHP_SELF'] ?? '')) . '/' . basename($_SERVER['PHP_SELF'] ?? '');

$itensMenu = [
    [
        'href' => '../painel/painel_adm.php',
        'icone' => 'bi-grid-1x2',
        'rotulo' => 'DASHBOARD',
        'rotas' => ['painel/painel_adm.php'],
    ],
    [
        'href' => '../livros/form_livro.php',
        'icone' => 'bi-clipboard2-plus',
        'rotulo' => 'CADASTRO DE LIVROS',
        'rotas' => ['livros/form_livro.php', 'livros/editar_livro.php', 'livros/alterar_genero.php'],
    ],
    [
        'href' => '../livros/visualizacao_livro.php',
        'icone' => 'bi-book',
        'rotulo' => 'LIVROS',
        'rotas' => ['livros/visualizacao_livro.php'],
    ],
    [
        'href' => '../emprestimos/list_emprest.php',
        'icone' => 'bi-journal-check',
        'rotulo' => 'EMPRÉSTIMOS',
        'rotas' => ['emprestimos/list_emprest.php', 'emprestimos/cadastro_emprest.php'],
    ],
    [
        'href' => '../turmas/index.php',
        'icone' => 'bi-mortarboard-fill',
        'rotulo' => 'TURMAS',
        'rotas' => ['turmas/index.php', 'turmas/editar.php', 'turmas/form_turma.php'],
    ],
    [
        'href' => '../alunos/visualizar.php',
        'icone' => 'bi-people',
        'rotulo' => 'ALUNOS',
        'rotas' => ['alunos/visualizar.php', 'alunos/cadastro_aluno.php', 'alunos/editar_aluno.php', 'alunos/importar_alunos.php'],
    ],
];
?>
<link rel="stylesheet" href="../includes/menu.css">

<nav class="navbar navbar-dark shadow-sm sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Abrir menu lateral">
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>

        <div class="d-flex align-items-center">
            <a href="../painel/painel_adm.php" class="d-flex align-items-center text-decoration-none">
                <img src="../img/LogoManoteca-removebg-preview.png" alt="Logo Manoteca"
                    class="img-fluid navbar-brand-logo">
            </a>
        </div>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-sm-inline fw-semibold">
                    <?php echo $_SESSION['nome_user'] ?? $_SESSION['nome'] ?? 'Usuário'; ?>
                </span>
                <i class="bi bi-person-circle fs-3 text-white" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow animate slideIn" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item py-2" href="../perfil/perfil.php"><i class="bi bi-person me-2" aria-hidden="true"></i>Meu Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="../includes/logout.php"
                    onclick="return confirm('Tem certeza que deseja sair?')">
                    <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Sair</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start sidebar-menu" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar menu"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column" aria-label="Navegação principal">
            <?php foreach ($itensMenu as $item): ?>
                <?php $ativo = in_array($rotaAtual, $item['rotas'], true); ?>
                <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>"
                    class="nav-link py-3 px-4 border-bottom<?= $ativo ? ' active' : ''; ?>"
                    <?= $ativo ? 'aria-current="page"' : ''; ?>>
                    <i class="bi <?= htmlspecialchars($item['icone'], ENT_QUOTES, 'UTF-8'); ?> me-2 text-success" aria-hidden="true"></i>
                    <?= htmlspecialchars($item['rotulo'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</div>