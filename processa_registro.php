<?php
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; 
    $perfil = 'Solicitante'; // Força o perfil base por questões de segurança

    // Verifica se o e-mail já existe para evitar duplicados
    $sql_verifica = "SELECT id_usuario FROM usuario WHERE email = :email";
    $stmt_verifica = $pdo->prepare($sql_verifica);
    $stmt_verifica->execute([':email' => $email]);

    if ($stmt_verifica->fetch()) {
        echo "Este e-mail já está registado! <a href='registo.php'>Tentar outro</a> ou <a href='login.php'>Fazer login</a>";
        exit;
    }

    // Insere o novo utilizador na base de dados
    $sql = "INSERT INTO usuario (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha, ':perfil' => $perfil])) {
        // Registo bem-sucedido, envia para o login
        header("Location: login.php");
        exit;
    } else {
        echo "Erro ao criar conta. <a href='registo.php'>Tentar novamente</a>";
    }
}
?>