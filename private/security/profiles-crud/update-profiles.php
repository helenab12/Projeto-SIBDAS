<?php
// Carregar dependências
require_once(__DIR__ . "/../../../config/funcoes.php");

// Iniciar sessão
start_session();
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['profiles.edit']);

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ligar à BD
$ligacao = connect_to_db();
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $postedPermissions = $_POST['permissions'] ?? [];

        foreach ($postedPermissions as $encPerfilId => $perms) {
            // Desencriptar ID
            $idPerfil = aes_decrypt($encPerfilId);
            if ($idPerfil === false) {
                throw new Exception("Dados de alteração inválidos.");
            }
            $idPerfil = (int) $idPerfil;

            // Query BD
            $stmtPerfil = execute_query("SELECT nome FROM Perfil WHERE idPerfil = :id", ['id' => $idPerfil], $ligacao);
            $nomePerfil = $stmtPerfil->fetchColumn() ?: "Perfil $idPerfil";

            foreach ($perms as $encPermId => $value) {
                // Desencriptar ID
                $idPermissao = aes_decrypt($encPermId);
                if ($idPermissao === false) {
                    throw new Exception("Dados de alteração inválidos.");
                }
                $idPermissao = (int) $idPermissao;

                // Valor tem que ser 0 ou 1
                if ($value !== '0' && $value !== '1') {
                    throw new Exception("Dados de alteração inválidos.");
                }
                $possui = (int) $value;

                // Query BD
                $stmtOld = execute_query(
                    "SELECT possui FROM PerfilPermissao WHERE idPerfil = :idPerfil AND idPermissao = :idPermissao",
                    ['idPerfil' => $idPerfil, 'idPermissao' => $idPermissao],
                    $ligacao
                );
                $oldRow = $stmtOld->fetch(PDO::FETCH_ASSOC);
                $oldPossui = $oldRow ? (int)$oldRow['possui'] : null;

                if ($oldPossui !== $possui) {
                    // Executar query
                    execute_query(
                        "INSERT INTO PerfilPermissao (idPerfil, idPermissao, possui) 
                         VALUES (:idPerfil, :idPermissao, :possui) 
                         ON DUPLICATE KEY UPDATE possui = :possui",
                        [
                            'idPerfil' => $idPerfil,
                            'idPermissao' => $idPermissao,
                            'possui' => $possui
                        ],
                        $ligacao
                    );
                    
                    registar_auditoria($ligacao, 'Perfil', $idPermissao, 'Edição', $nomePerfil, $oldPossui === null ? 'N/A' : (string)$oldPossui, (string)$possui);
                }
            }
        }

        $ligacao->commit();
        $_SESSION['success_message'] = "Alterações de perfis guardadas com sucesso!";
        $ligacao = null;
    } catch (Exception $e) {
        // Capturar erro
if (isset($ligacao) && $ligacao->inTransaction()) {
            $ligacao->rollBack();
        }
        $_SESSION['server_error'] = "Erro ao guardar alterações de perfis: " . $e->getMessage();
    }
}

// Redirecionar
header("Location: ../profiles.php");
exit;
