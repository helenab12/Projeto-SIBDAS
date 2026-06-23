<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['suppliers.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_fornecedor'])) {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['supplier-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int)$id;

        // Recolher dados do POST
        $nome = $_POST['supplier-name'] ?? '';
        $nif = $_POST['supplier-nif'] ?? '';
        $email = strtolower(trim($_POST['supplier-email'] ?? ''));
        $telefone = $_POST['supplier-phone'] ?? '';
        $website = $_POST['supplier-website'] ?? '';
        $tipo = $_POST['supplier-type'] ?? '';
        $idPessoa = $_POST['supplier-contact-person'] ?? '';

        // Sanitizar dados
        $nome = capitalize_name(trim($nome));
        $nif = trim($nif);
        $telefone = trim($telefone);
        $website = trim($website);
        $idPessoa = empty($idPessoa) ? null : $idPessoa;

        // Validar tipo de fornecedor
        $tipoEnum = TipoFornecedor::tryFrom($tipo);
        if (!$tipoEnum) {
            throw new Exception("Tipo de fornecedor inválido.");
        }

        // Sanitizar e validar dados
        $dadosSanitizados = Fornecedor::sanitizarDados([
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
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $nif = $dadosSanitizados['nifFornecedor'] ?? $nif;
        $telefone = $dadosSanitizados['contactoTelefonico'] ?? $telefone;
        $email = $dadosSanitizados['email'] ?? $email;
        $website = $dadosSanitizados['website'] ?? $website;
        $idPessoa = $dadosSanitizados['idPessoaResponsavel'] ?? $idPessoa;
        $tipoEnum = $dadosSanitizados['tipoFornecedor'] ?? $tipoEnum;
        $erros = Fornecedor::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT nifFornecedor, email FROM Fornecedor WHERE (nifFornecedor = :nif OR email = :email) AND idFornecedor != :id",
            ['nif' => $nif, 'email' => $email, 'id' => $id]);

        $fornecedorExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
        if ($fornecedorExistente) {
            if ($fornecedorExistente['nifFornecedor'] === $nif) {
                throw new Exception("Já existe outro fornecedor registado com este NIF.");
            }
            if ($fornecedorExistente['email'] === $email) {
                throw new Exception("Já existe outro fornecedor registado com este email.");
            }
        }

        // Consultar registo antigo
        $stmtAntigo = execute_query(
            "SELECT nome, nifFornecedor, contactoTelefonico, email, website, idPessoaResponsavel, tipoFornecedor FROM Fornecedor WHERE idFornecedor = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
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
            ]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Fornecedor', $id, $antigo, [
            'nome' => $nome,
            'nifFornecedor' => $nif,
            'contactoTelefonico' => $telefone,
            'email' => $email,
            'website' => empty($website) ? null : $website,
            'idPessoaResponsavel' => $idPessoa,
            'tipoFornecedor' => $tipoEnum->value
        ]);

        $_SESSION['success_message'] = "Fornecedor editado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar fornecedor: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../suppliers.php");
exit;
