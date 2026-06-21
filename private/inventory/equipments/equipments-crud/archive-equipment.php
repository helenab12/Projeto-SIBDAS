<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['equipments.archive']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $encryptedId = $_POST['equipment-id'] ?? null;
        if (!$encryptedId) {
            throw new Exception("ID inválido.");
        }
        $id = aes_decrypt($encryptedId);
        if ($id === false) {
            throw new Exception("ID inválido.");
        }
        $id = (int) $id;

        execute_query(
            "UPDATE Equipamento SET arquivado = 1 WHERE idEquipamento = :id",
            ['id' => $id]);

        registar_auditoria($ligacao, 'Equipamento', $id, 'Edição', 'arquivado', '0', '1');

        $_SESSION['success_message'] = "Equipamento arquivado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao arquivar equipamento: " . $e->getMessage();
    }
}

header("Location: ../equipment_list.php");
exit;
