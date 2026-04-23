<nav class="navbar navbar-dark shadow-sm sticky-top">
    <div class="container-fluid">
        <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
            <i class="bi bi-list"></i>
        </button>
        <div style="display: flex; justify-content: center; align-items: center;">
            <a href="../painel/painel_adm.php">
                <img src="../img/LogoEscola1-removebg.png" alt="Logo EEEP" style="height: 60px; width: 80px;">
            </a>
            <h2 style="color: white; margin-left: 10px;">| EEEP Manoel Mano</h2>
        </div>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="me-2 d-none d-sm-inline"><?php echo $_SESSION['nome']; ?></span>
                <i class="bi bi-person-circle fs-3"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="../painel/perfil.php"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="../includes/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 280px;">

    <!--Cabeçalho do Menu Lateral-->
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center w-100">
            <div class="d-flex align-items-center justify-content-center" style="height: 120px; width: 100%;">
                <img src="../img/Logo_Manoteca.png" alt="Logo Manoteca" style="height: auto; width: 80%; max-width: 220px; object-fit: contain;">
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
            <a href="###" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-book me-2 text-success"></i> LIVROS
            </a>
            <a href="####" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-journal-check me-2 text-success"></i> EMPRÉSTIMOS
            </a>
            <a href="../turmas/index.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-people me-2 text-success"></i> TURMAS
            </a>
            <a href="../alunos/visualizar.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-people me-2 text-success"></i> ALUNOS
            </a>
        </nav>
    </div>
    <!--Rodapé do Menu Lateral
    <div class="p-4 mt-auto border-top">
        <a href="../includes/logout.php" class="text-danger text-decoration-none fw-bold">
            <i class="bi bi-box-arrow-left me-2"></i> SAIR
        </a>
    </div>-->
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
</style>