<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */
require_once '../includes/verifica_admin.php';

// TRAVA: Apenas ADM pode deletar
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] !== 'adm') {
    $_SESSION['msg'] = "Acesso negado!";
    $_SESSION['msg_tipo'] = "danger";
    header('Location: perfil.php');
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_target = mysqli_real_escape_string($conn, $_GET['id']);

    // Regra de Ouro: Impedir que o ADM delete a si próprio!
    if ($id_target == $_SESSION['id_user']) {
        $_SESSION['msg'] = "Você não pode deletar a sua própria conta de Administrador!";
        $_SESSION['msg_tipo'] = "warning";
        header('Location: perfil.php');
        exit();
    }

    $sql = "DELETE FROM usuario WHERE id_user = '$id_target'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['msg'] = "Funcionário removido com sucesso!";
        $_SESSION['msg_tipo'] = "success";
    } else {
        $_SESSION['msg'] = "Erro ao remover funcionário: " . mysqli_error($conn);
        $_SESSION['msg_tipo'] = "danger";
    }
}

header('Location: perfil.php');
exit();