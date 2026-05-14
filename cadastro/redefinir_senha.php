<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if ((empty($_POST['email']))) {
    $_SESSION['mensagem'] = "Preencha o campo de email!";
    header('Location: esqueceu_senha.php');
    exit();
}

$email = mysqli_real_escape_string($conn, trim($_POST['email']));

//verifica se o email é de verdade ou não
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensagem'] = "Email inválido!";
    header('Location: esqueceu_senha.php');
    exit();
}

// Verifica se o email existe
$sql = "SELECT count(*) AS total FROM usuario WHERE email = '$email' ";
$result = mysqli_query($conn, $sql);
$dados = mysqli_fetch_assoc($result);
if ($dados['total'] == 0) {
    $_SESSION['mensagem'] = "Email não encontrado!";
    header('Location: esqueceu_senha.php');
    exit();
} else {
    $_SESSION['email'] = $email; // Salva o email para o próximo script usar
    header('Location: alterar_senha.php');
    exit();
}


?>