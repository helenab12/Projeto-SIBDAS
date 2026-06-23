<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['categories.create']);

// Processar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $codigo = strtoupper(trim($_POST['category-code'] ?? ''));
        $nome = ucfirst(trim($_POST['category-name'] ?? ''));
        $descricao = ucfirst(trim($_POST['category-description'] ?? ''));

        // Sanitizar dados
        $dadosSanitizados = Categoria::sanitizarDados([
            'idCategoria' => 0, // ID fictício para validação de criação
            'codigo' => $codigo,
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);
        // Atualizar variáveis
        $codigo = $dadosSanitizados['codigo'] ?? $codigo;
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $descricao = $dadosSanitizados['descricao'] ?? $descricao;
        // Validar dados
        $erros = Categoria::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Ligar à BD
        $ligacao = connect_to_db();

        // Verificar unicidade
        $stmt = execute_query("SELECT idCategoria FROM CategoriaEquipamento WHERE codigoPrefix = :codigo OR nome = :nome", ['codigo' => $codigo, 'nome' => $nome], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("A chave ou nome da categoria já existe.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO CategoriaEquipamento (codigoPrefix, nome, descricao, ativo) VALUES (:codigo, :nome, :descricao, 1)",
            ['codigo' => $codigo, 'nome' => $nome, 'descricao' => $descricao],
            $ligacao
        );

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'CategoriaEquipamento', $novoId, 'Criação');

        // Definir sucesso
        $_SESSION['success_message'] = "Categoria criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar categoria: " . $e->getMessage();
    }
}

// Redirecionar utilizador
header("Location: ../categories.php");
exit;
