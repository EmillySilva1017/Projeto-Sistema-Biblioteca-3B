<?php
session_start();
include('../includes/conexao.php');
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = isset($_POST['role']) ? $_POST['role'] : 'aluno';

    if ($role === 'aluno') {
        // --- PORTAL DO ALUNO (SÓ MATRÍCULA) ---
        if (empty($_POST['matricula'])) {
            $_SESSION['mensagem'] = "Preencha o campo de matrícula!";
            header('Location: login.php?role=aluno');
            exit();
        }

        $matricula = mysqli_real_escape_string($conn, $_POST['matricula']);

        // Busca na sua tabela separada de alunos
        // ATENÇÃO: Verifique se na sua tabela 'alunos' os campos são id_aluno, nome e matricula
        $sql = "SELECT * FROM alunos WHERE matricula = '$matricula' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $dados_aluno = mysqli_fetch_assoc($result);

            // Login do Aluno Autorizado (Sem Senha)
            $_SESSION['id_user'] = $dados_aluno['id_aluno']; // Ajuste se for apenas 'id'
            $_SESSION['nome_aluno'] = $dados_aluno['nome_aluno'];
            $_SESSION['nivel'] = 'aluno';

            header('Location: ../aluno/dashboard_aluno.php');
            exit();
        } else {
            $_SESSION['mensagem'] = "Matrícula não encontrada!";
            $_SESSION['msg_tipo'] = 'danger';
            header('Location: login.php?role=aluno');
            exit();
        }

    } else {
        $usuario_input = $_POST['usuario'] ?? $_POST['email'] ?? '';
        // --- PORTAL ADM OU BIBLIOTECÁRIO (EMAIL + SENHA CRIPTOGRAFADA) ---
        if (empty($usuario_input) || empty($_POST['senha'])) {
            $_SESSION['mensagem'] = "Preencha todos os campos!";
            $_SESSION['msg_tipo'] = 'warning';
            header("Location: login.php?role=$role");
            exit();
        }

        $email = mysqli_real_escape_string($conn, $usuario_input);
        $senha = $_POST['senha'];

        // Busca na tabela 'usuario' filtrando pelo email e pela string do nível ('adm' ou 'bibliotecario')
        $query = "SELECT * FROM usuario WHERE email = '$email' AND nivel = '$role'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $dados = mysqli_fetch_assoc($result);

            // Verifica a senha usando a função nativa para senhas criptografadas
            if (password_verify($senha, $dados['senha'])) {

                $_SESSION['id_user'] = $dados['id_user'];
                $_SESSION['nome_user'] = $dados['nome_user']; // Nome correto salvo na sessão!
                $_SESSION['email'] = $dados['email'];
                $_SESSION['nivel'] = $dados['nivel'];

                header('Location: ../painel/painel_adm.php');
                exit();
            }
        }

        // Se errou e-mail, senha ou tentou entrar no portal errado
        $_SESSION['mensagem'] = "E-mail ou senha incorretos!";
        $_SESSION['msg_tipo'] = "danger";
        header("Location: login.php?role=$role");
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}