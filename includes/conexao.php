<?php 
### CONEXÃO COM O BANCO
    define('HOST', 'localhost');
    define('USUARIO', 'root');
    define('SENHA', '');
    define('BD', 'sistema_biblioteca');

    $conn = mysqli_connect(HOST, USUARIO, SENHA, BD) or die('Não foi possível conectar ao banco!!');
?>