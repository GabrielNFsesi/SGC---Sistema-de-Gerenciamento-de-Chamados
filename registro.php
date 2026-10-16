<?php
require 'layout.php';
if (!empty($_SESSION['id_usuario'])) volta('index.php');
render_topo('Criar conta', 'auth');
?>
<h1>Criar conta</h1>
<p class="lead">Cadastre-se para abrir e acompanhar chamados.</p>
<form action="processa_registro.php" method="POST">
    <?= csrf_field() ?>
    <div class="field">
        <label for="nome">Nome completo</label>
        <input id="nome" type="text" name="nome" autocomplete="name" required autofocus>
    </div>
    <div class="field">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" autocomplete="username" required>
    </div>
    <div class="field">
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" autocomplete="new-password" minlength="8" required>
        <p class="hint">Use pelo menos 8 caracteres.</p>
    </div>
    <button class="btn" type="submit">Criar conta</button>
</form>
<p class="auth-alt">Já tem uma conta? <a href="login.php">Entrar</a></p>
<?php render_rodape(); ?>
