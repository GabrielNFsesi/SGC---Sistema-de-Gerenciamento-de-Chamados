<?php
require 'layout.php';
require 'conexao.php';
exige_login(['Atendente', 'Administrador']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id_chamado'] ?? 0);
    $solucao = trim($_POST['solucao'] ?? '');
    if ($solucao === '' || mb_strlen($solucao) > 500 || !muda_status($pdo, $id, 'Resolvido', 'Solução proposta', $solucao)) {
        flash('erro', 'Não foi possível salvar a solução. Descreva-a em até 500 caracteres.');
        volta('index.php');
    }
    flash('ok', 'Solução salva. O chamado foi marcado como Resolvido.');
    volta('index.php');
}

$c = carrega_chamado($pdo, $_GET['id'] ?? 0);
if (!$c) { flash('erro', 'Chamado não encontrado.'); volta('index.php'); }

render_topo('Propor solução');
?>
<div class="card form-card">
    <h1>Propor solução</h1>
    <?= resumo_chamado($c) ?>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id_chamado" value="<?= (int)$c['id_chamado'] ?>">
        <div class="field">
            <label for="solucao">Solução</label>
            <textarea id="solucao" name="solucao" maxlength="500" required autofocus><?= e($c['solucao']) ?></textarea>
            <p class="hint">Até 500 caracteres. Ao salvar, o chamado passa para Resolvido.</p>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Salvar solução</button>
            <a class="btn btn-ghost" href="index.php">Cancelar</a>
        </div>
    </form>
</div>
<?php render_rodape(); ?>
