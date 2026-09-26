<?php
session_start();
require_once '../includes/verifica_login.php';
include '../includes/conexao.php';
/** @var mysqli $conn */

header('Content-Type: application/json; charset=utf-8');

// Valida o termo antes de consultar o banco
$termo = isset($_GET['q']) ? trim($_GET['q']) : '';
if (strlen($termo) < 2) {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
    exit();
}

// Busca títulos iniciados pelo termo e conta os exemplares de cada título
$termo = mysqli_real_escape_string($conn, $termo);
$sql = "SELECT titulo_livro AS titulo, COUNT(*) AS exemplares
        FROM livros
        WHERE titulo_livro LIKE '$termo%'
        GROUP BY titulo_livro
        ORDER BY titulo_livro
        LIMIT 10";
$resultado = mysqli_query($conn, $sql);

// Devolve os dados em JSON para o autocomplete da tela
$titulos = [];
if ($resultado) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        $titulos[] = $linha;
    }
}

echo json_encode($titulos, JSON_UNESCAPED_UNICODE);
exit();