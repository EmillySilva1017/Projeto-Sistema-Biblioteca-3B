<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
    <section>
        <div class="container">
            <!-- Mensagem de cadastro inicio--->
            <?php
            if (isset($_SESSION['mensagem'])):
            ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $_SESSION['mensagem']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php unset($_SESSION['mensagem']); //limpa mensagem 
            endif;
            ?>
            <h2>Cadastro de Turmas</h2>
            <!--Formulário para cadastrar turma--->
            <form action="add_turma.php" method="POST">
                <!--Campo curso e id_curso juntos--->
                <div class="row">
                    <!--Campo Curso-->
                    <div class="col-md-6 mb-3">
                        <label for="curso">Curso</label>
                        <input type="text" class="form-control" name="curso">
                    </div>
                    <!--Campo Identificador do Curso-->
                    <div class="col-md-6 mb-3">
                        <label for="curso">Identificador Curso</label>
                        <select name="id_curso" id="" class="form-select">
                            <option value="" selected>Selecione</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                </div>
                <!--Campo da Série Atual-->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serie_atual">Série Atual</label>
                        <select name="serie_atual" class="form-select" required>
                            <option value="1">1º Ano</option>
                            <option value="2">2º Ano</option>
                            <option value="3">3º Ano</option>
                        </select>
                    </div>
                <!--Campo do Ano de Conclusão-->
                    <div class="col-md-6 mb-3">
                        <label for="ano_conclusao">Ano de Conclusão</label>
                        <input type="number" name="ano_conclusao" class="form-control" 
                        value="<?php echo date('Y');?>" required>
                        <small class="text-muted">Ano em que a turma finaliza o curso.</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Cadastrar Turma</button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>