<?php session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */
date_default_timezone_set('America/Fortaleza');

$id_turma = "SELECT id_turma FROM turmas";

// Query para encontrar as turmas
$sqlTurmas = "SELECT id_turma, curso, identificador_curso, serie_atual FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$resTurmas = mysqli_query($conn, $sqlTurmas);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Cadastro de Empréstimo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="cadastro.css">
</head>

<body>
    <?php include('../includes/menu.php'); ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <?php if (isset($_SESSION['mensagem'])): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <?= $_SESSION['mensagem']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensagem']); endif; ?>
                <div class="card card-cadastro">
                    <div class="card-header-custom text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bold mb-1"><i class="bi bi-bookmark-plus me-2"></i> Novo Empréstimo</h4>
                            <p class="small text-white-50 mb-0">Preencha os dados abaixo para registrar a saída da obra.</p>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-">
                        <form action="cadastro.php" method="POST" id="formEmprestimo">
                            <div class="row g-4">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold">N° Registro</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="n_registro"
                                        id="n_registro" required autocomplete="off">
                                </div>
                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-bold">Titulo Livro</label>
                                    <input type="text" class="form-control form-control-lg border-2" name="titulo"
                                        id="titulo" readonly placeholder="Digite o registro para buscar..." required>
    
                                    <input type="hidden" name="fk_id_livro" id="fk_id_livro">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Turma</label>
                                    <select name="fk_id_turma" id="turma" class="form-control form-control-lg border-2"
                                        required>
                                        <option value="">Selecione uma turma</option>
                                        <?php while ($turma = mysqli_fetch_assoc($resTurmas)): ?>
                                            <option value="<?= $turma['id_turma']; ?>">
                                                <?= $turma['serie_atual'] ?>º <?= $turma['identificador_curso'] ?> -
                                                <?= $turma['curso'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Aluno</label>
                                    <select name="aluno" id="aluno" class="form-control form-control-lg border-2" required>
                                        <option value="">Selecione a turma primeiro.</option>
                                    </select>
                                </div>
    
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold">Data Saída</label>
                                    <input type="date" class="form-control form-control-lg border-2" name="data_saida"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <?php $data_prevista = date('Y-m-d', strtotime('+7 days')); ?>
                                    <label class="form-label fw-bold">Data Prevista</label>
                                    <input type="date" class="form-control form-control-lg border-2" name="data_prevista"
                                        value="<?php echo $data_prevista; ?>" required>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                    <button type="submit" class="btn btn-salvar btn-lg px-5 shadow text-uppercase">Cadastrar Empréstimo</button>
                                    <a href="list_emprest.php" class="btn btn-outline-danger btn-cancelar btn-lg px-4 fw-bold">Cancelar</a>
                                </div>
                                
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // AUTOMAÇÃO DOS ALUNOS
        document.getElementById('turma').addEventListener('change', function () {
            const idTurma = this.value;
            const selectAluno = document.getElementById('aluno');

            selectAluno.innerHTML = '<option value="">Carregando alunos...</option>';
            selectAluno.disabled = true;

            if (!idTurma) {
                selectAluno.innerHTML = '<option value="">Selecione uma turma primeiro</option>';
                return;
            }

            // Faz a busca em segundo plano
            fetch('buscar_aluno.php?id_turma=' + idTurma)
                .then(response => response.json())
                .then(alunos => {
                    selectAluno.innerHTML = '<option value="">Selecione o aluno</option>';

                    if (alunos.length === 0) {
                        selectAluno.innerHTML = '<option value="">Nenhum aluno nesta turma</option>';
                    } else {
                        alunos.forEach(aluno => {
                            selectAluno.innerHTML += `<option value="${aluno.nome_aluno}">${aluno.nome_aluno}</option>`;
                        });
                        selectAluno.disabled = false;
                    }
                });
        });

        // AUTOMAÇÃO DOS LIVROS
        document.getElementById('n_registro').addEventListener('blur', function () {
            const nRegistro = this.value.trim();
            const inputTitulo = document.getElementById('titulo');
            const inputIdLivro = document.getElementById('fk_id_livro');

            if (!nRegistro) {
                inputTitulo.value = '';
                inputIdLivro.value = '';
                return;
            }

            inputTitulo.value = 'Buscando livro...';

            // Faz a busca do livro pelo número de registro
            fetch('buscar_livro.php?n_registro=' + encodeURIComponent(nRegistro))
                .then(response => response.json())
                .then(dados => {
                    const btnSalvar = document.querySelector('button[type="submit"]'); // Pega seu botão de salvar

                    if (dados.sucesso) {
                        inputTitulo.value = dados.titulo;
                        inputIdLivro.value = dados.id_livro;
                        btnSalvar.disabled = false; // Garante que o botão está liberado
                    } else {
                        inputTitulo.value = '';
                        inputIdLivro.value = '';
                        btnSalvar.disabled = false; // Bloqueia o botão para impedir o envio!
                        alert(dados.msg); // Mostra a mensagem dinâmica ("já emprestado" ou "não encontrado")
                    }
                }) 
                .catch(error => {
                    console.error('Erro na busca do livro:', error);
                    inputTitulo.value = '';
                    inputIdLivro.value = '';
                    btnSalvar.disabled = true; // Bloqueia o botão em caso de erro
                    alert('Ocorreu um erro ao buscar o livro. Tente novamente.');
                });
            });
    </script>
</body>

</html>