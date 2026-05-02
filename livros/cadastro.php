<?php 
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Verificação de campos vazios
    if (empty($_POST['titulo']) || empty($_POST['autor']) || empty($_POST['isbn'])) {
        $_SESSION['mensagem'] = "Preencha todos os campos!";
        $_SESSION['tipo_mensagem'] = "danger";
        header('Location: cadastrar_livro.php');
        exit();
    }
    //Limpeza dos dados contra SQL Injection
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);

    // Verificação para ver se já existe algum livro com esse ISBN
    $sqlCheck = "SELECT isbn FROM livros WHERE isbn = '$isbn'";
    $resCheck = mysqli_query($conn, $sqlCheck);

    // Se o número de linhas retornadas for maior que 0, o ISBN já existe
    if (mysqli_num_rows($resCheck) > 0) {
        $_SESSION['mensagem'] = "Erro: Este ISBN já está cadastrado!";
        $_SESSION['tipo_mensagem'] = "danger";
        header('Location: cadastrar_livro.php');
        exit();
    }

    // Inserção no banco de dados
    $sqlInserir = " INSERT INTO livros (titulo, autor, isbn)
    VALUES ('$titulo', '$autor', '$isbn')";

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