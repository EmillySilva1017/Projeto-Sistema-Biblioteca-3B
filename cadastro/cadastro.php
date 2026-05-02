<?php 
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Verificação para saber se os campos estão ou não vazios
if(empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])){
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header('Location: cadastrar_conta.php');
    exit();
}

// Sanitização
$nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$senha = mysqli_real_escape_string($conn, trim($_POST['senha']));

// Verifica se jáa existe alguém com este email
$sql = "SELECT count(*) AS total FROM usuario WHERE email = '$email' ";
$result = mysqli_query($conn, $sql);
$dados = mysqli_fetch_assoc($result);

if($dados['total'] > 0){
    $_SESSION['mensagem'] = "Email já cadastrado!";
    header('Location: cadastrar_conta.php');
    exit();
}

//Inserir um novo usuário
$sqlInserir = "INSERT INTO usuario (nome_user, email, senha)
VALUES ('$nome', '$email', MD5('$senha'))";

if(mysqli_query($conn, $sqlInserir)){
    $_SESSION['mensagem'] = "Cadastro realizado com sucesso! Faça login!";
    header('Location: index.php');
    exit();
}else{
    $_SESSION['mensagem'] = "Erro ao cadastrar, tente novamente." . mysqli_error($conn);
    header('Location: cadastrar_conta.php');
}

?>