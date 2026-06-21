<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['categories.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $codigo = strtoupper(trim($_POST['category-code'] ?? ''));
        $nome = ucfirst(trim($_POST['category-name'] ?? ''));
        $descricao = ucfirst(trim($_POST['category-description'] ?? ''));

        // Validação básica
        $dadosSanitizados = Categoria::sanitizarDados([
            'idCategoria' => 0, // ID fictício para validação de criação
            'codigo' => $codigo,
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);
        $codigo = $dadosSanitizados['codigo'] ?? $codigo;
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $descricao = $dadosSanitizados['descricao'] ?? $descricao;
        $erros = Categoria::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se a categoria já existe
        $stmt = execute_query("SELECT idCategoria FROM CategoriaEquipamento WHERE codigoPrefix = :codigo OR nome = :nome", ['codigo' => $codigo, 'nome' => $nome], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("A chave ou nome da categoria já existe.");
        }

        execute_query(
            "INSERT INTO CategoriaEquipamento (codigoPrefix, nome, descricao, ativo) VALUES (:codigo, :nome, :descricao, 1)",
            ['codigo' => $codigo, 'nome' => $nome, 'descricao' => $descricao],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'CategoriaEquipamento', $novoId, 'Criação');

        $_SESSION['success_message'] = "Categoria criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar categoria: " . $e->getMessage();
    }
}

header("Location: ../categories.php");
exit;
