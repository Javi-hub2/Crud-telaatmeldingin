<?php
if($_SERVER['HTTP_HOST'] === 'localhost') {
    $servername = "localhost";
    $database = "crud";
    $username = "root";
    $password = "";    
}
else
{
    $servername = "localhost";
    $database = "2214580-database1";
    $username = "telaat_1";
    $password = "Javi2007.";
}
    $conn = new PDO("mysql:host=$servername;dbname=$database",$username, $password);
    ?>
 