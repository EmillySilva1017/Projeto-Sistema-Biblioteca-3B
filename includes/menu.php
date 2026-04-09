<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel" style="width: 280px;">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">MM</div>
            <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel" style="color: #2d572c;">EEEP MANOEL MANO</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-0">
        <nav class="nav flex-column mt-3">
            <a href="../painel/painel_adm.php" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-grid-1x2 me-2 text-success"></i> DASHBOARD
            </a>
            <a href="../turmas/index.php" class="nav-link py-3 px-4 border-bottom text-dark bg-light">
                <i class="bi bi-people me-2 text-success"></i> TURMAS
            </a>
            <a href="###" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-book me-2 text-success"></i> LIVROS
            </a>
            <a href="####" class="nav-link py-3 px-4 border-bottom text-dark">
                <i class="bi bi-journal-check me-2 text-success"></i> EMPRÉSTIMOS
            </a>
        </nav>
    </div>

    <div class="p-4 mt-auto border-top">
        <a href="../includes/logout.php" class="text-danger text-decoration-none fw-bold">
            <i class="bi bi-box-arrow-left me-2"></i> SAIR
        </a>
    </div>
</div>

<style>
    /* Cores extraídas do Protótipo EEEP */
    :root {
        --verde-eeep: #2d572c;
        /* O verde oficial da farda/logo */
        --laranja-eeep: #f39200;
        /* O laranja dos botões de destaque */
        --branco: #ffffff;
    }

    body {
        background-color: #f4f7f6;
    }

    /* Navbar com o verde do protótipo */
    .navbar {
        background-color: var(--verde-eeep) !important;
        border-bottom: 4px solid var(--laranja-eeep);
        /* Linha laranja de detalhe */
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

    /* Ajuste do botão de menu para ficar branco e visível no verde */
    .btn-menu-toggle {
        color: var(--branco);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    .nav-link:hover { background-color: #f8f9fa; color: #2d572c !important; }
    .nav-link.active { border-left: 5px solid #2d572c; font-weight: bold; }
</style>