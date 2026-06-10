<!-- Protege as paginas, a fim de que ninguém acesse o painel sem ter logado-->
<?php
session_start();
if(!isset($_SESSION['id_user'])){
    header('Location: index.php'); // Se não logou, expulsa para o login
    exit();
}
?>