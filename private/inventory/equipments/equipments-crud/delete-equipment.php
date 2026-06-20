<?php
require_once(__DIR__ . "/../../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['equipments.delete']);

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

        $ligacao = connect_to_db();
        execute_query(
            "UPDATE Equipamento SET ativo = 0 WHERE idEquipamento = :id",
            ['id' => $id],
            $ligacao
        );

        $_SESSION['success_message'] = "Equipamento eliminado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao eliminar equipamento: " . $e->getMessage();
    }
}

header("Location: ../equipment_list.php");
exit;
