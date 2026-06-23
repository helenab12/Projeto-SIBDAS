<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idEdificio = (int) aes_decrypt($_POST['building-id']);
        $nomeEdificio = trim($_POST['building-name'] ?? '');

        // Sanitizar dados
        $nomeEdificio = capitalize_name($nomeEdificio);
        $dadosSanitizados = Edificio::sanitizarDados([
            'nome' => $nomeEdificio
        ]);
        $nomeEdificio = $dadosSanitizados['nome'] ?? $nomeEdificio;

        // Validar dados
        $erros = Edificio::validarDados($dadosSanitizados);

        if (!empty($erros)) {
            throw new Exception(implode(", ", $erros));
        }

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idEdificio FROM Edificio WHERE nome = :nome AND idEdificio != :id",
            ['nome' => $nomeEdificio, 'id' => $idEdificio]);

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um edifício (ativo ou inativo) com este nome.");
        }

        // Consultar registos
        $stmtAntigo = execute_query(
            "SELECT nome FROM Edificio WHERE idEdificio = :id",
            ['id' => $idEdificio]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
        execute_query(
            "UPDATE Edificio SET nome = :nome WHERE idEdificio = :id",
            ['nome' => $nomeEdificio, 'id' => $idEdificio]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Edificio', $idEdificio, $antigo, [
            'nome' => $nomeEdificio
        ]);

        $_SESSION['success_message'] = "Edifício alterado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar edifício: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
