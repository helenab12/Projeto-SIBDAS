<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['users.edit']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_utilizador'])) {
    try {
        // Recolher dados do POST
        $encryptedUserId = $_POST['user-id'] ?? '';
        $emailAutenticacao = $_POST['user-auth-email'] ?? '';
        $password = $_POST['user-password'] ?? '';
        $idPerfil = $_POST['user-role'] ?? '';
        // Sanitização usando a classe
        $dadosSanitizados = Utilizador::sanitizarDados([
            'emailAutenticacao' => $emailAutenticacao
        ]);
        $emailAutenticacao = $dadosSanitizados['emailAutenticacao'] ?? $emailAutenticacao;

        if (empty(trim($encryptedUserId)) || empty($emailAutenticacao) || empty(trim($idPerfil))) {
            throw new Exception("Por favor, preencha todos os campos obrigatórios.");
        }

        // Desencriptar ID
        $idUtilizador = aes_decrypt($encryptedUserId);
        if (!$idUtilizador) {
            throw new Exception("ID de utilizador inválido.");
        }

        // 1. Verificar se o utilizador existe
        $stmtUtilizador = execute_query(
            "SELECT idUtilizador FROM Utilizador WHERE idUtilizador = :id AND ativo = 1",
            ['id' => $idUtilizador]);
        if (!$stmtUtilizador->fetch()) {
            throw new Exception("O utilizador que está a tentar editar não existe ou está inativo.");
        }

        // 2. Verificar se o email de autenticação já está em uso por OUTRO utilizador
        $stmtAuth = execute_query(
            "SELECT idUtilizador FROM Utilizador WHERE emailAutenticacao = :email AND idUtilizador != :id",
            ['email' => $emailAutenticacao, 'id' => $idUtilizador]);
        if ($stmtAuth->fetch()) {
            throw new Exception("O email de autenticação fornecido já está em uso por outro utilizador.");
        }

        // 3. Verificar se o perfil existe
        $stmtPerfil = execute_query(
            "SELECT idPerfil FROM Perfil WHERE idPerfil = :idPerfil AND ativo = 1",
            ['idPerfil' => $idPerfil]);
        if (!$stmtPerfil->fetch()) {
            throw new Exception("O perfil selecionado não é válido.");
        }

        // 4. Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT emailAutenticacao, idPerfil FROM Utilizador WHERE idUtilizador = :id",
            ['id' => $idUtilizador]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // 5. Update Utilizador
        if (!empty($password)) {
            if (strlen($password) < 8) {
                throw new Exception("A password deve ter pelo menos 8 caracteres.");
            }
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Executar query
            execute_query(
                "UPDATE Utilizador 
                 SET emailAutenticacao = :email, 
                     password = AES_ENCRYPT(:password_hashada, :key), 
                     idPerfil = :perfil,
                     dataAtualizacao = CURRENT_TIMESTAMP
                 WHERE idUtilizador = :id",
                [
                    'email' => $emailAutenticacao,
                    'password_hashada' => $hashedPassword,
                    'key' => MYSQL_AES_KEY,
                    'perfil' => $idPerfil,
                    'id' => $idUtilizador
                ]);
        } else {
            // Executar query
            execute_query(
                "UPDATE Utilizador 
                 SET emailAutenticacao = :email, 
                     idPerfil = :perfil,
                     dataAtualizacao = CURRENT_TIMESTAMP
                 WHERE idUtilizador = :id",
                [
                    'email' => $emailAutenticacao,
                    'perfil' => $idPerfil,
                    'id' => $idUtilizador
                ]);
        }

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Utilizador', $idUtilizador, $antigo, [
            'emailAutenticacao' => $emailAutenticacao,
            'idPerfil' => $idPerfil
        ]);

        if (!empty($password)) {
            registar_auditoria($ligacao, 'Utilizador', $idUtilizador, 'Edição', 'password', '***', '***');
        }

        $_SESSION['success_message'] = "Utilizador atualizado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
$_SESSION['server_error'] = "Erro ao atualizar utilizador: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../users.php");
exit;
