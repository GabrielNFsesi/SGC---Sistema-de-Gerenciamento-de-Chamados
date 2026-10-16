<?php
require 'layout.php';
require 'conexao.php';
exige_login(['Administrador']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $perfil = $_POST['perfil'] ?? '';

    $erro = null;
    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8
        || !in_array($perfil, ['Solicitante', 'Atendente', 'Administrador'], true)) {
        $erro = 'Preencha o nome, um e-mail válido, uma senha com pelo menos 8 caracteres e o perfil.';
    } else {
        $v = $pdo->prepare('SELECT 1 FROM usuario WHERE email = ?');
        $v->execute([$email]);
        if ($v->fetch()) $erro = 'Já existe um usuário com este e-mail.';
    }
    if ($erro) { flash('erro', $erro); volta('novo_usuario.php'); }

    $pdo->prepare('INSERT INTO usuario (nome, email, senha, perfil) VALUES (?,?,?,?)')
        ->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $perfil]);
    flash('ok', 'Usuário ' . $nome . ' cadastrado como ' . $perfil . '.');
    volta('index.php');
}

render_topo('Cadastrar usuário');
?>
<div class="card form-card">
    <h1>Cadastrar usuário</h1>
    <form method="POST">
        <?= csrf_field() ?>
        <div class="field">
            <label for="nome">Nome completo</label>
            <input id="nome" type="text" name="nome" required autofocus>
        </div>
        <div class="field">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" required>
        </div>
        <div class="field">
            <label for="senha">Senha inicial</label>
            <input id="senha" type="password" name="senha" autocomplete="new-password" minlength="8" required>
            <p class="hint">Use pelo menos 8 caracteres.</p>
        </div>
        <div class="field">
            <label for="perfil">Perfil</label>
            <select id="perfil" name="perfil">
                <option>Solicitante</option><option>Atendente</option><option>Administrador</option>
            </select>
        </div>
        <div class="form-actions">
            <button class="btn" type="submit">Cadastrar usuário</button>
            <a class="btn btn-ghost" href="index.php">Cancelar</a>
        </div>
    </form>
</div>
<?php render_rodape(); ?>
