<?php
session_start();
include '../includes/conexao.php';
/** @var mysqli $conn */
require_once '../includes/verifica_admin.php';

// 1. TRAVA DE SEGURANÇA: Apenas Administrador pode atualizar usuários
if (!isset($_SESSION['id_user']) || $_SESSION['nivel'] !== 'adm') {
    $_SESSION['msg'] = "Acesso negado!";
    $_SESSION['msg_tipo'] = "danger";
    header('Location: perfil.php');
    exit();
}

// 2. Verifica se a requisição veio via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitização e Captura dos dados
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $nivel = mysqli_real_escape_string($conn, $_POST['nivel_novo']);
    $senha = trim($_POST['senha']);

    // Validação de campos obrigatórios
    if (empty($id) || empty($nome) || empty($email) || empty($nivel)) {
        $_SESSION['msg'] = "Preencha todos os campos obrigatórios!";
        $_SESSION['msg_tipo'] = "warning";
        header("Location: editar_usuario.php?id=$id");
        exit();
    }

    // 3. Monta a SQL dinamicamente (se a senha for informada, altera a senha também)
    if (!empty($senha)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario 
                SET nome_user = '$nome', email = '$email', nivel = '$nivel', senha = '$senha_hash' 
                WHERE id_user = '$id'";
    } else {
        $sql = "UPDATE usuario 
                SET nome_user = '$nome', email = '$email', nivel = '$nivel' 
                WHERE id_user = '$id'";
    }

    // 4. Executa a atualização
    if (mysqli_query($conn, $sql)) {

        // Se o Administrador atualizou o PRÓPRIO nome ou e-mail, atualiza a sessão dele na hora
        if ($id == $_SESSION['id_user']) {
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['nivel'] = $nivel;
        }

        $_SESSION['msg'] = "Dados do usuário atualizados com sucesso!";
        $_SESSION['msg_tipo'] = "success";
        header('Location: perfil.php');
        exit();

    } else {
        $_SESSION['msg'] = "Erro ao atualizar no banco de dados: " . mysqli_error($conn);
        $_SESSION['msg_tipo'] = "danger";
        header("Location: editar_usuario.php?id=$id");
        exit();
    }

} else {
    header('Location: perfil.php');
    exit();
}