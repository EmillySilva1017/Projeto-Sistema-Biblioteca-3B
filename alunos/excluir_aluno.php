<?php
session_start();
include '../includes/conexao.php';

// Verifica se o ID foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do aluno não fornecido.";
    header('Location: ../turmas/visualizar_turma.php');
    exit();
}

// Sanitiza o ID recebido
$id = mysqli_real_escape_string($conn, trim($_GET['id']));

// Antes de excluir, vamos pegar o ID da turma para saber para onde voltar
$sql_busca = "SELECT fk_id_turma FROM alunos WHERE id_aluno = $id";
$res_busca = mysqli_query($conn, $sql_busca);
$aluno = mysqli_fetch_assoc($res_busca);
$id_turma_retorno = $aluno['fk_id_turma'];

// Consulta SQL para deletar o registro
$sql_delete = "DELETE FROM alunos WHERE id_aluno = $id";

if (mysqli_query($conn, $sql_delete)){
    $_SESSION['msg'] = "Aluno excluído com sucesso!";
} else {
    $_SESSION['msg'] = "Erro ao excluir: <br>" . mysqli_error($conn);
}

mysqli_close($conn);

// Redireciona de volta para a lista dos alunos
header("Location: ../turmas/visualizar_turma.php?id=$id_turma_retorno");
exit();
?>