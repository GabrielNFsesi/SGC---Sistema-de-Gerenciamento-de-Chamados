<?php
session_start();
require 'conexao.php';

// Proteção: Solicitante não pode acessar esta tela
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] == 'Solicitante') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_chamado = $_POST['id_chamado'];
    $solucao = $_POST['solucao'];
    
    // Salva a solução e já muda o status para Resolvido
    $sql = "UPDATE chamado SET solucao = :solucao, status = 'Resolvido' WHERE id_chamado = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':solucao' => $solucao, ':id' => $id_chamado]);
    
    header("Location: index.php");
    exit;
}

$id_chamado = $_GET['id'];
?>

<h2>Propor Solução para o Chamado</h2>
<form method="POST">
    <input type="hidden" name="id_chamado" value="<?= $id_chamado ?>">
    
    <label>Descreva a solução:</label><br>
    <textarea name="solucao" rows="5" cols="40" required></textarea><br><br>
    
    <button type="submit">Salvar Solução</button>
    <a href="index.php">Cancelar</a>
</form>