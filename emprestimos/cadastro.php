<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

// Proteção: Garante que o bibliotecário está logado
if (!isset($_SESSION['id_user'])) {
    $_SESSION['mensagem'] = "Erro: Usuário não autenticado.";
    header("Location: cadastro_emprest.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Coleta os dados vindos do formulário
    $fk_id_turma    = intval($_POST['fk_id_turma']);                     // ID da Turma
    $nome_aluno     = mysqli_real_escape_string($conn, $_POST['aluno']); // Nome do Aluno (Texto)
    $fk_id_livro    = intval($_POST['fk_id_livro']);                     // ID do Livro (vindo do campo hidden)
    $data_saida     = mysqli_real_escape_string($conn, $_POST['data_saida']);
    $data_prevista  = mysqli_real_escape_string($conn, $_POST['data_prevista']);
    
    // 2. Coleta o ID do bibliotecário logado na sessão
    $fk_id_user = intval($_SESSION['id_user']); 

    // Validação básica de segurança
    if ($fk_id_turma === 0 || empty($nome_aluno) || $fk_id_livro === 0) {
        $_SESSION['mensagem'] = "Erro: Dados incompletos ou inválidos no formulário.";
        header("Location: cadastro_emprest.php");
        exit;
    }

    //Trava de Segurança para que não haja empréstimos duplicados
    $sql_trava = "SELECT id_emprestimos FROM emprestimos 
                  WHERE fk_id_livro = $fk_id_livro 
                  AND status IN ('Pendente', 'Atrasado', 'Renovado') 
                  LIMIT 1";
    $res_trava = mysqli_query($conn, $sql_trava);

    if (mysqli_num_rows($res_trava) > 0) {
        $_SESSION['mensagem'] = "Erro: Este livro já se encontra emprestado no momento!";
        header("Location: cadastro_emprest.php");
        exit;
    }

    // Trava de Segurança para que não possa haver empréstimo para um aluno que esta com pendencias
    $sql_pendencias = "SELECT id_emprestimos FROM emprestimos 
                       WHERE nome_aluno = '$nome_aluno' 
                       AND status IN ('Pendente', 'Atrasado', 'Renovado') 
                       LIMIT 1";
    $res_pendencias = mysqli_query($conn, $sql_pendencias);

    if (mysqli_num_rows($res_pendencias) > 0) {
        $_SESSION['mensagem'] = "Erro: Este aluno possui empréstimos pendentes!";
        header("Location: cadastro_emprest.php");
        exit;
    }

    // 3. Executa o INSERT salvando a fk_id_turma junto
    $sqlInsert = "INSERT INTO emprestimos (nome_aluno, data_saida, data_prevista, fk_id_turma, fk_id_livro, fk_id_user) 
                  VALUES ('$nome_aluno', '$data_saida', '$data_prevista', $fk_id_turma, $fk_id_livro, $fk_id_user)";

    if (mysqli_query($conn, $sqlInsert)) {
        $_SESSION['mensagem'] = "Empréstimo cadastrado com sucesso!";
        header("Location: list_emprest.php");
        exit;
    } else {
        $_SESSION['mensagem'] = "Erro ao salvar no banco: " . mysqli_error($conn);
        header("Location: cadastro_emprest.php");
        exit;
    }

} else {
    header("Location: cadastro_emprest.php");
    exit;
}