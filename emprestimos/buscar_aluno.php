<?php
include('../includes/conexao.php');
/** @var mysqli $conn */

if (isset($_GET['id_turma'])) {
    $id_turma = intval($_GET['id_turma']);
    
    // Busca apenas os alunos da turma enviada pelo JS
    $sql = "SELECT id_aluno, nome_aluno FROM alunos WHERE fk_id_turma = $id_turma ORDER BY nome_aluno ASC";
    $result = mysqli_query($conn, $sql);
    
    $alunos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $alunos[] = $row;
    }
    
    // Devolve o resultado em formato JSON para o JS ler
    echo json_encode($alunos);
    exit;
}