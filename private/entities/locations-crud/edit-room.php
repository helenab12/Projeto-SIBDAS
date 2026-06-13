<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idLocalizacao = (int) aes_decrypt($_POST['room-id']);
        $nomeSala = trim($_POST['room-name'] ?? '');

        // Sanitização
        $nomeSala = capitalize_name($nomeSala);

        // Validação
        if (empty($nomeSala)) {
            throw new Exception("O nome da sala não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Buscar o idServico desta sala para verificação de duplicados
        $stmtSala = execute_query(
            "SELECT idServico FROM Localizacao WHERE idLocalizacao = :id",
            ['id' => $idLocalizacao],
            $ligacao
        );
        $salaRow = $stmtSala->fetch(PDO::FETCH_ASSOC);

        if (!$salaRow) {
            throw new Exception("Sala não encontrada.");
        }

        $idServico = $salaRow['idServico'];

        // Verificar se já existe outra Sala com o mesmo nome no mesmo Serviço
        $stmtVerificar = execute_query(
            "SELECT idLocalizacao FROM Localizacao WHERE nomeSala = :nome AND idServico = :idServico AND idLocalizacao != :id",
            ['nome' => $nomeSala, 'idServico' => $idServico, 'id' => $idLocalizacao],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe uma sala (ativa ou inativa) com este nome neste serviço.");
        }

        // Fazer o update
        execute_query(
            "UPDATE Localizacao SET nomeSala = :nome WHERE idLocalizacao = :id",
            ['nome' => $nomeSala, 'id' => $idLocalizacao],
            $ligacao
        );

        $_SESSION['success_message'] = "Sala editada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar sala: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
