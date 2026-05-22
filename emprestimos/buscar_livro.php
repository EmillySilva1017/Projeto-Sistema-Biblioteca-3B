<?php
include('../includes/conexao.php');
/** @var mysqli $conn */

if (isset($_GET['n_registro'])) {
    // Sanitiza o dado recebido para proteção
    $n_registro = mysqli_real_escape_string($conn, $_GET['n_registro']);
    
    // Altere "livros" e os nomes das colunas se no seu banco for diferente
    // Com base no seu SQL, a tabela chama-se 'livros'
    $sql = "SELECT id, titulo_livro FROM livros WHERE numero_registro = '$n_registro' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Encontrou o livro, devolve o título e o id dele
        echo json_encode([
            'sucesso' => true, 
            'id_livro' => $row['id'], 
            'titulo' => $row['titulo_livro']
        ]);
    } else {
        // Não encontrou
        echo json_encode(['sucesso' => false]);
    }
    exit;
}