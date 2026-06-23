<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['locations.create']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recolher dados
        $idServico = (int) aes_decrypt($_POST['service-id']);
        $nomeSala = trim($_POST['room-name'] ?? '');

        // Sanitizar dados
        $dadosSanitizados = Localizacao::sanitizarDados(['nomeSala' => $nomeSala]);
        $nomeSala = $dadosSanitizados['nomeSala'] ?? $nomeSala;

        // Validar dados
        if (empty($nomeSala)) {
            throw new Exception("O nome da sala não pode estar vazio.");
        }

        // Ligar à BD
        $ligacao = connect_to_db();

        // Consultar registos
        $stmtVerificar = execute_query(
            "SELECT idLocalizacao FROM Localizacao WHERE nomeSala = :nome AND idServico = :idServico",
            ['nome' => $nomeSala, 'idServico' => $idServico],
            $ligacao
        );

        if ($stmtVerificar->fetch()) {
            throw new Exception("Já existe uma sala (ativa ou inativa) com este nome neste serviço.");
        }

        // Inserir registo
        execute_query(
            "INSERT INTO Localizacao (idServico, nomeSala, ativo) VALUES (:idServico, :nome, 1)",
            ['idServico' => $idServico, 'nome' => $nomeSala],
            $ligacao
        );

        $novoId = $ligacao->lastInsertId();
        // Registar auditoria
        registar_auditoria($ligacao, 'Localizacao', $novoId, 'Criação');

        $_SESSION['success_message'] = "Sala criada com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        $_SESSION['server_error'] = "Erro ao criar sala: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../locations.php");
exit;
