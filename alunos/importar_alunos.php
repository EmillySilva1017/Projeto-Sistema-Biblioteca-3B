<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header('Location: ../index.php');
    exit();
}

// Processa o upload e a importação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo_csv'])) {
    $id_turma = mysqli_real_escape_string($conn, $_POST['id_turma']);
    $arquivo = $_FILES['arquivo_csv']['tmp_name'];

    // Valida se a turma foi selecionada e se o arquivo foi enviado
    if (empty($id_turma)) {
        $_SESSION['msg'] = "Por favor, selecione a turma antes de enviar.";
        $_SESSION['msg_tipo'] = "danger";
    } elseif (empty($arquivo)) {
        $_SESSION['msg'] = "Por favor, selecione um arquivo CSV.";
        $_SESSION['msg_tipo'] = "danger";
    } else {
        // Tenta abrir o arquivo CSV para leitura
        if (($handle = fopen($arquivo, "r")) !== FALSE) {
            
            // Ignora a primeira linha do CSV (o cabeçalho: numero_chamada, matricula, nome_aluno)
            fgetcsv($handle, 1000, ",");

            $sucessos = 0;
            $erros = 0;

            // Inicia uma Transação no banco para aumentar a performance drasticamente
            mysqli_begin_transaction($conn);

            // Lê linha por linha até o fim do arquivo
            while (($dados = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Mapeia as colunas conforme a planilha
                $numero_chamada = mysqli_real_escape_string($conn, $dados[0]);
                $matricula      = mysqli_real_escape_string($conn, $dados[1]);
                $nome_aluno     = mysqli_real_escape_string($conn, $dados[2]);

                // Pula linhas que estejam completamente vazias na planilha
                if (empty($matricula) && empty($nome_aluno)) {
                    continue;
                }

                // Monta o comando de inserção associando ao id_turma vindo do formulário
                $sqlInsert = "INSERT INTO alunos (numero_chamada, matricula, nome_aluno, fk_id_turma) 
                              VALUES ('$numero_chamada', '$matricula', '$nome_aluno', '$id_turma')";

                if (mysqli_query($conn, $sqlInsert)) {
                    $sucessos++;
                } else {
                    $erros++;
                }
            }

            // Grava de fato todas as inserções de uma só vez no banco
            mysqli_commit($conn);
            fclose($handle);

            // Define o feedback para o usuário
            if ($erros == 0) {
                $_SESSION['msg'] = "Sucesso! Foram importados <strong>$sucessos</strong> alunos para a turma.";
                $_SESSION['msg_tipo'] = "success";
            } else {
                $_SESSION['msg'] = "Importação parcial: $sucessos alunos importados com sucesso e $erros falhas ocorridas.";
                $_SESSION['msg_tipo'] = "warning";
            }

            header("Location: visualizar.php");
            exit();
        } else {
            $_SESSION['msg'] = "Erro ao abrir o arquivo para leitura.";
            $_SESSION['msg_tipo'] = "danger";
        }
    }
}

// Busca as turmas para alimentar o select do formulário
$sqlTurmas = "SELECT id_turma, serie_atual, identificador_curso, curso FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$resTurmas = mysqli_query($conn, $sqlTurmas);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Alunos | Manoteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }
        .card-custom {
            border-radius: 16px;
            border: none;
        }
        .form-control-custom {
            min-height: 45px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php include '../includes/menu.php'; ?>

    <div class="container mt-4 px-3">
        
        <div class="mb-4">
            <a href="visualizar.php" class="btn btn-outline-secondary shadow-sm d-inline-flex align-items-center gap-2" style="border-radius: 10px;">
                <i class="bi bi-arrow-left fs-5"></i> <span>Voltar à Listagem</span>
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                
                <div class="card shadow-sm card-custom">
                    <div class="card-body p-4 p-sm-5">
                        
                        <div class="text-center mb-4">
                            <i class="bi bi-file-earmark-excel text-success" style="font-size: 3.5rem;"></i>
                            <h3 class="fw-bold mt-2 text-dark">Importar Lista de Alunos</h3>
                            <p class="text-muted small">Faça o upload de uma planilha CSV para cadastrar a turma inteira de uma vez só.</p>
                        </div>

                        <?php if (isset($_SESSION['msg'])): ?>
                            <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show" role="alert">
                                <?= $_SESSION['msg'] ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['msg']); unset($_SESSION['msg_tipo']); ?>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">1. Selecione a Turma Alvo</label>
                                <select name="id_turma" class="form-select form-control-custom" required>
                                    <option value="">Escolha a Turma</option>
                                    <?php while ($t = mysqli_fetch_assoc($resTurmas)): ?>
                                        <option value="<?= $t['id_turma'] ?>">
                                            <?= $t['serie_atual'] ?>º <?= $t['identificador_curso'] ?> - <?= $t['curso'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">2. Selecione a Planilha (.csv)</label>
                                <input type="file" name="arquivo_csv" class="form-control form-control-custom" accept=".csv" required>
                                <div class="form-text text-muted" style="font-size: 0.78rem;">
                                    <i class="bi bi-info-circle me-1"></i> A planilha deve conter as colunas nesta ordem exata: Número de Chamada, Matrícula e Nome.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success py-2 fw-semibold shadow-sm" style="border-radius: 10px;">
                                    <i class="bi bi-cloud-upload me-2"></i>Iniciar Importação
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>