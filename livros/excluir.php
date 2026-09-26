<?php
session_start();
require_once '../includes/verifica_login.php';
include '../includes/conexao.php';
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $sqlExcluir = "DELETE FROM livros WHERE id = $id";

    if (mysqli_query($conn, $sqlExcluir)) {
        $_SESSION['mensagem'] = "Livro excluído com sucesso!";
        $_SESSION['msg_tipo'] = "success";
        header('Location: visualizacao_livro.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao excluir: " . mysqli_error($conn);
        $_SESSION['msg_tipo'] = "danger";
        header("Location: visualizacao_livro.php");
        exit();
    }
} else {
    header('Location: visualizacao_livro.php');
    exit();
}