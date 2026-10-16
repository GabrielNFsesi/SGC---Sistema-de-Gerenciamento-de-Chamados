<?php
require 'layout.php';
exige_login(['Solicitante']);
render_topo('Abrir chamado');
?>
<div class="card form-card">
    <h1>Abrir chamado</h1>
    <form action="processa_chamado.php" method="POST">
        <?= csrf_field() ?>
        <div class="field">
            <label for="titulo">Título</label>
            <input id="titulo" type="text" name="titulo" maxlength="150" required autofocus>
            <p class="hint">Resuma o problema em uma frase.</p>
        </div>
        <div class="field">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" maxlength="500" required></textarea>
            <p class="hint">Até 500 caracteres. Conte o que aconteceu e o que você já tentou.</p>
        </div>
        <div class="field">
            <label for="prioridade">Prioridade</label>
            <select id="prioridade" name="prioridade">
                <option>Baixa</option><option selected>Média</option><option>Alta</option><option>Crítica</option>
            </select>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Abrir chamado</button>
            <a class="btn btn-ghost" href="index.php">Cancelar</a>
        </div>
    </form>
</div>
<?php render_rodape(); ?>
