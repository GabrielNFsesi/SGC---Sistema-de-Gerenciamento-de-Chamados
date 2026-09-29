<?php
require 'conexao.php';

$id_chamado = $_GET['id'] ?? null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_chamado_post = $_POST['id_chamado'];
    $novo_status = $_POST['status'];
    
    // Atualiza o status no banco de dados
    $sql = "UPDATE chamado SET status = :status WHERE id_chamado = :id_chamado";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $novo_status, ':id_chamado' => $id_chamado_post]);
    
    // Obs: Pela regra RN11, você deveria depois inserir essa alteração na tabela `historico` 
    // com o "status_anterior", "status_novo" e "acao".
    
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Alterar Status do Chamado</h2>
    <form action="alterar_status.php" method="POST">
        <input type="hidden" name="id_chamado" value="<?= htmlspecialchars($id_chamado) ?>">
        <label>Novo Status:</label>
        <select name="status">
            <option value="Aberto">Aberto</option>
            <option value="Em atendimento">Em atendimento</option>
            <option value="Resolvido">Resolvido</option>
        </select><br><br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>