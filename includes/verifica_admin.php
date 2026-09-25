<?php
require_once __DIR__ . '/verifica_login.php';

if (($_SESSION['nivel'] ?? '') !== 'adm') {
    header('Location: ../painel/painel_adm.php');
    exit();
}