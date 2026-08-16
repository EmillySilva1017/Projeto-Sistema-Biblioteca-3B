<nav class="navbar navbar-dark shadow-sm sticky-top"
    style="background-color: #2d572c; border-bottom: 4px solid #f39200;">
    <div class="container d-flex align-items-center justify-content-between">

        <!-- Identificação do Sistema -->
        <a href="dashboard_aluno.php" class="d-flex align-items-center text-decoration-none">
            <img src="../img/LogoManoteca-removebg-preview.png" alt="Logo" style="height: 50px; width: auto;"
                class="img-fluid">
            <h4 class="text-white m-0 ms-2 d-none d-sm-inline fw-bold fs-5"
                    style="max-width: 200px; @media(min-width: 768px){max-width: 100%;}">
                    | EEEP Manoel Mano
            </h4>
        </a>

        <!-- Área do Usuário / Logout -->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown">
                <span class="me-2 fw-semibold d-none d-sm-inline"><?php echo $_SESSION['nome_aluno']; ?></span>
                <i class="bi bi-person-circle fs-4"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="perfil_aluno.php"><i class="bi bi-person me-2"></i>Meu Perfil</a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="../includes/logout.php" onclick="return confirm('Deseja sair?')">
                    <i class="bi bi-box-arrow-left me-2"></i>Sair</a></li>
            </ul>
        </div>

    </div>
</nav>



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
        padding: 0.5rem 1.5rem !important;
    }

    .dropdown-toggle::after {
        display: none !important;
        /* Remove a setinha padrão do Bootstrap */
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

    .btn-menu-toggle {
        color: var(--branco);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .nav-link:hover {
        background-color: #f8f9fa;
        color: var(--verde-eeep) !important;
    }

    /* --- RESPONSIVIDADE DA NAVBAR --- */
    @media (max-width: 576px) {
        .navbar {
            padding: 0.4rem 0.75rem !important;
        }

        .navbar img {
            height: 40px !important;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
        }
    }
</style>