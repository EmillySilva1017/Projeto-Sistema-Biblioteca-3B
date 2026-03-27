<?php
session_start();
include('../includes/conexao.php');

// 1. Quem era do 3º ano (concluintes), a série vira 0
$sql_concluir = "UPDATE turmas SET serie_atual = 0 WHERE serie_atual = 3";
mysqli_query($conn, $sql_concluir);

// 2. Quem era do 2º ano vira 3º ano
$sql_para_terceiro = "UPDATE turmas SET serie_atual = 3 WHERE serie_atual = 2";
mysqli_query($conn, $sql_para_terceiro);

// 3. Quem era do 1º ano vira 2º ano
$sql_para_segundo = "UPDATE turmas SET serie_atual = 2 WHERE serie_atual = 1";
mysqli_query($conn, $sql_para_segundo);

// Mensagem de sucesso para o seu alerta no index.php
$_SESSION['mensagem'] = "Ano Letivo Atualizado! As turmas foram promovidas com sucesso.";

mysqli_close($conn);

// Volta para a sua listagem
header("Location: index.php");
exit();
?>