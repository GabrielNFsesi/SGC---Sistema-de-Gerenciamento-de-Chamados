<?php
require 'layout.php';
require 'conexao.php';
exige_login();

$id = $_SESSION['id_usuario'];
$perfil = $_SESSION['perfil'];
$equipe = in_array($perfil, ['Atendente', 'Administrador'], true);
$lista = ['Aberto', 'Em atendimento', 'Resolvido'];
$filtro = in_array($_GET['status'] ?? '', $lista, true) ? $_GET['status'] : '';

// Solicitante vê só os próprios chamados; Atendente e Administrador veem todos.
$where = $equipe ? '1=1' : 'c.id_usuario = :u';
$p = $equipe ? [] : [':u' => $id];

$q = $pdo->prepare("SELECT c.status, COUNT(*) AS n FROM chamado c WHERE $where GROUP BY c.status");
$q->execute($p);
$cont = array_fill_keys($lista, 0);
foreach ($q as $r) if (isset($cont[$r['status']])) $cont[$r['status']] = (int)$r['n'];

if ($filtro) { $where .= ' AND c.status = :s'; $p[':s'] = $filtro; }
$q = $pdo->prepare("SELECT c.id_chamado, c.numero_chamado, c.titulo, c.prioridade, c.status, c.data_abertura, c.solucao, u.nome AS solicitante
                    FROM chamado c JOIN usuario u ON u.id_usuario = c.id_usuario
                    WHERE $where ORDER BY c.data_abertura DESC");
$q->execute($p);
$chamados = $q->fetchAll();

render_topo('Chamados');
?>
<div class="page-head">
    <div>
        <h1>Chamados</h1>
        <p><?= $equipe ? 'Todos os chamados abertos na plataforma.' : 'Acompanhe os chamados que você abriu.' ?></p>
    </div>
    <div class="actions">
        <?php if ($perfil === 'Solicitante'): ?><a class="btn" href="novo_chamado.php">Abrir chamado</a><?php endif; ?>
        <?php if ($perfil === 'Administrador'): ?><a class="btn btn-ghost" href="novo_usuario.php">Cadastrar usuário</a><?php endif; ?>
    </div>
</div>

<nav class="tabs" aria-label="Filtrar por status">
    <a class="tab" href="index.php" <?= $filtro === '' ? 'aria-current="page"' : '' ?>>Todos<b><?= array_sum($cont) ?></b></a>
    <?php foreach ($lista as $s): ?>
        <a class="tab" href="index.php?status=<?= urlencode($s) ?>" <?= $filtro === $s ? 'aria-current="page"' : '' ?>><?= e($s) ?><b><?= $cont[$s] ?></b></a>
    <?php endforeach; ?>
</nav>

<?php if (!$chamados): ?>
    <div class="card empty">
        <h2>Nenhum chamado por aqui</h2>
        <p><?= $filtro ? 'Nenhum chamado com o status "' . e($filtro) . '".' : ($perfil === 'Solicitante' ? 'Abra seu primeiro chamado para a equipe começar a atender.' : 'Quando alguém abrir um chamado, ele aparece aqui.') ?></p>
        <?php if ($perfil === 'Solicitante'): ?><a class="btn" href="novo_chamado.php">Abrir chamado</a><?php endif; ?>
    </div>
<?php else: ?>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nº</th><th>Chamado</th>
                    <?php if ($equipe): ?><th>Solicitante</th><?php endif; ?>
                    <th>Prioridade</th><th>Status</th><th>Aberto em</th>
                    <?php if ($equipe): ?><th>Ações</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($chamados as $c): ?>
                <tr>
                    <td class="num" data-label="Nº">#<?= e($c['numero_chamado']) ?></td>
                    <td>
                        <div class="t-title"><?= e($c['titulo']) ?></div>
                        <?php if ($c['solucao']): ?><div class="t-sub"><b>Solução:</b> <?= e($c['solucao']) ?></div><?php endif; ?>
                    </td>
                    <?php if ($equipe): ?><td data-label="Solicitante"><?= e($c['solicitante']) ?></td><?php endif; ?>
                    <td data-label="Prioridade"><?= badge('p', $c['prioridade']) ?></td>
                    <td data-label="Status"><?= badge('s', $c['status']) ?></td>
                    <td class="num" data-label="Aberto em"><?= date('d/m/Y H:i', strtotime($c['data_abertura'])) ?></td>
                    <?php if ($equipe): ?>
                    <td><div class="row-actions">
                        <a class="btn btn-ghost btn-sm" href="alterar_status.php?id=<?= (int)$c['id_chamado'] ?>">Alterar status</a>
                        <a class="btn btn-ghost btn-sm" href="resolver_chamado.php?id=<?= (int)$c['id_chamado'] ?>">Propor solução</a>
                    </div></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php render_rodape(); ?>
