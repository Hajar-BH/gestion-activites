<?php  
    $dsn = 'mysql:host=localhost;dbname=monprojet;charset=UTF8'; 
    $username = 'root';
    $password = '';
    $pdo = new PDO($dsn, $username, $password) or die("Pb de connexion !");
?>
