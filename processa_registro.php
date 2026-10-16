<?php
require 'layout.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') volta('registro.php');
csrf_check();

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
    flash('erro', 'Preencha o nome, um e-mail válido e uma senha com pelo menos 8 caracteres.');
    volta('registro.php');
}

$v = $pdo->prepare('SELECT 1 FROM usuario WHERE email = ?');
$v->execute([$email]);
if ($v->fetch()) {
    flash('erro', 'Este e-mail já está cadastrado. Entre na sua conta ou use outro e-mail.');
    volta('registro.php');
}

// Perfil fixo: quem se cadastra sozinho sempre é Solicitante.
$pdo->prepare('INSERT INTO usuario (nome, email, senha, perfil) VALUES (?,?,?,?)')
    ->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), 'Solicitante']);

flash('ok', 'Conta criada. Agora é só entrar.');
volta('login.php');
