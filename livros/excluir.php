<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $sqlExcluir = "DELETE FROM livros WHERE id = $id";
    
    if(mysqli_query($conn, $sqlExcluir)){
        $_SESSION['mensagem'] = "Livro excluído com sucesso!";
        header('Location: visualizacao_livro.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir: " . mysqli_error($conn);
        header("Location: visualizacao_livro.php");
        exit();
    }
} else {
    header('Location: visualizacao_livro.php');
    exit();
}