<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idPiso = (int) aes_decrypt($_POST['floor-id']);
        $nomePiso = trim($_POST['floor-name'] ?? '');

        // Sanitizar dados
        $dadosSanitizados = Piso::sanitizarDados(['nome' => $nomePiso]);
        $nomePiso = $dadosSanitizados['nome'] ?? $nomePiso;

        // Validar dados
        if (empty($nomePiso)) {
            throw new Exception("O nome do piso não pode estar vazio.");
        }

        // Consultar registos
        $stmtPiso = execute_query(
            "SELECT idEdificio FROM Piso WHERE idPiso = :id",
            ['id' => $idPiso]);
        $pisoRow = $stmtPiso->fetch(PDO::FETCH_ASSOC);

        if (!$pisoRow) {
            throw new Exception("Piso não encontrado.");
        }

        $idEdificio = $pisoRow['idEdificio'];

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idPiso FROM Piso WHERE nome = :nome AND idEdificio = :idEdificio AND idPiso != :id",
            ['nome' => $nomePiso, 'idEdificio' => $idEdificio, 'id' => $idPiso]);

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe um piso (ativo ou inativo) com este nome neste edifício.");
        }

        // Consultar registos
        $stmtAntigo = execute_query(
            "SELECT nome FROM Piso WHERE idPiso = :id",
            ['id' => $idPiso]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
        execute_query(
            "UPDATE Piso SET nome = :nome WHERE idPiso = :id",
            ['nome' => $nomePiso, 'id' => $idPiso]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Piso', $idPiso, $antigo, [
            'nome' => $nomePiso
        ]);

        $_SESSION['success_message'] = "Piso editado com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar piso: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
