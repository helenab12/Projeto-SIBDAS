<?php
// Carregar dependências
require_once(__DIR__ . "/../../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['maintenances.delete']);

// Validar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados do POST
        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedManId = trim($_POST['maintenance-id'] ?? '');

        // Validar dados
        if (empty($encryptedEqId) || empty($encryptedManId)) {
            throw new Exception("IDs não fornecidos.");
        }

        // Desencriptar IDs
        $idEquipamento = aes_decrypt($encryptedEqId);
        $idManutencao = aes_decrypt($encryptedManId);

        // Validar desencriptação
        if ($idEquipamento === false || $idManutencao === false) {
            throw new Exception("IDs inválidos.");
        }

        // Atualizar manutenção
        execute_query(
            "UPDATE Manutencao SET ativo = 0, dataAtualizacao = NOW() WHERE idManutencao = :idMan AND idEquipamento = :idEq",
            [
                'idMan' => $idManutencao,
                'idEq' => $idEquipamento
            ]);

        // Registar auditoria
        registar_auditoria($ligacao, 'Manutencao', $idManutencao, 'Remoção', 'ativo', '1', '0');

        // Definir mensagem de sucesso
        $_SESSION['success_message'] = "Registo de manutenção eliminado com sucesso!";

    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

// Construir link
$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId)
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=manutencoes"
    : "../equipment_list.php";

// Redirecionar
header("Location: " . $redirectUrl);
exit;
