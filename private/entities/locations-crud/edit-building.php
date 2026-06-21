<?php
require_once(__DIR__ . "/../../../config/funcoes.php");

redirect_if_not_logged('private/login/login.php', ['locations.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $idEdificio = (int) aes_decrypt($_POST['building-id']);
        $nomeEdificio = trim($_POST['building-name'] ?? '');

        // Sanitização
        $nomeEdificio = capitalize_name($nomeEdificio);

        // Validação usando a classe Edificio
        $dadosSanitizados = Edificio::sanitizarDados([
            'nome' => $nomeEdificio
        ]);
        $nomeEdificio = $dadosSanitizados['nome'] ?? $nomeEdificio;
        $erros = Edificio::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Verificar se já existe outro edifício com este nome
        $stmtVerificar = execute_query(
            "SELECT idEdificio FROM Edificio WHERE nome = :nome AND idEdificio != :id",
            ['nome' => $nomeEdificio, 'id' => $idEdificio]);

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um edifício (ativo ou inativo) com este nome.");
        }

        // Ler o estado antigo antes do Update para Auditoria
        $stmtAntigo = execute_query(
            "SELECT nome FROM Edificio WHERE idEdificio = :id",
            ['id' => $idEdificio]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Fazer o update
        execute_query(
            "UPDATE Edificio SET nome = :nome WHERE idEdificio = :id",
            ['nome' => $nomeEdificio, 'id' => $idEdificio]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Edificio', $idEdificio, $antigo, [
            'nome' => $nomeEdificio
        ]);

        $_SESSION['success_message'] = "Edifício alterado com sucesso!";
    } catch (Exception $e) {
        $_SESSION['server_error'] = "Erro ao editar edifício: " . $e->getMessage();
    }
}

header("Location: ../locations.php");
exit;
