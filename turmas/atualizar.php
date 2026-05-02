<?php 
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitização
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $curso = mysqli_real_escape_string($conn, $_POST['curso']);
    $id_curso = mysqli_real_escape_string($conn, $_POST['id_curso']);
    $serie_atual = mysqli_real_escape_string($conn, $_POST['serie_atual']);
    $ano_conclusao = mysqli_real_escape_string($conn, $_POST['ano_conclusao']);

    // query para atualização dos dados
    $sqlAtualizar = " UPDATE turmas SET
                    curso = '$curso',
                    identificador_curso = '$id_curso',
                    ano_conclusao = '$ano_conclusao',
                    serie_atual = '$serie_atual'
                    WHERE id_turma = $id";

    // 4. Executa e define a mensagem de retorno
    if(mysqli_query($conn, $sqlAtualizar)){
        // Sucesso: Guarda a mensagem e vai para a LISTA
        $_SESSION['mensagem'] = "Cadastro da turma <strong>$serie_atual $id_curso</strong> atualizado com sucesso!";
        header('Location: index.php'); 
        exit();
    } else {
        // Erro: Guarda o erro e volta para o formulário de EDIÇÃO
        $_SESSION['mensagem'] = "Erro ao atualizar: " . mysqli_error($conn);
        header("Location: editar.php?id=$id"); 
        exit();
    }
} else {
    // Se alguém tentar acessar esse arquivo direto pelo link, manda de volta
    header('Location: index.php');
    exit();
}

?>