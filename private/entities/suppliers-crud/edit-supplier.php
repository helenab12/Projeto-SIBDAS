<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['suppliers.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_fornecedor'])) {
    try {
        $encryptedId = $_POST['supplier-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        $nome = $_POST['supplier-name'] ?? '';
        $nif = $_POST['supplier-nif'] ?? '';
        $email = strtolower(trim($_POST['supplier-email'] ?? ''));
        $telefone = $_POST['supplier-phone'] ?? '';
        $website = $_POST['supplier-website'] ?? '';
        $tipo = $_POST['supplier-type'] ?? '';
        $idPessoa = $_POST['supplier-contact-person'] ?? '';

        // Sanitização
        $nome = capitalize_name(trim($nome));
        $nif = trim($nif);
        $telefone = trim($telefone);
        $website = trim($website);
        $idPessoa = empty($idPessoa) ? null : $idPessoa;

        // Tipo Enum
        $tipoEnum = TipoFornecedor::tryFrom($tipo);
        if (!$tipoEnum) {
            throw new Exception("Tipo de fornecedor inválido.");
        }

        // Validação usando a classe Fornecedor
        $erros = Fornecedor::validarDados([
            'idFornecedor' => (string)$id,
            'nome' => $nome,
            'nifFornecedor' => $nif,
            'contactoTelefonico' => $telefone,
            'email' => $email,
            'website' => $website,
            'idPessoaResponsavel' => $idPessoa,
            'tipoFornecedor' => $tipoEnum,
            'ativo' => true,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar duplicados (NIF ou email noutro registo)
        $stmtVerificar = execute_query(
            "SELECT nifFornecedor, email FROM Fornecedor WHERE (nifFornecedor = :nif OR email = :email) AND idFornecedor != :id",
            ['nif' => $nif, 'email' => $email, 'id' => $id],
            $ligacao
        );

        $fornecedorExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
        if ($fornecedorExistente) {
            if ($fornecedorExistente['nifFornecedor'] === $nif) {
                throw new Exception("Já existe outro fornecedor registado com este NIF.");
            }
            if ($fornecedorExistente['email'] === $email) {
                throw new Exception("Já existe outro fornecedor registado com este email.");
            }
        }

        // Atualizar na base de dados
        execute_query(
            "UPDATE Fornecedor 
             SET nome = :nome, nifFornecedor = :nif, contactoTelefonico = :telefone, email = :email, 
                 website = :website, idPessoaResponsavel = :idPessoa, tipoFornecedor = :tipo, dataAtualizacao = NOW()
             WHERE idFornecedor = :id",
            [
                'nome' => $nome,
                'nif' => $nif,
                'telefone' => $telefone,
                'email' => $email,
                'website' => empty($website) ? null : $website,
                'idPessoa' => $idPessoa,
                'tipo' => $tipoEnum->value,
                'id' => $id
            ],
            $ligacao
        );

        $_SESSION['success_message'] = "Fornecedor editado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar fornecedor: " . $e->getMessage();
    }
}

header("Location: ../suppliers.php");
exit;
