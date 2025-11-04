<?php 
/*  CONNECTION TO DATABASE   */

$dbhost = 'localhost';
$dbname = 'voitures';
$dbuser = 'root';
$dbpass = '';

try{
    $PDO = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbuser,$dbpass);
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e){
    die("Connection failed: " . $e->getMessage());
}

?>