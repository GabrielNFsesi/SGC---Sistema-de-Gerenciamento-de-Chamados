<?php
date_default_timezone_set('America/Sao_Paulo');
$host = "localhost";
$dbname = "sgc";
$usuario = "root";
$senha = "";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log($e->getMessage()); // detalhes só no log do servidor, não na tela
    http_response_code(500);
    die("Não foi possível conectar ao banco de dados.");
}
