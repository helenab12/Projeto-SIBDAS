<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['permissions.create']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $chave = strtolower(trim($_POST['permission-key'] ?? ''));
        $descricao = ucfirst(trim($_POST['permission-description'] ?? ''));

        // Sanitizar dados
        $dadosSanitizados = Permissao::sanitizarDados([
            'idPermissao' => 0,
            'chave' => $chave,
            'descricao' => $descricao
        ]);
        $chave = $dadosSanitizados['chave'] ?? $chave;
        $descricao = $dadosSanitizados['descricao'] ?? $descricao;

        // Validar dados
        $erros = Permissao::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Ligar à BD
$ligacao = connect_to_db();

        // Verificar chave
        $stmt = execute_query("SELECT idPermissao FROM Permissao WHERE chave = :chave", ['chave' => $chave], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("A chave da permissão já existe.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Permissao (chave, descricao) VALUES (:chave, :descricao)",
            ['chave' => $chave, 'descricao' => $descricao],
            $ligacao
        );

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Permissao', $novoId, 'Criação');

        // Definir sucesso
        $_SESSION['success_message'] = "Permissão criada com sucesso!";
        
        // Fechar ligação
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
$_SESSION['server_error'] = "Erro ao criar permissão: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../permissions.php");
exit;
