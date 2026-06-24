<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['suppliers.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_fornecedor'])) {
    try {
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
        $tipoEnum = TipoFornecedor::tryFrom((string)$tipo);
        if (!$tipoEnum) {
            throw new Exception("Tipo de fornecedor inválido.");
        }

        // Sanitizar e validar dados
        $dadosSanitizados = Fornecedor::sanitizarDados([
            'idFornecedor' => '-1', // ID fictício para validação
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

        // Ligar à BD
        $ligacao = connect_to_db();

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT nifFornecedor, email FROM Fornecedor WHERE nifFornecedor = :nif OR email = :email",
            ['nif' => $nif, 'email' => $email],
            $ligacao
        );

        $fornecedorExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
        if ($fornecedorExistente) {
            if ($fornecedorExistente['nifFornecedor'] === $nif) {
                throw new Exception("Já existe um fornecedor registado com este NIF.");
            }
            if ($fornecedorExistente['email'] === $email) {
                throw new Exception("Já existe um fornecedor registado com este email.");
            }
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Fornecedor (nome, nifFornecedor, contactoTelefonico, email, website, idPessoaResponsavel, tipoFornecedor, ativo, dataCriacao)
             VALUES (:nome, :nif, :telefone, :email, :website, :idPessoa, :tipo, 1, NOW())",
            [
                'nome' => $nome,
                'nif' => $nif,
                'telefone' => $telefone,
                'email' => $email,
                'website' => empty($website) ? null : $website,
                'idPessoa' => $idPessoa,
                'tipo' => $tipoEnum->value
            ],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        // Registar auditoria
        registar_auditoria($ligacao, 'Fornecedor', $novoId, 'Criação');

        $_SESSION['success_message'] = "Fornecedor criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar fornecedor: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../suppliers.php");
exit;
