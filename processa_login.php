<?php
require 'layout.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') volta('login.php');
csrf_check();

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

$s = $pdo->prepare('SELECT id_usuario, nome, perfil, senha FROM usuario WHERE email = ?');
$s->execute([$email]);
$u = $s->fetch();

$ok = false;
if ($u) {
    if (password_verify($senha, $u['senha'])) {
        $ok = true;
    } elseif (hash_equals($u['senha'], $senha)) {
        // Conta antiga com senha em texto puro: converte para hash no primeiro login.
        $ok = true;
        $pdo->prepare('UPDATE usuario SET senha = ? WHERE id_usuario = ?')
            ->execute([password_hash($senha, PASSWORD_DEFAULT), $u['id_usuario']]);
    }
}

if (!$ok) {
    flash('erro', 'E-mail ou senha incorretos. Confira os dados e tente de novo.');
    volta('login.php');
}

session_regenerate_id(true); // evita fixação de sessão
$_SESSION['id_usuario'] = $u['id_usuario'];
$_SESSION['nome'] = $u['nome'];
$_SESSION['perfil'] = $u['perfil'];
volta('index.php');
