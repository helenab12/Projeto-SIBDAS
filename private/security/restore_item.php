<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged('private/login/login.php', ['recycling.restore']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idStr = $_POST['id'] ?? '';
    $tableName = $_POST['table'] ?? '';

    if (empty($idStr) || empty($tableName)) {
        $_SESSION['server_error'] = "Dados inválidos para o restauro.";
        header("Location: " . BASE_URL . "/private/security/recycling.php");
        exit;
    }

    $id = aes_decrypt($idStr);
    if (!$id) {
        $_SESSION['server_error'] = "ID inválido ou corrompido.";
        header("Location: " . BASE_URL . "/private/security/recycling.php");
        exit;
    }

    try {
        $ligacao = connect_to_db();
        
        $sql = "";
        switch ($tableName) {
            case 'Equipamento':
                $sql = "UPDATE Equipamento SET ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idEquipamento = :id";
                break;
            case 'Componente':
                $sql = "UPDATE Componente SET ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idComponente = :id";
                break;
            case 'Fornecedor':
                $sql = "UPDATE Fornecedor SET ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idFornecedor = :id";
                break;
            case 'Pessoa':
                $sql = "UPDATE Pessoa SET ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idPessoa = :id";
                break;
            case 'Utilizador':
                $sql = "UPDATE Utilizador SET ativo = 1, dataAtualizacao = CURRENT_TIMESTAMP WHERE idUtilizador = :id";
                break;
            default:
                throw new Exception("Entidade desconhecida para restauro.");
        }

        execute_query($sql, ['id' => $id], $ligacao);

        // Registar auditoria
        registar_auditoria($ligacao, $tableName, $id, 'Edição', 'ativo', '0', '1');

        $_SESSION['success_message'] = "Registo de $tableName restaurado com sucesso!";

    } catch (Exception $e) {
        error_log("Erro no restauro: " . $e->getMessage());
        $_SESSION['server_error'] = "Ocorreu um erro ao restaurar o registo.";
    }

    header("Location: " . BASE_URL . "/private/security/recycling.php");
    exit;
}

header("Location: " . BASE_URL . "/private/security/recycling.php");
exit;
