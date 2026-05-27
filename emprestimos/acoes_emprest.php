<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if (!isset($_SESSION['id_user'])) {
    $_SESSION['mensagem'] = "Erro: Acesso não autorizado.";
    header("Location: list_emprest.php");
    exit;
}

$acao = isset($_GET['acao']) ? $_GET['acao'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    $_SESSION['mensagem'] = "Erro: Registro inválido.";
    header("Location: list_emprest.php");
    exit;
}

date_default_timezone_set('America/Fortaleza');
$hoje = date('Y-m-d H:i:s');

switch ($acao) {
    case 'devolver':
        // Atualiza a data real de devolução e muda o status para Entregue
        $sql = "UPDATE emprestimos SET data_devolucao = '$hoje', status = 'Entregue' WHERE id_emprestimos = $id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Livro devolvido com sucesso!";
        }
        break;

    case 'renovar':
        // Adiciona mais 7 dias a partir de hoje para a nova data prevista e marca como Renovado
        $nova_data_prevista = date('Y-m-d', strtotime('+7 days'));
        $sql = "UPDATE emprestimos SET data_prevista = '$nova_data_prevista', status = 'Renovado' WHERE id_emprestimos = $id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Empréstimo renovado com sucesso por mais 7 dias!";
        }
        break;

    case 'excluir':
        // Remove de vez o registro histórico
        $sql = "DELETE FROM emprestimos WHERE id_emprestimos = $id";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['mensagem'] = "Histórico de empréstimo excluído do sistema.";
        }
        break;
}

header("Location: list_emprest.php");
exit;