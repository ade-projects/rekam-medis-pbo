<?php
$host     = 'localhost';
$dbname   = 'db_remedis';
$username = 'root'; 
$password = '';     

try {
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>