<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idEdificio = (int) aes_decrypt($_POST['building-id']);
        $nomePiso = trim($_POST['floor-name'] ?? '');

        // Sanitização
        $nomePiso = capitalize_name($nomePiso);

        // Validação
        if (empty($nomePiso)) {
            throw new Exception("O nome do piso não pode estar vazio.");
        }

        $ligacao = connect_to_db();

        // Verificar se já existe um Piso com o mesmo nome no mesmo Edifício
        $stmtVerificar = execute_query(
            "SELECT idPiso FROM Piso WHERE nome = :nome AND idEdificio = :idEdificio",
            ['nome' => $nomePiso, 'idEdificio' => $idEdificio],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um piso (ativo ou inativo) com este nome neste edifício.");
        }

        // Inserir o novo piso
        execute_query(
            "INSERT INTO Piso (idEdificio, nome, ativo) VALUES (:idEdificio, :nome, 1)",
            ['idEdificio' => $idEdificio, 'nome' => $nomePiso],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        registar_auditoria($ligacao, 'Piso', $novoId, 'Criação');

        $_SESSION['success_message'] = "Piso criado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao criar piso: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
