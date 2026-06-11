<?php 
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: visualizacao_livro.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$sql_select = "SELECT * FROM livros WHERE id = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    header('Location: visualizacao_livro.php');
    exit();
}

$dados = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Editar Livro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="cadastro.css">
</head>
<body>
    <?php include('../includes/menu.php'); ?>
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                
                <div class="card card-cadastro">
                    <div class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i> Edição de Obra</h4>
                            <p class="small text-white-50 mb-0">Insira as informações técnicas para editar o livro.</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="atualizar.php" method="POST">
                            <input type="hidden" name="id" value="<?= $id; ?>">
                            
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-3">
                                    <label class="form-label fw-bold">N° Registro</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="n_registro" value="<?= htmlspecialchars($dados['numero_registro']); ?>" required>
                                </div>
                                <div class="col-12 col-md-9">
                                    <label class="form-label fw-bold">Titulo</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="titulo" value="<?= htmlspecialchars($dados['titulo_livro']); ?>" required>
                                </div>
                            </div>
    
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold">Autor</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="autor" value="<?= htmlspecialchars($dados['autor']); ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold">Gênero</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="genero" value="<?= htmlspecialchars($dados['genero']); ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold">Editora</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="editora" value="<?= htmlspecialchars($dados['editora']); ?>" required>
                                </div>
                            </div>
    
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold">Data Aquisição</label>
                                    <input type="date" class="form-control form-control-lg border-2" name="ano_aquisicao" value="<?= htmlspecialchars($dados['ano_aquisicao']); ?>" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold">CDD</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="cdd" value="<?= htmlspecialchars($dados['cdd']); ?>" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold">CDU</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="cdu" value="<?= htmlspecialchars($dados['cdu']); ?>" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label fw-bold">Selo</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="selo" value="<?= htmlspecialchars($dados['selo']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-salvar btn-lg px-5 shadow text-uppercase">Editar
                                    Livro</button>
                                <a href="visualizacao_livro.php"
                                    class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>