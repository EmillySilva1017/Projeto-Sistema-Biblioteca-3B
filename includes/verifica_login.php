<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only staff accounts may access staff pages.
if (!isset($_SESSION['id_user']) || !in_array($_SESSION['nivel'] ?? '', ['adm', 'bibliotecario'], true)) {
    header('Location: ../login/login.php?role=bibliotecario&erro=restrito');
    exit();
}

// 2. Se o nome_user ainda não estiver na sessão, busca no banco pelo id_user
if (!isset($_SESSION['nome_user']) && isset($conn)) {
    $id_user = intval($_SESSION['id_user']);
    $sql_user = "SELECT nome_user FROM usuario WHERE id_user = $id_user LIMIT 1";
    $res_user = mysqli_query($conn, $sql_user);

    if ($res_user && mysqli_num_rows($res_user) > 0) {
        $dados_user = mysqli_fetch_assoc($res_user);
        $_SESSION['nome_user'] = $dados_user['nome_user'];
    }
}
?>