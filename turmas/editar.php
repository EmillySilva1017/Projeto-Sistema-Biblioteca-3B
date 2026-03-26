<?php
session_start();
//include 'menu.php';
include '../includes/conexao.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do registro não fornecido para edição.";
    header('Location: index.php');
    exit();
}

 //sanitização do ID recebido
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Consulta para buscar os dados da turma
$sql_select = "SELECT * FROM turmas WHERE id_turma = $id";
$result = mysqli_query($conn, $sql_select);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['mensagem'] = "Turma não encontrada.";
    header('Location: index.php');
    exit();
}

$dados = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Turma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <section>
        <div class="container">
            <h2>Editar de Turma</h2>
            <!--Formulário para cadastrar turma--->
            <form action="atualizar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <!--Campo curso e id_curso juntos--->
                <div class="row">
                    <!--Campo Curso-->
                    <div class="col-md-6 mb-3">
                        <label for="curso">Curso</label>
                        <input type="text" class="form-control" name="curso" value="<?php echo htmlspecialchars($dados['curso']); ?>" required>
                    </div>
                    <!--Campo Identificador do Curso-->
                    <div class="col-md-6 mb-3">
                        <label for="id_curso">Identificador Curso</label>
                        <select name="id_curso" id="" class="form-select">
                            <option selected>Selecione</option>
                            <option value="A" <?php echo ($dados['identificador_curso'] == "A") ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo ($dados['identificador_curso'] == "B") ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo ($dados['identificador_curso'] == "C") ? 'selected' : ''; ?>>C</option>
                            <option value="D" <?php echo ($dados['identificador_curso'] == "D") ? 'selected' : ''; ?>>D</option>
                            <option value="E" <?php echo ($dados['identificador_curso'] == "E") ? 'selected' : ''; ?>>E</option>
                        </select>
                    </div>
                </div>
                <!--Campo da Série Atual-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serie_atual">Série Atual</label>
                        <select name="serie_atual" class="form-select" required>
                            <option value="1" <?php echo ($dados['serie_atual'] == "1") ? 'selected' : ''; ?>>1º Ano</option>
                            <option value="2" <?php echo ($dados['serie_atual'] == "2") ? 'selected' : ''; ?>>2º Ano</option>
                            <option value="3" <?php echo ($dados['serie_atual'] == "3") ? 'selected' : ''; ?>>3º Ano</option>
                        </select>
                    </div>
                <!--Campo do Ano de Conclusão-->
                    <div class="col-md-6 mb-3">
                        <label for="ano_conclusao">Ano de Conclusão</label>
                        <input type="number" name="ano_conclusao" class="form-control" 
                        value="<?php echo htmlspecialchars($dados['ano_conclusao']);?>" required>
                        <small class="text-muted">Ano em que a turma finaliza o curso.</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Editar Turma</button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>