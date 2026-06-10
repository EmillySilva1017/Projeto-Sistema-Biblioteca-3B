<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if ((empty($_POST['nova_senha'])) || (empty($_POST['confirmar_senha']))) {
    $_SESSION['msg'] = "Preencha todos os campos!";
    header('Location: alterar_senha.php');
    exit();
}

$nova_senha = trim($_POST['nova_senha']);
$confirmar_senha = trim($_POST['confirmar_senha']);

if ($nova_senha !== $confirmar_senha) {
    $_SESSION['msg'] = "As senhas não coincidem!";
    header('Location: alterar_senha.php');
    exit();
}

$sql_confirmar = "SELECT senha FROM usuario WHERE email = '$_SESSION[email]'";
$result_confirmar = mysqli_query($conn, $sql_confirmar);
$row = mysqli_fetch_assoc($result_confirmar);

// VERIFICAÇÃO 
if (password_verify($nova_senha, $row['senha'])) {
    $_SESSION['msg'] = "A nova senha não pode ser igual à senha atual!";
    header('Location: alterar_senha.php');
    exit();
}

// SENHA CRIPTOGRAFADA
$nova_senha_criptografada = password_hash($nova_senha, PASSWORD_DEFAULT);

// ALTERAÇÃO DA SENHA NO BANCO
$email_sessao = mysqli_real_escape_string($conn, $_SESSION['email']);
$alterar_senha_sql = "UPDATE usuario SET senha = '$nova_senha_criptografada' WHERE email = '$email_sessao' ";

if (mysqli_query($conn, $alterar_senha_sql)) {
    unset($_SESSION['email']);
    $_SESSION['mensagem'] = "Senha alterada com sucesso!";
    header('Location: index.php');
    exit();
} else {
    $_SESSION['msg'] = "Erro ao atualizar senha no banco de dados.";
    header('Location: alterar_senha.php');
    exit();
}

?>