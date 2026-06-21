<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['people.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = $_POST['person-name'] ?? '';
        $nif = $_POST['person-nif'] ?? '';
        $funcao = $_POST['person-role'] ?? '';
        $departamento = $_POST['person-department'] ?? '';
        $email = strtolower(trim($_POST['person-email'] ?? ''));
        $telefone = $_POST['person-phone'] ?? '';

        // Sanitização
        $nome = capitalize_name($nome);
        $nif = trim($nif);
        $departamento = trim($departamento);
        $telefone = trim($telefone);

        // Validação usando a classe Pessoa
        $dadosSanitizados = Pessoa::sanitizarDados([
            'id' => '-1', // ID fictício para validação de criação
            'nome' => $nome,
            'email' => $email,
            'contactoTelefonico' => $telefone,
            'nif' => $nif,
            'funcao' => $funcao,
            'departamento' => $departamento,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime()
        ]);
        $nome = $dadosSanitizados['nome'] ?? $nome;
        $email = $dadosSanitizados['email'] ?? $email;
        $telefone = $dadosSanitizados['contactoTelefonico'] ?? $telefone;
        $nif = $dadosSanitizados['nif'] ?? $nif;
        $funcao = $dadosSanitizados['funcao'] ?? $funcao;
        $departamento = $dadosSanitizados['departamento'] ?? $departamento;
        $erros = Pessoa::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // Verificar se já existe uma pessoa com o mesmo NIF ou Email
        $stmtVerificar = execute_query(
            "SELECT nif, email FROM Pessoa WHERE nif = :nif OR email = :email",
            ['nif' => $nif, 'email' => $email],
            $ligacao
        );

        $pessoaExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
        if ($pessoaExistente) {
            if ($pessoaExistente['nif'] === $nif) {
                throw new Exception("Já existe uma pessoa registada com este NIF.");
            }
            if ($pessoaExistente['email'] === $email) {
                throw new Exception("Já existe uma pessoa registada com este email.");
            }
        }

        // Inserir na base de dados
        execute_query(
            "INSERT INTO Pessoa (nome, email, contactoTelefonico, nif, funcao, departamento, ativo, dataCriacao)
             VALUES (:nome, :email, :contactoTelefonico, :nif, :funcao, :departamento, 1, NOW())",
            [
                'nome' => $nome,
                'email' => $email,
                'contactoTelefonico' => $telefone,
                'nif' => $nif,
                'funcao' => $funcao,
                'departamento' => $departamento
            ],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Pessoa', $novoId, 'Criação');

        $_SESSION['success_message'] = "Pessoa criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar pessoa: " . $e->getMessage();
    }
}

header("Location: ../people_management.php");
exit;
