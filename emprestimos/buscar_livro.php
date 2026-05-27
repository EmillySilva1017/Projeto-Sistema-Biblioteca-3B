<?php
include('../includes/conexao.php');
/** @var mysqli $conn */

if (isset($_GET['n_registro'])) {
    $n_registro = mysqli_real_escape_string($conn, $_GET['n_registro']);
    
    // 1. Busca se o livro existe pelo número de registro
    $sql_livro = "SELECT id, titulo_livro FROM livros WHERE numero_registro = '$n_registro' LIMIT 1";
    $res_livro = mysqli_query($conn, $sql_livro);
    
    if ($row_livro = mysqli_fetch_assoc($res_livro)) {
        $id_livro = $row_livro['id'];
        $titulo = $row_livro['titulo_livro'];

        // 2. VERIFICAÇÃO: O livro está atualmente emprestado?
        // Procura por empréstimos que NÃO foram entregues ainda
        $sql_checa_status = "SELECT id_emprestimos FROM emprestimos 
                             WHERE fk_id_livro = $id_livro 
                             AND status IN ('Pendente', 'Atrasado', 'Renovado') 
                             LIMIT 1";
        $res_status = mysqli_query($conn, $sql_checa_status);

        if (mysqli_num_rows($res_status) > 0) {
            // O livro existe, mas está ocupado!
            echo json_encode([
                'sucesso' => false,
                'motivo' => 'indisponivel',
                'msg' => "Aviso: O livro '$titulo' já está emprestado e não foi devolvido ainda."
            ]);
        } else {
            // O livro existe e está livre para empréstimo
            echo json_encode([
                'sucesso' => true, 
                'id_livro' => $id_livro, 
                'titulo' => $titulo
            ]);
        }
    } else {
        // O livro não existe no acervo
        echo json_encode([
            'sucesso' => false,
            'motivo' => 'nao_encontrado',
            'msg' => 'Aviso: Nenhum livro encontrado com este Número de Registro.'
        ]);
    }
    exit;
}