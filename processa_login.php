<?php
session_start(); // Inicia a sessão no PHP
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco
    $sql = "SELECT id_usuario, nome, perfil FROM usuario WHERE email = :email AND senha = :senha";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email, ':senha' => $senha]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Se achou, guarda os dados na sessão
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['perfil'];

        // Redireciona para o painel principal
        header("Location: index.php");
        exit;
    } else {
        echo "E-mail ou senha incorretos! <a href='login.php'>Tentar novamente</a>";
    }
}
?>