<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['people.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedId = $_POST['person-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        // Recolher dados
        $nome = capitalize_name(trim($_POST['person-name'] ?? ''));
        $nif = trim($_POST['person-nif'] ?? '');
        $funcao = trim($_POST['person-role'] ?? '');
        $departamento = trim($_POST['person-department'] ?? '');
        $email = strtolower(trim($_POST['person-email'] ?? ''));
        $telefone = trim($_POST['person-phone'] ?? '');

        // Validar e sanitizar dados
        $dadosSanitizados = Pessoa::sanitizarDados([
            'id' => (string) $id,
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

        // Consultar registos
        $stmt = execute_query(
            "SELECT idPessoa, nif, email FROM Pessoa WHERE (nif = :nif OR email = :email) AND idPessoa != :id",
            ['nif' => $nif, 'email' => $email, 'id' => $id]);
        $existente = $stmt->fetch();
        if ($existente) {
            if ($existente['nif'] === $nif) {
                throw new Exception("O NIF introduzido já está registado noutra pessoa.");
            }
            if ($existente['email'] === $email) {
                throw new Exception("O Email introduzido já está registado noutra pessoa.");
            }
        }

        // Consultar registo antigo
        $stmtAntigo = execute_query(
            "SELECT nome, email, contactoTelefonico, nif, funcao, departamento FROM Pessoa WHERE idPessoa = :id",
            ['id' => $id]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
        execute_query(
            "UPDATE Pessoa 
             SET nome = :nome, email = :email, contactoTelefonico = :telefone, nif = :nif, funcao = :funcao, departamento = :departamento, dataAtualizacao = NOW() 
             WHERE idPessoa = :id",
            [
                'nome' => $nome,
                'email' => $email,
                'telefone' => $telefone,
                'nif' => $nif,
                'funcao' => $funcao,
                'departamento' => $departamento,
                'id' => $id
            ]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Pessoa', $id, $antigo, [
            'nome' => $nome,
            'email' => $email,
            'contactoTelefonico' => $telefone,
            'nif' => $nif,
            'funcao' => $funcao,
            'departamento' => $departamento
        ]);

        $_SESSION['success_message'] = "Pessoa editada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar pessoa: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../people_management.php");
exit;
