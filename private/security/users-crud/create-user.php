<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['users.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $emailPessoa = strtolower(trim($_POST['user-email'] ?? ''));
        $emailAutenticacao = strtolower(trim($_POST['user-auth-email'] ?? ''));
        $password = $_POST['user-password'] ?? '';
        $idPerfil = $_POST['user-role'] ?? '';

        if (empty(trim($emailPessoa))) {
            throw new Exception("O email do funcionário é obrigatório.");
        }

        // Validação básica
        $perfilFicticio = new Perfil('-1', 'Temporário', new DateTime(), new DateTime());
        $erros = Utilizador::validarDados([
            'idUtilizador' => '-1', // ID fictício
            'idPessoa' => '-1', // ID fictício
            'emailAutenticacao' => $emailAutenticacao,
            'password' => $password,
            'idPerfil' => $idPerfil,
            'dataCriacao' => new DateTime(),
            'dataAtualizacao' => new DateTime(),
            'perfil' => $perfilFicticio
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        $ligacao = connect_to_db();

        // 1. Procurar Pessoa pelo Email
        $stmtPessoa = execute_query(
            "SELECT idPessoa FROM Pessoa WHERE email = :email AND ativo = 1",
            ['email' => $emailPessoa],
            $ligacao
        );
        $pessoa = $stmtPessoa->fetch(PDO::FETCH_ASSOC);

        if (!$pessoa) {
            throw new Exception("Não existe nenhuma pessoa ativa registada com este email.");
        }

        $idPessoa = $pessoa['idPessoa'];

        // 2. Verificar se já existe um Utilizador para esta Pessoa
        $stmtUtilizador = execute_query(
            "SELECT idUtilizador, ativo FROM Utilizador WHERE idPessoa = :idPessoa",
            ['idPessoa' => $idPessoa],
            $ligacao
        );

        $utilizadorExistente = $stmtUtilizador->fetch(PDO::FETCH_ASSOC);
        if ($utilizadorExistente) {
            if ($utilizadorExistente['ativo'] == 1) {
                throw new Exception("Esta pessoa já tem um utilizador ativo associado.");
            } else {
                throw new Exception("Esta pessoa já tem um utilizador associado, mas encontra-se inativo. Por favor, reative o utilizador existente em vez de criar um novo.");
            }
        }

        // 3. Verificar se o email de autenticação já existe
        $stmtAuth = execute_query(
            "SELECT idUtilizador FROM Utilizador WHERE emailAutenticacao = :email",
            ['email' => $emailAutenticacao],
            $ligacao
        );

        if ($stmtAuth->fetch()) {
            throw new Exception("O email de autenticação fornecido já está em uso.");
        }

        // 4. Verificar se o perfil existe
        $stmtPerfil = execute_query(
            "SELECT idPerfil FROM Perfil WHERE idPerfil = :idPerfil AND ativo = 1",
            ['idPerfil' => $idPerfil],
            $ligacao
        );

        if (!$stmtPerfil->fetch()) {
            throw new Exception("O perfil selecionado não é válido.");
        }

        // 5. Hash da password antes de encriptar
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 6. Inserir o novo utilizador
        execute_query(
            "INSERT INTO Utilizador (idPessoa, emailAutenticacao, password, idPerfil, ativo) 
             VALUES (:idPessoa, :emailAutenticacao, AES_ENCRYPT(:password_hashada, :key), :idPerfil, 1)",
            [
                'idPessoa' => $idPessoa,
                'emailAutenticacao' => $emailAutenticacao,
                'password_hashada' => $hashedPassword,
                'key' => MYSQL_AES_KEY,
                'idPerfil' => $idPerfil
            ],
            $ligacao
        );

        // Registar auditoria
        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Utilizador', $novoId, 'Criação');

        $_SESSION['success_message'] = "Utilizador criado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar utilizador: " . $e->getMessage();
    }
}

header("Location: ../users.php");
exit;
