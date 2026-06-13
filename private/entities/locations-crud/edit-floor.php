<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idPiso = (int) aes_decrypt($_POST['floor-id']);
        $nomePiso = trim($_POST['floor-name'] ?? '');

        // Sanitização
        $nomePiso = capitalize_name($nomePiso);

        // Validação
        if (empty($nomePiso)) {
            throw new Exception("O nome do piso não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Buscar o idEdificio deste piso para a verificação de duplicados
        $stmtPiso = execute_query(
            "SELECT idEdificio FROM Piso WHERE idPiso = :id",
            ['id' => $idPiso],
            $ligacao
        );
        $pisoRow = $stmtPiso->fetch(PDO::FETCH_ASSOC);

        if (!$pisoRow) {
            throw new Exception("Piso não encontrado.");
        }

        $idEdificio = $pisoRow['idEdificio'];

        // Verificar se já existe outro Piso com o mesmo nome no mesmo Edifício
        $stmtVerificar = execute_query(
            "SELECT idPiso FROM Piso WHERE nome = :nome AND idEdificio = :idEdificio AND idPiso != :id",
            ['nome' => $nomePiso, 'idEdificio' => $idEdificio, 'id' => $idPiso],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um piso (ativo ou inativo) com este nome neste edifício.");
        }

        // Fazer o update
        execute_query(
            "UPDATE Piso SET nome = :nome WHERE idPiso = :id",
            ['nome' => $nomePiso, 'id' => $idPiso],
            $ligacao
        );

        $_SESSION['success_message'] = "Piso editado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar piso: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
