<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idServico = (int) aes_decrypt($_POST['service-id']);
        $nomeSala = trim($_POST['room-name'] ?? '');

        // Sanitização
        $nomeSala = capitalize_name($nomeSala);

        // Validação
        if (empty($nomeSala)) {
            throw new Exception("O nome da sala não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Verificar se já existe uma Sala com o mesmo nome no mesmo Serviço
        $stmtVerificar = execute_query(
            "SELECT idLocalizacao FROM Localizacao WHERE nomeSala = :nome AND idServico = :idServico",
            ['nome' => $nomeSala, 'idServico' => $idServico],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe uma sala (ativa ou inativa) com este nome neste serviço.");
        }

        // Inserir a nova sala
        execute_query(
            "INSERT INTO Localizacao (idServico, nomeSala, ativo) VALUES (:idServico, :nome, 1)",
            ['idServico' => $idServico, 'nome' => $nomeSala],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Localizacao', $novoId, 'Criação');

        $_SESSION['success_message'] = "Sala criada com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar sala: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
