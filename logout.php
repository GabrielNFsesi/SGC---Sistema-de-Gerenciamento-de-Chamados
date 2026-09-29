<?php
session_start(); // Inicia a sessão atual
session_destroy(); // Destrói a sessão (desloga o usuário completamente)
header("Location: login.php"); // Redireciona de volta para a página de login
exit;
?>