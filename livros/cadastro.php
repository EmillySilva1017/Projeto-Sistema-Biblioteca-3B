<?php 
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Verificação de campos vazios
    if (empty($_POST['titulo']) || empty($_POST['autor']) || empty($_POST['n_registro'])) {
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        header('Location: cadastrar_livro.php');
        exit();
    }
    //Limpeza dos dados contra SQL Injection
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $editora = mysqli_real_escape_string($conn, $_POST['editora']);
    $ano_aquisicao = mysqli_real_escape_string($conn, $_POST['ano_aquisicao']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $n_registro = mysqli_real_escape_string($conn, $_POST['n_registro']);
    $cdd = mysqli_real_escape_string($conn, $_POST['cdd']);
    $cdu = mysqli_real_escape_string($conn, $_POST['cdu']);
    $selo = mysqli_real_escape_string($conn, $_POST['selo']);

    // Verificação para ver se já existe algum livro com esse número de registro
    $sqlCheck = "SELECT numero_registro FROM livros WHERE numero_registro = '$n_registro'";
    $resCheck = mysqli_query($conn, $sqlCheck);

    // Se o número de linhas retornadas for maior que 0, o número de registro já existe
    if (mysqli_num_rows($resCheck) > 0) {
        $_SESSION['mensagem'] = "Erro: Este N° de Registro já está cadastrado!";
        header('Location: cadastrar_livro.php');
        exit();
    }

    // Inserção no banco de dados
    $sqlInserir = " INSERT INTO livros (titulo_livro, autor, editora, ano_aquisicao, genero, numero_registro, cdd, cdu, selo)
    VALUES ('$titulo', '$autor', '$editora', '$ano_aquisicao', '$genero', '$n_registro', '$cdd', '$cdu', '$selo')";

    //Executamos e verificamos se deu certo
    if (mysqli_query($conn, $sqlInserir)) {
        $_SESSION['mensagem'] = "Livro cadastrado com sucesso!";
        header('Location: cadastrar_livro.php');
        exit();
    } else {
        // Se der erro no banco (ex: coluna com nome errado)
        $_SESSION['mensagem'] = "Erro ao salvar no banco: " . mysqli_error($conn);
        header('Location: cadastrar_livro.php');
        exit();
    }
}

?>