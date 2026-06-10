<nav class="navbar navbar-dark shadow-sm sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">

        <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-flex align-items-center">

            <a href="../painel/painel_adm.php" class="d-flex align-items-center text-decoration-none">
                <img src="../img/LogoEscola1-removebg.png" alt="Logo EEEP" style="height: 50px; width: auto;"
                    class="img-fluid">
                <h4 class="text-white m-0 ms-2 d-none d-sm-inline-block fw-bold fs-5 text-truncate"
                    style="max-width: 200px; @media(min-width: 768px){max-width: 100%;}">
                    | EEEP Manoel Mano
                </h4>
            </a>
        </div>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-sm-inline fw-semibold"><?php echo $_SESSION['nome']; ?></span>
                <i class="bi bi-person-circle fs-3 text-white"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow animate slideIn" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item py-2" href="../painel/perfil.php"><i class="bi bi-person me-2"></i>Meu
                        Perfil</a></li>
                <li>
            </ul>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel"
    style="width: 280px;">

    <!--Cabeçalho do Menu Lateral-->
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center w-100">
            <div class="d-flex align-items-center justify-content-center" style="height: 120px; width: 100%;">
                <img src="../img/Logo_Manoteca.png" alt="Logo Manoteca"
                    style="height: auto; width: 80%; max-width: 220px; object-fit: contain;">
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <!--Páginas do Menu Lateral-->
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column mt-3">
            <a href="../painel/painel_adm.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-grid-1x2 me-2 text-success"></i> DASHBOARD
            </a>
            <a href="../livros/form_livro.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-clipboard2-plus text-success"></i> CADASTRO DE LIVROS
            </a>
            <a href="../livros/visualizacao_livro.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-book me-2 text-success"></i> LIVROS
            </a>
            <a href="../emprestimos/list_emprest.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-journal-check me-2 text-success"></i> EMPRÉSTIMOS
            </a>
            <a href="../turmas/index.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-mortarboard-fill text-success"></i> TURMAS
            </a>
            <a href="../alunos/visualizar.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-people me-2 text-success"></i> ALUNOS
            </a>
        </nav>
    </div>
    <!-- Rodapé do Menu Lateral -->
    <div class="p-4 mt-auto">
        <a href="../includes/logout.php" class="text-danger text-decoration-none fw-bold"
            onclick="return confirm('Tem certeza que deseja sair?')">
            <i class="bi bi-box-arrow-left me-2"></i> SAIR
        </a>
    </div>
</div>

<style>
    /* Cores do Projeto */
    :root {
        --verde-eeep: #2d572c;
        --laranja-eeep: #f39200;
        --branco: #ffffff;
    }

    body {
        background-color: #f4f7f6;
    }

    /* Navbar com o verde do protótipo */
    .navbar {
        background-color: var(--verde-eeep) !important;
        border-bottom: 4px solid var(--laranja-eeep);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 1.5rem;
    }

    .profile-dropdown .dropdown-toggle::after {
        display: none;
        /* Remove a setinha padrão do Bootstrap se preferir igual ao protótipo */
    }

    .dropdown-menu {
        border: none;
        border-radius: 8px;
        margin-top: 10px !important;
    }

    .dropdown-item {
        padding: 10px 20px;
        transition: background 0.3s;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: var(--verde-eeep);
    }

    .bi-person-circle {
        color: white;
        transition: opacity 0.2s;
    }

    .bi-person-circle:hover {
        opacity: 0.8;
    }

    .btn-orange {
        background-color: var(--laranja-eeep);
        color: white;
        font-weight: bold;
        border: none;
    }

    .btn-orange:hover {
        background-color: #d88200;
        color: white;
    }

    .btn-menu-toggle {
        color: var(--branco);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        color: #2d572c !important;
    }

    /* --- RESPONSIVIDADE DA NAVBAR --- */
    .navbar {
        padding: 0.4rem 0.75rem !important;
        /* Diminui o espaçamento interno no mobile */
    }

    @media (max-width: 576px) {

        /* Ajusta o tamanho da imagem da logo em telas minúsculas para não empurrar os botões */
        .navbar img {
            height: 40px !important;
        }

        /* Garante que o dropdown de perfil não abra para fora da tela da direita */
        .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
        }
    }
</style>