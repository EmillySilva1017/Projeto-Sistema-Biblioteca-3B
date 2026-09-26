<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */
require_once '../includes/verifica_admin.php';

// 1. TRAVA DE SEGURANÇA: Apenas Administrador pode resetar senhas!
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] !== 'adm') {
    $_SESSION['msg'] = "Acesso negado! Você não tem permissão.";
    $_SESSION['msg_tipo'] = "danger";
    header('Location: perfil.php');
    exit();
}

// 2. Verifica se o ID do usuário foi passado na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {

    $id_user_target = mysqli_real_escape_string($conn, $_GET['id']);

    // Opcional: Impedir que o ADM resete a própria senha por esse botão
    if ($id_user_target == $_SESSION['id_user']) {
        $_SESSION['msg'] = "Para alterar sua própria senha, use o botão 'Alterar Senha'.";
        $_SESSION['msg_tipo'] = "warning";
        header('Location: perfil.php');
        exit();
    }

    // 3. Define a senha padrão e criptografa
    $senha_padrao = "123456";
    $senha_hash = password_hash($senha_padrao, PASSWORD_DEFAULT);

    // 4. Atualiza no banco de dados
    $sql = "UPDATE usuario SET senha = '$senha_hash' WHERE id_user = '$id_user_target'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Senha resetada com sucesso! A nova senha temporária é: <strong>$senha_padrao</strong>";
        $_SESSION['msg_tipo'] = "success";
    } else {
        $_SESSION['msg'] = "Erro ao resetar a senha: " . mysqli_error($conn);
        $_SESSION['msg_tipo'] = "danger";
    }

} else {
    $_SESSION['msg'] = "Usuário inválido ou não especificado.";
    $_SESSION['msg_tipo'] = "danger";
}

// 5. Volta para a página de perfil
header('Location: perfil.php');
exit();