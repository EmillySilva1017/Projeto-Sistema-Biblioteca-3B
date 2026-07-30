<?php 
session_start();
include('../includes/conexao.php'); // Ajuste o caminho da conexão se necessário

// TRAVA DE SEGURANÇA: Só o ADM cria contas no sistema!
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] !== 'adm') {
    header('Location: ../login/index.php');
    exit();
}

if(empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])){
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    $_SESSION['msg_tipo'] = "warning";
    header('Location: cadastrar_conta.php');
    exit();
}

$nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$nivel_novo = mysqli_real_escape_string($conn, $_POST['nivel_novo']);

$senha_pura = trim($_POST['senha']); 
$senha = password_hash($senha_pura, PASSWORD_DEFAULT);

// Verifica se o e-mail já existe
$sql = "SELECT count(*) AS total FROM usuario WHERE email = '$email' ";
$result = mysqli_query($conn, $sql);
$dados = mysqli_fetch_assoc($result);

if($dados['total'] > 0){
    $_SESSION['mensagem'] = "Email já cadastrado!";
    $_SESSION['msg_tipo'] = "warning";
    header('Location: cadastrar_conta.php');
    exit();
}

// Inserir incluindo a string do nível!
$sqlInserir = "INSERT INTO usuario (nome_user, email, senha, nivel)
VALUES ('$nome', '$email', '$senha', '$nivel_novo')";

if(mysqli_query($conn, $sqlInserir)){
    $_SESSION['mensagem'] = "Conta do funcionário criada com sucesso!";
    $_SESSION['msg_tipo'] = "success";
    header('Location: perfil.php'); // Volta para o perfil
    exit();
} else {
    $_SESSION['mensagem'] = "Erro ao cadastrar: " . mysqli_error($conn);
    $_SESSION['msg_tipo'] = "danger";
    header('Location: cadastrar_conta.php');
}
?>