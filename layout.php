<?php
// Funções compartilhadas: sessão, segurança (CSRF, permissões) e layout das páginas.
session_start();

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function volta($url) { header("Location: $url"); exit; }
function flash($tipo, $msg) { $_SESSION['flash'] = [$tipo, $msg]; }

function csrf_field() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf" value="' . $_SESSION['csrf'] . '">';
}
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die('Sessão expirada. Volte à página anterior, recarregue e tente de novo.');
    }
}

function slug($s) {
    return ['Aberto'=>'aberto','Em atendimento'=>'atendimento','Resolvido'=>'resolvido',
            'Baixa'=>'baixa','Média'=>'media','Alta'=>'alta','Crítica'=>'critica'][$s] ?? 'baixa';
}
function badge($tipo, $valor) { return '<span class="badge ' . $tipo . '-' . slug($valor) . '">' . e($valor) . '</span>'; }

function exige_login(array $perfis = []) {
    if (empty($_SESSION['id_usuario'])) volta('login.php');
    if ($perfis && !in_array($_SESSION['perfil'], $perfis, true)) {
        http_response_code(403);
        render_topo('Acesso negado');
        echo '<div class="card empty"><h2>Você não tem acesso a esta página</h2><p>Seu perfil não permite esta ação.</p><a class="btn" href="index.php">Voltar aos chamados</a></div>';
        render_rodape();
        exit;
    }
}

function carrega_chamado(PDO $pdo, $id) {
    $s = $pdo->prepare('SELECT c.*, u.nome AS solicitante FROM chamado c JOIN usuario u ON u.id_usuario = c.id_usuario WHERE c.id_chamado = ?');
    $s->execute([(int)$id]);
    return $s->fetch();
}
function resumo_chamado($c) {
    return '<div class="summary"><span class="num">Chamado #' . e($c['numero_chamado']) . '</span>'
         . '<span class="t-title">' . e($c['titulo']) . '</span>'
         . '<span class="t-sub">Aberto por ' . e($c['solicitante']) . '</span>'
         . '<span>' . badge('p', $c['prioridade']) . ' ' . badge('s', $c['status']) . '</span></div>';
}

// Atualiza o status e registra a mudança na tabela historico (RN11), tudo em uma transação.
function muda_status(PDO $pdo, int $id, string $novo, string $acao, ?string $solucao = null) {
    $pdo->beginTransaction();
    $q = $pdo->prepare('SELECT status FROM chamado WHERE id_chamado = ? FOR UPDATE');
    $q->execute([$id]);
    $antes = $q->fetchColumn();
    if ($antes === false) { $pdo->rollBack(); return false; }
    $pdo->prepare('UPDATE chamado SET status = ?, solucao = COALESCE(?, solucao) WHERE id_chamado = ?')->execute([$novo, $solucao, $id]);
    $pdo->prepare('INSERT INTO historico (id_chamado, id_usuario, status_anterior, acao, status_novo, observacao, data_hora) VALUES (?,?,?,?,?,?,?)')
        ->execute([$id, $_SESSION['id_usuario'], $antes, $acao, $novo, $solucao, date('Y-m-d H:i:s')]);
    $pdo->commit();
    return true;
}

function render_topo($titulo, $modo = 'app') {
    $GLOBALS['modo'] = $modo;
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . e($titulo) . ' – SGC</title>'
       . '<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">'
       . '<link rel="stylesheet" href="assets/style.css"></head><body>';
    if ($modo === 'auth') {
        echo '<main class="auth"><aside class="auth-side"><a class="brand" href="login.php">SGC<span>Gerenciamento de chamados</span></a>'
           . '<div><h2>Do pedido à solução, tudo registrado.</h2><p>Abra um chamado, acompanhe cada mudança de status e veja a solução proposta pela equipe.</p></div><div></div></aside>'
           . '<section class="auth-main"><div class="auth-box">';
    } else {
        $n = $_SESSION['nome'] ?? '';
        echo '<header class="topbar"><div class="topbar-in"><a class="brand" href="index.php">SGC<span>Chamados</span></a>'
           . '<div class="user"><div class="avatar" aria-hidden="true">' . e(mb_strtoupper(mb_substr($n, 0, 1))) . '</div>'
           . '<div class="user-info">' . e($n) . '<small>' . e($_SESSION['perfil'] ?? '') . '</small></div>'
           . '<a class="btn btn-ghost btn-sm" href="logout.php">Sair</a></div></div></header><main class="wrap">';
    }
    if ($f) echo '<div class="alert alert-' . e($f[0]) . '" role="status">' . e($f[1]) . '</div>';
}
function render_rodape() {
    echo ($GLOBALS['modo'] === 'auth') ? '</div></section></main>' : '</main>';
    echo '</body></html>';
}
