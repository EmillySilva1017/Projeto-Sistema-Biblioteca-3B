<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (($_SESSION['nivel'] ?? '') !== 'aluno' || empty($_SESSION['id_user']) || empty($_SESSION['nome_aluno'])) {
    header('Location: ../login/login.php?role=aluno&erro=restrito');
    exit();
}