<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['components.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = ucfirst(trim($_POST['component-name'] ?? ''));
        $sku = strtoupper(trim($_POST['component-sku'] ?? ''));
        $idCategoria = trim($_POST['component-category'] ?? '');
        $idLocalizacao = trim($_POST['component-location'] ?? '');
        
        $stock = isset($_POST['component-stock-actual']) && $_POST['component-stock-actual'] !== '' ? (int)$_POST['component-stock-actual'] : 0;
        $stockMin = isset($_POST['component-stock-min']) && $_POST['component-stock-min'] !== '' ? (int)$_POST['component-stock-min'] : 0;
        $preco = isset($_POST['component-price']) && $_POST['component-price'] !== '' ? (float)$_POST['component-price'] : 0.00;

        // Validação usando o método do Componente
        $dadosSanitizados = Componente::sanitizarDados([
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
        $sku = $dadosSanitizados['codigoInterno'] ?? $sku;
        $nome = $dadosSanitizados['descricao'] ?? $nome;
        $stock = $dadosSanitizados['stock'] ?? $stock;
        $stockMin = $dadosSanitizados['stockMinimo'] ?? $stockMin;
        $preco = $dadosSanitizados['preco'] ?? $preco;
        $idLocalizacao = $dadosSanitizados['idLocalizacao'] ?? $idLocalizacao;
        $erros = Componente::validarDados($dadosSanitizados);

        if (empty($idCategoria)) {
            $erros[] = "A categoria é obrigatória.";
        }

        if ($stockMin > $stock) {
            $erros[] = "O stock mínimo não pode ser superior ao stock atual.";
        }

        if (!empty($erros)) {
            throw new Exception(implode("<br>", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se o SKU já existe
        $stmt = execute_query("SELECT idComponente FROM Componente WHERE codigoInterno = :sku", ['sku' => $sku], $ligacao);
        if ($stmt->fetch()) {
            throw new Exception("Já existe um componente com este SKU.");
        }

        // Inserir o novo Componente
        execute_query(
            "INSERT INTO Componente (codigoInterno, descricao, stock, stockMinimo, preco, idLocalizacao, ativo) 
             VALUES (:sku, :descricao, :stock, :stockMinimo, :preco, :idLocalizacao, 1)",
            [
                'sku' => $sku,
                'descricao' => $nome,
                'stock' => $stock,
                'stockMinimo' => $stockMin,
                'preco' => $preco,
                'idLocalizacao' => $idLocalizacao
            ],
            $ligacao
        );

        $idComponente = $ligacao->lastInsertId();

        // Inserir o mapeamento do Componente com a Categoria
        execute_query(
            "INSERT INTO ComponenteCategoria (idComponente, idCategoria) VALUES (:idComponente, :idCategoria)",
            [
                'idComponente' => $idComponente,
                'idCategoria' => $idCategoria
            ],
            $ligacao
        );

        registar_auditoria($ligacao, 'Componente', $idComponente, 'Criação');

        $_SESSION['success_message'] = "Componente criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar componente: " . $e->getMessage();
    }
}

header("Location: ../components.php");
exit;
