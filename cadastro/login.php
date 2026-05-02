<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if (empty($_POST['email']) || empty($_POST['senha'])) {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header('Location: index.php');
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$senha = mysqli_real_escape_string($conn, $_POST['senha']);

$query = "SELECT id_user, nome_user, email, nivel FROM usuario WHERE email = '$email' AND senha = MD5('$senha')";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Erro na consulta: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {
    $dados = mysqli_fetch_assoc($result);

    $_SESSION['id_user'] = $dados['id_user'];
    $_SESSION['nome'] = $dados['nome_user'];
    $_SESSION['email'] = $dados['email'];
    $_SESSION['nivel'] = $dados['nivel'];

    //verificação do destino da página
    if ($dados['nivel'] == 1) {
        header('Location: ../painel/painel_adm.php'); // redireciona para a página do administrador
    } else {
        header('Location: ../painel/visao_aluno.php'); // redireciona para a página de visualização dos alunos
    }
    exit();
} else {
    $_SESSION['nao_autenticado'] = true;
    $_SESSION['mensagem'] = "Email ou senha incorretos!";
    header('Location: index.php');
    exit();
}
