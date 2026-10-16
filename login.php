<?php
require 'layout.php';
if (!empty($_SESSION['id_usuario'])) volta('index.php');
render_topo('Entrar', 'auth');
?>
<h1>Entrar</h1>
<p class="lead">Acesse sua conta para acompanhar seus chamados.</p>
<form action="processa_login.php" method="POST">
    <?= csrf_field() ?>
    <div class="field">
        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" autocomplete="username" required autofocus>
    </div>
    <div class="field">
        <label for="senha">Senha</label>
        <input id="senha" type="password" name="senha" autocomplete="current-password" required>
    </div>
    <button class="btn" type="submit">Entrar</button>
</form>
<p class="auth-alt">Ainda não tem conta? <a href="registro.php">Criar conta</a></p>
<?php render_rodape(); ?>
