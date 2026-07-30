<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificação de segurança dupla
    $sql_check = "SELECT COUNT(*) AS total FROM usuario WHERE nivel = 'adm'";
    $res_check = mysqli_query($conn, $sql_check);
    $total_adms = ($res_check) ? mysqli_fetch_assoc($res_check)['total'] : 0;

    if ($total_adms > 0) {
        $_SESSION['mensagem'] = "Ação não permitida! O Administrador Master já existe.";
        $_SESSION['msg_tipo'] = "warning";
        header('Location: index.php');
        exit();
    }

    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        $_SESSION['msg_tipo'] = "warning";
        header('Location: cadastrar_master.php');
        exit();
    }

    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $senha_pura = trim($_POST['senha']);
    $senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

    // Inserção forçando o nível 'adm'
    $sql = "INSERT INTO usuario (nome_user, email, senha, nivel) VALUES ('$nome', '$email', '$senha_hash', 'adm')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['mensagem'] = "Administrador Master criado com sucesso! Faça login.";
        $_SESSION['msg_tipo'] = "success";
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['mensagem'] = "Erro ao gravar no banco: " . mysqli_error($conn);
        $_SESSION['msg_tipo'] = "danger";
        header('Location: cadastrar_master.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}