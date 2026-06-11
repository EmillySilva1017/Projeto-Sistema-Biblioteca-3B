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
        // 1. Primeiro, buscamos a data_prevista atual deste empréstimo no banco de dados
        $sql_busca_data = "SELECT data_prevista FROM emprestimos WHERE id_emprestimos = $id LIMIT 1";
        $res_busca_data = mysqli_query($conn, $sql_busca_data);
        
        if ($row_data = mysqli_fetch_assoc($res_busca_data)) {
            $data_prevista_atual = $row_data['data_prevista'];
            
            // 2. Adiciona exatamente 7 dias em cima da data prevista original
            $nova_data_prevista = date('Y-m-d', strtotime($data_prevista_atual . ' + 7 days'));
            
            // 3. Atualiza o banco de dados com a nova data e muda o status para 'Renovado'
            $sql = "UPDATE emprestimos 
                    SET data_prevista = '$nova_data_prevista', status = 'Renovado' 
                    WHERE id_emprestimos = $id";
                    
            if (mysqli_query($conn, $sql)) {
                $_SESSION['mensagem'] = "Empréstimo renovado com sucesso por mais 7 dias!";
            } else {
                $_SESSION['mensagem'] = "Erro ao tentar renovar o empréstimo.";
            }
        } else {
            $_SESSION['mensagem'] = "Erro: Empréstimo não encontrado.";
        }
        break;
}

header("Location: list_emprest.php");
exit;