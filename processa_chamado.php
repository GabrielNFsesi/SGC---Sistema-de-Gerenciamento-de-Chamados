<?php
require 'layout.php';
require 'conexao.php';
exige_login(['Solicitante']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') volta('novo_chamado.php');
csrf_check();

$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$prioridade = $_POST['prioridade'] ?? '';

if ($titulo === '' || mb_strlen($titulo) > 150 || mb_strlen($descricao) > 500
    || !in_array($prioridade, ['Baixa', 'Média', 'Alta', 'Crítica'], true)) {
    flash('erro', 'Confira os campos do chamado e tente de novo.');
    volta('novo_chamado.php');
}

$agora = date('Y-m-d H:i:s');
try {
    $pdo->beginTransaction();
    // O solicitante vem da sessão (não de um campo do formulário). A trava evita números repetidos.
    $numero = (int)$pdo->query('SELECT COALESCE(MAX(numero_chamado), 0) + 1 FROM chamado FOR UPDATE')->fetchColumn();
    $pdo->prepare('INSERT INTO chamado (id_usuario, titulo, descricao, prioridade, status, data_abertura, numero_chamado) VALUES (?,?,?,?,?,?,?)')
        ->execute([$_SESSION['id_usuario'], $titulo, $descricao, $prioridade, 'Aberto', $agora, $numero]);
    $idc = $pdo->lastInsertId();
    $pdo->prepare('INSERT INTO historico (id_chamado, id_usuario, status_anterior, acao, status_novo, data_hora) VALUES (?,?,NULL,?,?,?)')
        ->execute([$idc, $_SESSION['id_usuario'], 'Chamado aberto', 'Aberto', $agora]);
    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    flash('erro', 'Não foi possível abrir o chamado. Tente de novo.');
    volta('novo_chamado.php');
}

flash('ok', "Chamado #$numero aberto. A equipe vai atender em breve.");
volta('index.php');
