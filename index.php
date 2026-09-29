<?php
session_start(); // Sempre que usar sessão, precisa ter isso no topo!

// Verifica se o usuário está logado. Se não estiver, manda pro login.
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

require 'conexao.php';

$id_logado = $_SESSION['id_usuario'];
$perfil_logado = $_SESSION['perfil'];

// Lógica de visualização baseada no perfil:
if ($perfil_logado == 'Solicitante') {
    // O Solicitante só vê os SEUS chamados e selecionamos a coluna 'solucao'
    $sql = "SELECT c.id_chamado, c.numero_chamado, c.titulo, c.prioridade, c.status, c.data_abertura, c.solucao, u.nome as solicitante 
            FROM chamado c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            WHERE c.id_usuario = :id_usuario
            ORDER BY c.data_abertura DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_usuario' => $id_logado]);
} else {
    // Atendente e Administrador veem TODOS os chamados
    $sql = "SELECT c.id_chamado, c.numero_chamado, c.titulo, c.prioridade, c.status, c.data_abertura, c.solucao, u.nome as solicitante 
            FROM chamado c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            ORDER BY c.data_abertura DESC";
    $stmt = $pdo->query($sql);
}

$chamados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>SGC - Consultar Chamados</title>
</head>
<body>
    <h1>Sistema de Gerenciamento de Chamados (SGC)</h1>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?> (<?= htmlspecialchars($_SESSION['perfil']) ?>)</h2>
    
    <!-- Painel de Controle baseado no Perfil -->
    <div style="margin-bottom: 20px;">
        <?php if ($perfil_logado == 'Administrador'): ?>
            <a href="novo_usuario.php"><button>Cadastrar Novo Usuário</button></a>
        <?php endif; ?>
        
        <?php if ($perfil_logado == 'Solicitante'): ?>
            <a href="novo_chamado.php"><button>Abrir Novo Chamado</button></a>
        <?php endif; ?>
        
        <a href="logout.php"><button>Sair</button></a>
    </div>
    
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nº Chamado</th>
                <th>Título</th>
                <th>Solicitante</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Data de Abertura</th>
                <th>Solução</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chamados as $chamado): ?>
            <tr>
                <td><?= htmlspecialchars($chamado['numero_chamado']) ?></td>
                <td><?= htmlspecialchars($chamado['titulo']) ?></td>
                <td><?= htmlspecialchars($chamado['solicitante']) ?></td>
                <td><?= htmlspecialchars($chamado['prioridade']) ?></td>
                <td><?= htmlspecialchars($chamado['status']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?></td>
                <!-- Exibe a solução se existir, senão mostra 'Pendente' -->
                <td><?= $chamado['solucao'] ? htmlspecialchars($chamado['solucao']) : '<i>Pendente</i>' ?></td>
                <td>
                    <!-- O link agora mostra o status atual do chamado -->
                    <a href="alterar_status.php?id=<?= $chamado['id_chamado'] ?>"><?= htmlspecialchars($chamado['status']) ?></a>
                    
                    <!-- Apenas Admin e Atendente podem propor solução -->
                    <?php if ($perfil_logado == 'Administrador' || $perfil_logado == 'Atendente'): ?>
                         | <a href="resolver_chamado.php?id=<?= $chamado['id_chamado'] ?>">Propor Solução</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>