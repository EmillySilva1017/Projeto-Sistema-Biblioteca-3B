<!--Sair da pagina-->
<?php 
    session_start();
    session_destroy();
    header('Location: ../cadastro/index.php');
    exit();
?>
