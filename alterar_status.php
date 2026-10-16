<?php
require 'layout.php';
require 'conexao.php';
exige_login(['Atendente', 'Administrador']);

$lista = ['Aberto', 'Em atendimento', 'Resolvido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id_chamado'] ?? 0);
    $novo = $_POST['status'] ?? '';
    if (!in_array($novo, $lista, true) || !muda_status($pdo, $id, $novo, 'Status alterado')) {
        flash('erro', 'Não foi possível alterar o status.');
        volta('index.php');
    }
    flash('ok', 'Status atualizado para ' . $novo . '.');
    volta('index.php');
}

$c = carrega_chamado($pdo, $_GET['id'] ?? 0);
if (!$c) { flash('erro', 'Chamado não encontrado.'); volta('index.php'); }

render_topo('Alterar status');
?>
<div class="card form-card">
    <h1>Alterar status</h1>
    <?= resumo_chamado($c) ?>
    <form method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id_chamado" value="<?= (int)$c['id_chamado'] ?>">
        <div class="field">
            <label for="status">Novo status</label>
            <select id="status" name="status">
                <?php foreach ($lista as $s): ?>
                    <option <?= $s === $c['status'] ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Salvar status</button>
            <a class="btn btn-ghost" href="index.php">Cancelar</a>
        </div>
    </form>
</div>
<?php render_rodape(); ?>
