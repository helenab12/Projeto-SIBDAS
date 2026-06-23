<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idEdificio = (int) aes_decrypt($_POST['building-id']);
        $nomePiso = trim($_POST['floor-name'] ?? '');

        // Sanitizar dados
        $dadosSanitizados = Piso::sanitizarDados(['nome' => $nomePiso]);
        $nomePiso = $dadosSanitizados['nome'] ?? $nomePiso;

        // Validar dados
        if (empty($nomePiso)) {
            throw new Exception("O nome do piso não pode estar vazio.");
        }

        // Ligar à BD
        $ligacao = connect_to_db();

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idPiso FROM Piso WHERE nome = :nome AND idEdificio = :idEdificio",
            ['nome' => $nomePiso, 'idEdificio' => $idEdificio],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um piso (ativo ou inativo) com este nome neste edifício.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Piso (idEdificio, nome, ativo) VALUES (:idEdificio, :nome, 1)",
            ['idEdificio' => $idEdificio, 'nome' => $nomePiso],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        // Registar auditoria
        registar_auditoria($ligacao, 'Piso', $novoId, 'Criação');

        $_SESSION['success_message'] = "Piso criado com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar piso: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
