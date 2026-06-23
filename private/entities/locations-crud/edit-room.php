<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idLocalizacao = (int) aes_decrypt($_POST['room-id']);
        $nomeSala = trim($_POST['room-name'] ?? '');

        // Sanitizar dados
        $dadosSanitizados = Localizacao::sanitizarDados(['nomeSala' => $nomeSala]);
        $nomeSala = $dadosSanitizados['nomeSala'] ?? $nomeSala;

        // Validar dados
        if (empty($nomeSala)) {
            throw new Exception("O nome da sala não pode estar vazio.");
        }

        // Consultar registos
        $stmtSala = execute_query(
            "SELECT idServico FROM Localizacao WHERE idLocalizacao = :id",
            ['id' => $idLocalizacao]);
        $salaRow = $stmtSala->fetch(PDO::FETCH_ASSOC);

        if (!$salaRow) {
            throw new Exception("Sala não encontrada.");
        }

        $idServico = $salaRow['idServico'];

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idLocalizacao FROM Localizacao WHERE nomeSala = :nome AND idServico = :idServico AND idLocalizacao != :id",
            ['nome' => $nomeSala, 'idServico' => $idServico, 'id' => $idLocalizacao]);

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe uma sala (ativa ou inativa) com este nome neste serviço.");
        }

        // Consultar registos
        $stmtAntigo = execute_query(
            "SELECT nomeSala FROM Localizacao WHERE idLocalizacao = :id",
            ['id' => $idLocalizacao]);
        $antigo = $stmtAntigo->fetch(PDO::FETCH_ASSOC);

        // Atualizar registo
        execute_query(
            "UPDATE Localizacao SET nomeSala = :nome WHERE idLocalizacao = :id",
            ['nome' => $nomeSala, 'id' => $idLocalizacao]);

        // Registar auditoria
        registar_auditoria_edicao($ligacao, 'Localizacao', $idLocalizacao, $antigo, [
            'nomeSala' => $nomeSala
        ]);

        $_SESSION['success_message'] = "Sala editada com sucesso!";
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao editar sala: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
