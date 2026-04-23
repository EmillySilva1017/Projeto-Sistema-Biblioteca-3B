<?php 
session_start();
include('../includes/conexao.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitização
    $id = mysqli_real_escape_string($conn, $_POST['id_aluno']);
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $numero_chamada = mysqli_real_escape_string($conn, $_POST['numero_chamada']);
    $fk_id_turma = mysqli_real_escape_string($conn, $_POST['fk_id_turma']);
    $matricula = mysqli_real_escape_string($conn, $_POST['matricula']);

    // query para atualização dos dados
    $sqlAtualizar = " UPDATE alunos SET
                    nome_aluno = '$nome',
                    numero_chamada = '$numero_chamada',
                    matricula = '$matricula',
                    fk_id_turma = '$fk_id_turma'
                    WHERE id_aluno = $id";

    // 4. Executa e define a mensagem de retorno
    if(mysqli_query($conn, $sqlAtualizar)){
        // Sucesso: Guarda a mensagem e vai para a LISTA
        $_SESSION['msg'] = "Cadastro do aluno <strong>$nome</strong> atualizado com sucesso!";
        header('Location: visualizar.php?id=' . $fk_id_turma); 
        exit();
    } else {
        // Erro: Guarda o erro e volta para o formulário de EDIÇÃO
        $_SESSION['msg'] = "Erro ao atualizar: " . mysqli_error($conn);
        header("Location: editar_aluno.php?id=$id"); 
        exit();
    }
} else {
    // Se alguém tentar acessar esse arquivo direto pelo link, manda de volta
    header('Location: visualizar.php');
    exit();
}

?>