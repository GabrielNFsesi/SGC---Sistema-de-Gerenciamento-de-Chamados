<?php
// Configura o fuso horário para Brasília logo no início
date_default_timezone_set('America/Sao_Paulo');

require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Recebe o e-mail do formulário
    $email_digitado = $_POST['email_solicitante'];
    
    // 2. Busca o ID do usuário no banco de dados usando esse e-mail
    $sql_busca = "SELECT id_usuario FROM usuario WHERE email = :email";
    $stmt_busca = $pdo->prepare($sql_busca);
    $stmt_busca->execute([':email' => $email_digitado]);
    $usuario_encontrado = $stmt_busca->fetch(PDO::FETCH_ASSOC);
    
    // Se o e-mail não existir, interrompe o processo e avisa
    if (!$usuario_encontrado) {
        die("Erro: Nenhum usuário cadastrado com este e-mail. <a href='novo_chamado.php'>Voltar</a>");
    }
    
    // Se encontrou, pega o ID correto
    $id_usuario = $usuario_encontrado['id_usuario'];

    // Recebe o resto dos campos
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];
    
    // Define o status inicial
    $status = 'Aberto'; 
    
    // Define a data de abertura com o fuso horário já corrigido
    $data_abertura = date('Y-m-d H:i:s');
    
    // --- LÓGICA DO NÚMERO SEQUENCIAL ---
    $sql_max = "SELECT MAX(numero_chamado) as max_numero FROM chamado";
    $stmt_max = $pdo->query($sql_max);
    $resultado = $stmt_max->fetch(PDO::FETCH_ASSOC);
    
    // Verifica se existe resultado, caso contrário, assume 0
    $ultimo_numero = ($resultado && $resultado['max_numero']) ? $resultado['max_numero'] : 0;
    
    // O novo número será o último + 1
    $numero_chamado = $ultimo_numero + 1;
    // ------------------------------------

    // Insere na base de dados (agora usando o ID que o sistema descobriu sozinho)
    $sql = "INSERT INTO chamado (id_usuario, titulo, descricao, prioridade, status, data_abertura, numero_chamado) 
            VALUES (:id_usuario, :titulo, :descricao, :prioridade, :status, :data_abertura, :numero_chamado)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':prioridade' => $prioridade,
        ':status' => $status,
        ':data_abertura' => $data_abertura, 
        ':numero_chamado' => $numero_chamado
    ]);

    // Redireciona de volta para a lista (index.php)
    header("Location: index.php");
    exit;
}
?>