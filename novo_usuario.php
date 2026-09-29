<?php
session_start(); // Precisa ser a PRIMEIRA linha do arquivo!
require 'conexao.php';

// Verifica se existe a sessão e converte o perfil para minúsculo e sem espaços para comparar com segurança
$perfil_logado = isset($_SESSION['perfil']) ? trim(strtolower($_SESSION['perfil'])) : '';

// Proteção: Apenas Administradores acessam aqui
if ($perfil_logado !== 'administrador') {
    die("Acesso negado. Apenas administradores podem criar usuários.");
}

// Lógica para salvar o novo usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha']; 
    $novo_perfil = $_POST['perfil'];

    $sql = "INSERT INTO usuario (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha, ':perfil' => $novo_perfil]);
    
    echo "<h3>Usuário criado com sucesso!</h3>";
    echo "<a href='index.php'><button>Voltar ao Painel</button></a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>SGC - Criar Usuário</title>
</head>
<body>
    <h2>Criar Novo Usuário (Acesso Restrito ao Administrador)</h2>
    <form method="POST">
        <label>Nome Completo:</label><br>
        <input type="text" name="nome" required><br><br>
        
        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>
        
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        
        <label>Perfil do Usuário:</label><br>
        <select name="perfil">
            <option value="Solicitante">Solicitante</option>
            <option value="Atendente">Atendente</option>
            <option value="Administrador">Administrador</option>
        </select><br><br>
        
        <button type="submit">Cadastrar Usuário</button>
        <a href="index.php"><button type="button">Cancelar e Voltar</button></a>
    </form>
</body>
</html>