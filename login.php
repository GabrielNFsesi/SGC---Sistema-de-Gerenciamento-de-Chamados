<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>SGC - Login</title>
</head>
<body>
    <h2>Login - Sistema de Gerenciamento de Chamados</h2>
    <form action="processa_login.php" method="POST">
        <label>E-mail:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Senha:</label>
        <input type="password" name="senha" required><br><br>
        
        <button type="submit">Entrar</button>
        <br><br>
        <a href="registro.php">Ainda não tem conta? Crie uma aqui</a>
    </form>
</body>
</html>