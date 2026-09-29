<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>SGC - Criar Conta</title>
</head>
<body>
    <h2>Criar Nova Conta</h2>
    <form action="processa_registro.php" method="POST">
        <label>Nome Completo:</label>
        <input type="text" name="nome" required><br><br>
        
        <label>E-mail:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Senha:</label>
        <input type="password" name="senha" required><br><br>
        
        <button type="submit">Registrar</button>
    </form>
    <br>
    <a href="login.php">Já tem uma conta? Iniciar sessão</a>
</body>
</html>