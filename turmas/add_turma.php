<?php 
session_start();
include('../includes/conexao.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //verificação se os campos não estao vazios
    if(empty($_POST['curso']) || empty($_POST['id_curso'])){
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        $_SESSION['tipo_mensagem'] = "danger";
        header('Location: form_turma.php');
        exit();
    }
    //limpeza dos dados contra o comando SQL Injection 
    $curso = mysqli_real_escape_string($conn, $_POST['curso']);
    $id_curso = mysqli_real_escape_string($conn, $_POST['id_curso']);
    $serie_atual = mysqli_real_escape_string($conn, $_POST['serie_atual']);
    $ano_conclusao = mysqli_real_escape_string($conn, $_POST['ano_conclusao']);

    $sqlInserir = "INSERT INTO turmas (curso, identificador_curso, ano_conclusao, serie_atual)
    VALUES('$curso', '$id_curso', '$ano_conclusao', '$serie_atual')";

    //Executamos e verificamos se deu certo
    if (mysqli_query($conn, $sqlInserir)){
        $_SESSION['mensagem'] = "Turma cadastrada com sucesso!";
        header('Location: form_turma.php');
        exit();
    } else {
        // Se der erro no banco (ex: coluna com nome errado)
        $_SESSION['mensagem'] = "Erro ao salvar no banco: " . mysqli_error($conn);
        header('Location: form_turma.php');
        exit();
    }

}else{
    // Se alguém tentar acessar o arquivo direto pelo navegador sem preencher o formulário
    header('Location: form_turma.php');
    exit();
}

?>