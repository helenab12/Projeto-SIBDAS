<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['components.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['component-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $idComponente = aes_decrypt($encryptedId);
        if ($idComponente === false) {
            throw new Exception("ID inválido.");
        }
        $idComponente = (int) $idComponente;

        $nome = ucfirst(trim($_POST['component-name'] ?? ''));
        $sku = strtoupper(trim($_POST['component-sku'] ?? ''));
        $idCategoria = trim($_POST['component-category'] ?? '');
        $idLocalizacao = trim($_POST['component-location'] ?? '');

        $stock = isset($_POST['component-stock-actual']) && $_POST['component-stock-actual'] !== '' ? (int) $_POST['component-stock-actual'] : 0;
        $stockMin = isset($_POST['component-stock-min']) && $_POST['component-stock-min'] !== '' ? (int) $_POST['component-stock-min'] : 0;
        $preco = isset($_POST['component-price']) && $_POST['component-price'] !== '' ? (float) $_POST['component-price'] : 0.00;

        // Validação básica
        $erros = Componente::validarDados([
            'codigoInterno' => $sku,
            'descricao' => $nome,
            'stock' => $stock,
            'stockMinimo' => $stockMin,
            'preco' => $preco,
            'idLocalizacao' => $idLocalizacao,
            'ativo' => true,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);

        if (empty($idCategoria)) {
            $erros[] = "A categoria é obrigatória.";
        }


        if (!empty($erros)) {
            throw new Exception(implode(",", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se o SKU já existe noutro componente
        $stmt = execute_query(
            "SELECT idComponente FROM Componente WHERE codigoInterno = :sku AND idComponente != :id",
            ['sku' => $sku, 'id' => $idComponente],
            $ligacao
        );
        if ($stmt->fetch()) {
            throw new Exception("O SKU inserido já existe noutro registo.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT codigoInterno, descricao, stock, stockMinimo, preco, idLocalizacao FROM Componente WHERE idComponente = :id",
            ['id' => $idComponente],
            $ligacao
        );
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar Componente
        execute_query(
            "UPDATE Componente 
             SET codigoInterno = :sku, descricao = :descricao, stock = :stock, stockMinimo = :stockMinimo, preco = :preco, idLocalizacao = :idLocalizacao 
             WHERE idComponente = :id",
            [
                'sku' => $sku,
                'descricao' => $nome,
                'stock' => $stock,
                'stockMinimo' => $stockMin,
                'preco' => $preco,
                'idLocalizacao' => $idLocalizacao,
                'id' => $idComponente
            ],
            $ligacao
        );

        // Atualizar mapeamento do Componente com a Categoria
        $stmtCheckCat = execute_query("SELECT idCategoria FROM ComponenteCategoria WHERE idComponente = :idComponente", ['idComponente' => $idComponente], $ligacao);
        if ($stmtCheckCat->fetch()) {
            execute_query(
                "UPDATE ComponenteCategoria SET idCategoria = :idCategoria WHERE idComponente = :idComponente",
                ['idCategoria' => $idCategoria, 'idComponente' => $idComponente],
                $ligacao
            );
        } else {
            execute_query(
                "INSERT INTO ComponenteCategoria (idComponente, idCategoria) VALUES (:idComponente, :idCategoria)",
                ['idComponente' => $idComponente, 'idCategoria' => $idCategoria],
                $ligacao
            );
        }

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Componente', $idComponente, $antigo, [
            'codigoInterno' => $sku,
            'descricao' => $nome,
            'stock' => $stock,
            'stockMinimo' => $stockMin,
            'preco' => $preco,
            'idLocalizacao' => $idLocalizacao
        ]);

        $_SESSION['success_message'] = "Componente editado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar componente: " . $e->getMessage();
    }
}

header("Location: ../components.php");
exit;
