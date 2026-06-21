<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['categories.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['category-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        $codigo = strtoupper(trim($_POST['category-code'] ?? ''));
        $nome = ucfirst(trim($_POST['category-name'] ?? ''));
        $descricao = ucfirst(trim($_POST['category-description'] ?? ''));

        // Validação básica
        $dadosSanitizados = Categoria::sanitizarDados([
            'idCategoria' => (string) $id,
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

        // Verificar se o código ou nome já existe noutra categoria
        $stmt = execute_query(
            "SELECT idCategoria FROM CategoriaEquipamento WHERE (codigoPrefix = :codigo OR nome = :nome) AND idCategoria != :id",
            ['codigo' => $codigo, 'nome' => $nome, 'id' => $id]);
        if ($stmt->fetch()) {
            throw new Exception("O código ou nome da categoria já existe noutro registo.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT codigoPrefix, nome, descricao FROM CategoriaEquipamento WHERE idCategoria = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        execute_query(
            "UPDATE CategoriaEquipamento SET codigoPrefix = :codigo, nome = :nome, descricao = :descricao WHERE idCategoria = :id",
            ['codigo' => $codigo, 'nome' => $nome, 'descricao' => $descricao, 'id' => $id]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'CategoriaEquipamento', $id, $antigo, [
            'codigoPrefix' => $codigo,
            'nome' => $nome,
            'descricao' => $descricao
        ]);

        $_SESSION['success_message'] = "Categoria editada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar categoria: " . $e->getMessage();
    }
}

header("Location: ../categories.php");
exit;
