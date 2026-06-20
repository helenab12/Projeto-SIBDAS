<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['maintenances.delete']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = connect_to_db();

        $encryptedEqId = trim($_POST['equipment-id'] ?? '');
        $encryptedManId = trim($_POST['maintenance-id'] ?? '');

        if (empty($encryptedEqId) || empty($encryptedManId)) {
            throw new Exception("IDs não fornecidos.");
        }

        $idEquipamento = aes_decrypt($encryptedEqId);
        $idManutencao = aes_decrypt($encryptedManId);

        if ($idEquipamento === false || $idManutencao === false) {
            throw new Exception("IDs inválidos.");
        }

        execute_query(
            "UPDATE Manutencao SET ativo = 0, dataAtualizacao = NOW() WHERE idManutencao = :idMan AND idEquipamento = :idEq",
            [
                'idMan' => $idManutencao,
                'idEq' => $idEquipamento
            ],
            $ligacao
        );

        registar_auditoria($ligacao, 'Manutencao', $idManutencao, 'Remoção', 'ativo', '1', '0');

        $_SESSION['success_message'] = "Registo de manutenção eliminado com sucesso!";

    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro: " . $e->getMessage();
    }
}

$redirectUrl = isset($encryptedEqId) && !empty($encryptedEqId)
    ? "../detailed_view.php?id=" . urlencode($encryptedEqId) . "&nav=manutencoes"
    : "../equipment_list.php";

header("Location: " . $redirectUrl);
exit;
