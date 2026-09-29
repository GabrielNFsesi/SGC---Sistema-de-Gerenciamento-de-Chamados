<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Chamado</title>
</head>
<body>
    <h2>Abrir Novo Chamado</h2>
    <form action="processa_chamado.php" method="POST">
        <!-- Num cenário real, o id_usuario viria da sessão de login. 
             Aqui usamos um input provisório para testar. -->
        <label>E-mail do Solicitante:</label>
        <input type="email" name="email_solicitante" required><br><br>

        <label>Título:</label>
        <input type="text" name="titulo" required maxlength="150"><br><br>

        <label>Descrição:</label>
        <textarea name="descricao" required maxlength="500"></textarea><br><br>

        <label>Prioridade:</label>
        <select name="prioridade">
            <option value="Baixa">Baixa</option>
            <option value="Média">Média</option>
            <option value="Alta">Alta</option>
            <option value="Crítica">Crítica</option>
        </select><br><br>

        <button type="submit">Cadastrar Chamado</button>
    </form>
</body>
</html>