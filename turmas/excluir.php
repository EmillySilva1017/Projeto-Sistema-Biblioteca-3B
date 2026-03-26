<?php
session_start();
include '../includes/conexao.php';

// Verifica se o ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do registro não fornecido.";
    header('Location: index.php');
    exit();
}

// Sanitiza o ID recebido
$id = mysqli_real_escape_string($conn, trim($_GET['id']));

// Consulta SQL para deletar o registro
$sql_delete = "DELETE FROM turmas WHERE id_turma = $id";

if (mysqli_query($conn, $sql_delete)){
    $_SESSION['mensagem'] = "Turma excluída com sucesso!";
} else {
    $_SESSION['mensagem'] = "Erro ao excluir: <br>" . mysqli_error($conn);
}

mysqli_close($conn);

// Redireciona de volta para a lista das turmas
header('Location: index.php');
exit();
?>