<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['categories.edit']);

// Processar pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Desencriptar ID
        $encryptedId = $_POST['category-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Recolher dados do POST
        $codigo = strtoupper(trim($_POST['category-code'] ?? ''));
        $nome = ucfirst(trim($_POST['category-name'] ?? ''));
        $descricao = ucfirst(trim($_POST['category-description'] ?? ''));

        // Sanitizar dados
        $dadosSanitizados = Categoria::sanitizarDados([
            'idCategoria' => (string) $id,
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

        // Verificar unicidade
        $stmt = execute_query(
            "SELECT idCategoria FROM CategoriaEquipamento WHERE (codigoPrefix = :codigo OR nome = :nome) AND idCategoria != :id",
            ['codigo' => $codigo, 'nome' => $nome, 'id' => $id]);
        if ($stmt->fetch()) {
            throw new Exception("O código ou nome da categoria já existe noutro registo.");
        }

        // Ler estado antigo
        $stmtAntigo = execute_query(
            "SELECT codigoPrefix, nome, descricao FROM CategoriaEquipamento WHERE idCategoria = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
        execute_query(
            "UPDATE CategoriaEquipamento SET codigoPrefix = :codigo, nome = :nome, descricao = :descricao WHERE idCategoria = :id",
            ['codigo' => $codigo, 'nome' => $nome, 'descricao' => $descricao, 'id' => $id]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'CategoriaEquipamento', $id, $antigo, [
            'codigoPrefix' => $codigo,
            'nome' => $nome,
            'descricao' => $descricao
        ]);

        // Definir sucesso
        $_SESSION['success_message'] = "Categoria editada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar categoria: " . $e->getMessage();
    }
}

// Redirecionar utilizador
header("Location: ../categories.php");
exit;
