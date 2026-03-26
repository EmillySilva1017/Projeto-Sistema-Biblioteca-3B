<?php
session_start();
include('../includes/conexao.php');

//consluta sql para listar as turmas cadastradas
$query = "SELECT * FROM turmas ORDER BY serie_atual ASC, identificador_curso ASC";
$result = mysqli_query($conn, $query);
$lista_turma = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
    <section>
        <div class="container">
            <?php if (isset($_SESSION['mensagem'])): ?>
                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                    <?= $_SESSION['mensagem']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['mensagem']); ?>
            <?php endif; ?>
            <!--Botão de adicionar uma nova turma-->
            <div>
                <button class="float-end"><a href="form_turma.php">Add Turma</a></button>
            </div>

            <!--Lista das Turmas--->
            <h2>Turmas</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Série</th>
                        <th>Curso</th>
                        <th>Ano Atual</th>
                        <th>Ano Conclusão</th>
                        <th>Visualizar</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_turma as $turma) : ?>
                        <tr>
                            <td><?= $turma['serie_atual'] . "° ano"; ?></td>
                            <td><?= $turma['curso']; ?></td>
                            <td><?php echo date('Y'); ?></td>
                            <td><?= $turma['ano_conclusao'] ?></td>
                            <td>
                                <a href="visualizar_turma.php?id=<?= $turma['id_turma']; ?>" class="btn btn-sm btn-info">
                                    Visu</a>
                            </td>
                            <td>
                                <a href="editar.php?id=<?= $turma['id_turma']; ?>">Editar</a>
                                <a href="excluir.php?id=<?= $turma['id_turma']; ?>"
                                onclick="return confirm('Tem certeza que deseja excluir esta turma? Esta ação não pode ser desfeita.')">
                                Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>