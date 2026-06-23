<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

start_session();
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['content.edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_textos'])) {
    try {
        // Ligar à BD
        $ligacao = connect_to_db();
        $ligacao->beginTransaction();
        // Recolher dados do POST
        $novosTextos = $_POST['textos'] ?? [];

        $mudancas = false;

        foreach ($novosTextos as $chaveEncriptada => $novoValor) {
            $chaveDecriptada = aes_decrypt($chaveEncriptada);
            if ($chaveDecriptada === false) continue;
            
            // Sanitizar dados
            $dadosSanitizados = ConteudoTexto::sanitizarDados(['valor' => $novoValor]);
            $novoValor = $dadosSanitizados['valor'] ?? $novoValor;

            // Consultar registos
            $stmtSelect = execute_query("SELECT idConteudo, valor FROM ConteudoFrontOffice WHERE chaveSecao = :id", ['id' => $chaveDecriptada], $ligacao);
            $antigo = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($antigo && $antigo['valor'] !== $novoValor) {
                // Atualizar registo
                execute_query(
                    "UPDATE ConteudoFrontOffice SET valor = :valor, dataAtualizacao = NOW() WHERE chaveSecao = :chaveSecao",
                    ['valor' => $novoValor, 'chaveSecao' => $chaveDecriptada],
                    $ligacao
                );
                
                // Registar auditoria
                registar_auditoria_edicao(
                    $ligacao,
                    'ConteudoFrontOffice',
                    $antigo['idConteudo'],
                    ['valor' => $antigo['valor']],
                    ['valor' => $novoValor]
                );
                $mudancas = true;
            }
        }
        
        $ligacao->commit();
        if ($mudancas) {
            $_SESSION['success_message'] = "Textos atualizados com sucesso!";
        } else {
            $_SESSION['success_message'] = "Nenhuma alteração foi feita aos textos.";
        }
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
        if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao atualizar textos: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../content_management.php");
exit;
