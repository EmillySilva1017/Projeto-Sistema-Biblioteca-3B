<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $n_registro = mysqli_real_escape_string($conn, $_POST['n_registro']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $editora = mysqli_real_escape_string($conn, $_POST['editora']);
    $ano_aquisicao = mysqli_real_escape_string($conn, $_POST['ano_aquisicao']);
    $cdd = mysqli_real_escape_string($conn, $_POST['cdd']);
    $cdu = mysqli_real_escape_string($conn, $_POST['cdu']);
    $selo = mysqli_real_escape_string($conn, $_POST['selo']);    
    
    $sqlAtualizar = "UPDATE livros SET 
                    titulo_livro = '$titulo',
                    numero_registro = '$n_registro',
                    autor = '$autor',
                    genero = '$genero',
                    editora = '$editora',
                    ano_aquisicao = '$ano_aquisicao',
                    cdd = '$cdd',
                    cdu = '$cdu',
                    selo = '$selo'
                    WHERE id = $id";
    
    // 4. Executa e define a mensagem de retorno
    if(mysqli_query($conn, $sqlAtualizar)){
        $_SESSION['mensagem'] = "Livro <strong>$titulo</strong> de registro <strong>$n_registro</strong> atualizado com sucesso!";
        header('Location: visualizacao_livro.php'); 
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao atualizar: " . mysqli_error($conn);
        header("Location: editar.php?id=$id"); 
        exit();
    }
} else {
    // Se alguém tentar acessar esse arquivo direto pelo link, manda de volta
    header('Location: visualizacao_livro.php');
    exit();
}