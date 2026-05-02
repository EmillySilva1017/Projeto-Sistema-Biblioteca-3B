<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Verificação de campos vazios
    if (empty($_POST['nome']) || empty($_POST['matricula'])) {
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        $_SESSION['tipo_mensagem'] = "danger";
        header('Location: cadastrar_aluno.php');
        exit();
    }
    //Limpeza dos dados contra SQL Injection
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $numero_chamada = mysqli_real_escape_string($conn, $_POST['numero_chamada']);
    $matricula = mysqli_real_escape_string($conn, $_POST['matricula']);
    $fk_id_turma = mysqli_real_escape_string($conn, $_POST['fk_id_turma']);

    // Verificação para ver se já existe algum aluno com essa matrícula
    $sqlCheck = "SELECT matricula FROM alunos WHERE matricula = '$matricula'";
    $resCheck = mysqli_query($conn, $sqlCheck);

    // Se o número de linhas retornadas for maior que 0, a matrícula já existe
    if (mysqli_num_rows($resCheck) > 0) {
        $_SESSION['mensagem'] = "Erro: Esta matrícula já está cadastrada!";
        $_SESSION['tipo_mensagem'] = "danger";
        header('Location: cadastrar_aluno.php');
        exit();
    }

    // Inserção no banco de dados
    $sqlInserir = " INSERT INTO alunos (nome_aluno, numero_chamada, matricula, fk_id_turma)
    VALUES ('$nome', '$numero_chamada', '$matricula', '$fk_id_turma')";

    //Executamos e verificamos se deu certo
    if (mysqli_query($conn, $sqlInserir)) {
        $_SESSION['mensagem'] = "Aluno cadastrado com sucesso!";
        header('Location: cadastrar_aluno.php');
        exit();
    } else {
        // Se der erro no banco (ex: coluna com nome errado)
        $_SESSION['mensagem'] = "Erro ao salvar no banco: " . mysqli_error($conn);
        header('Location: cadastrar_aluno.php');
        exit();
    }
}
