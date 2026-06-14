<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

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
        $erros = Categoria::validarDados([
            'idCategoria' => (string) $id,
            'codigo' => $codigo,
            'nome' => $nome,
            'descricao' => $descricao,
            'ativo' => true,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se o código ou nome já existe noutra categoria
        $stmt = execute_query(
            "SELECT idCategoria FROM CategoriaEquipamento WHERE (codigoPrefix = :codigo OR nome = :nome) AND idCategoria != :id",
            ['codigo' => $codigo, 'nome' => $nome, 'id' => $id],
            $ligacao
        );
        if ($stmt->fetch()) {
            throw new Exception("O código ou nome da categoria já existe noutro registo.");
        }

        execute_query(
            "UPDATE CategoriaEquipamento SET codigoPrefix = :codigo, nome = :nome, descricao = :descricao WHERE idCategoria = :id",
            ['codigo' => $codigo, 'nome' => $nome, 'descricao' => $descricao, 'id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Categoria editada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar categoria: " . $e->getMessage();
    }
}

header("Location: ../categories.php");
exit;
