<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/classes.php';

// ============================================================
// Gestão de Sessão
// ============================================================

function start_session()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function check_session()
{
    return isset($_SESSION['utilizador']);
}

function redirect_if_not_logged($redirect_to = 'private/login/login.php', ?array $required_permissions = null)
{
    start_session();
    if (!check_session()) {
        header("Location: " . BASE_URL . $redirect_to);
        exit;
    }

    // Carregar as instâncias de Pessoa e Utilizador para a sessão, caso ainda não existam
    if ((!isset($_SESSION['pessoaAtual']) || !isset($_SESSION['userAtual'])) && isset($_SESSION['id_utilizador'])) {
        try {
            $ligacao = connect_to_db();
            $stmt = execute_query(
                "SELECT u.idUtilizador, u.idPessoa, u.emailAutenticacao, u.password, u.idPerfil, u.ativo as utilizador_ativo, u.dataCriacao as utilizador_dataCriacao, u.dataAtualizacao as utilizador_dataAtualizacao,
                        p.nome as pessoa_nome, p.email as pessoa_email, p.contactoTelefonico as pessoa_contacto, p.nif as pessoa_nif, p.funcao as pessoa_funcao, p.departamento as pessoa_departamento, p.ativo as pessoa_ativo, p.dataCriacao as pessoa_dataCriacao, p.dataAtualizacao as pessoa_dataAtualizacao,
                        pf.idPerfil as perfil_id, pf.nome as perfil_nome, pf.dataCriacao as perfil_dataCriacao, pf.dataAtualizacao as perfil_dataAtualizacao
                FROM Utilizador u
                INNER JOIN Pessoa p ON u.idPessoa = p.idPessoa
                LEFT JOIN Perfil pf ON u.idPerfil = pf.idPerfil
                WHERE u.idUtilizador = :id",
                ['id' => $_SESSION['id_utilizador']],
                $ligacao
            );
            $dados = $stmt->fetch(PDO::FETCH_OBJ);

            if ($dados) {
                $_SESSION['pessoaAtual'] = new Pessoa(
                    (string) $dados->idPessoa,
                    (string) $dados->pessoa_nome,
                    (string) $dados->pessoa_email,
                    (string) $dados->pessoa_contacto,
                    (string) $dados->pessoa_nif,
                    $dados->pessoa_funcao ? Funcao::tryFrom((string) $dados->pessoa_funcao) : null,
                    $dados->pessoa_departamento ? (string) $dados->pessoa_departamento : null,
                    (bool) $dados->pessoa_ativo,
                    new DateTime($dados->pessoa_dataCriacao),
                    $dados->pessoa_dataAtualizacao ? new DateTime($dados->pessoa_dataAtualizacao) : new DateTime()
                );

                $_SESSION['userAtual'] = new Utilizador(
                    (string) $dados->idUtilizador,
                    (string) $dados->idPessoa,
                    (string) $dados->emailAutenticacao,
                    (string) $dados->password,
                    (string) $dados->idPerfil,
                    (bool) $dados->utilizador_ativo,
                    new DateTime($dados->utilizador_dataCriacao),
                    $dados->utilizador_dataAtualizacao ? new DateTime($dados->utilizador_dataAtualizacao) : new DateTime(),
                    new Perfil(
                        (string) $dados->perfil_id,
                        (string) $dados->perfil_nome,
                        new DateTime($dados->perfil_dataCriacao),
                        $dados->perfil_dataAtualizacao ? new DateTime($dados->perfil_dataAtualizacao) : new DateTime()
                    )
                );
            }
        } catch (Exception $e) {
            // Ignorar silenciosamente
        }
    }

    // Carregar permissões se não existirem (ex: sessão antiga ou login feito antes da feature)
    if (!isset($_SESSION['permissoes']) && isset($_SESSION['userAtual'])) {
        try {
            $ligacao = connect_to_db();
            $stmtPerms = execute_query(
                "SELECT pm.chave, pp.possui
                 FROM PerfilPermissao pp
                 INNER JOIN Permissao pm ON pp.idPermissao = pm.idPermissao
                 WHERE pp.idPerfil = :idPerfil AND pm.ativo = 1",
                ['idPerfil' => $_SESSION['userAtual']->getIdPerfil()],
                $ligacao
            );
            $permissoes = [];
            while ($row = $stmtPerms->fetch(PDO::FETCH_OBJ)) {
                $permissoes[$row->chave] = (bool) $row->possui;
            }
            $_SESSION['permissoes'] = $permissoes;
        } catch (Exception $e) {
            $_SESSION['permissoes'] = [];
        }
    }

    // Verificar permissões obrigatórias, se fornecidas
    if ($required_permissions !== null) {
        foreach ($required_permissions as $perm) {
            if (!tem_permissao($perm)) {
                // Se falhar alguma permissão, redireciona para a dashboard
                $_SESSION['server_error'] = "Não tem permissão para aceder a esta página.";
                header("Location: " . BASE_URL . "private/index.php");
                exit;
            }
        }
    }
}

function logout_and_redirect($redirect_to = 'private/login/login.php')
{
    start_session();
    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . $redirect_to);
    exit;
}

// ============================================================
// Encriptação e desencriptação de valores com OpenSSL
// ============================================================

function aes_encrypt($value)
{
    return bin2hex(openssl_encrypt(
        $value,
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    ));
}

function aes_decrypt($value)
{
    if (!is_string($value) || strlen($value) % 2 !== 0)
        return false; // proteção básica 

    return openssl_decrypt(
        hex2bin($value),
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
}

// ============================================================
// Conexão à Base de Dados
// ============================================================

function connect_to_db(): PDO
{
    try {
        $port = defined('MYSQL_PORT') ? MYSQL_PORT : '3306';
        $pdo = new PDO(
            "mysql:host=" . MYSQL_HOST . ";port=" . $port . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
            MYSQL_USERNAME,
            MYSQL_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão à base de dados. Tente novamente mais tarde.");
    }
}

function execute_query(string $sql, array $params = [], ?PDO $ligacao = null): PDOStatement
{
    $ligacao ??= connect_to_db();
    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// ============================================================
// Funções de Utilidade
// ============================================================

function get_user_initials(string $name): string
{
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        if (strlen($word) > 0) {
            $initials .= strtoupper($word[0]);
        }
    }
    return $initials;
}

function capitalize_name(string $str): string
{
    $str = trim($str);
    if ($str === '')
        return '';
    return ucwords(strtolower($str));
}

function tem_permissao(string $chave): bool
{
    return isset($_SESSION['permissoes'][$chave]) && $_SESSION['permissoes'][$chave] === true;
}

// ============================================================
// Funções de Auditoria
// ============================================================

// Regista uma entrada no histórico de auditoria.
function registar_auditoria(
    $ligacao,
    $tabelaAfetada,
    $idRegistoAfetado,
    $acao,
    $campoAfetado = null,
    $valorAntigo = null,
    $valorNovo = null
) {
    $idUtilizador = $_SESSION['id_utilizador'] ?? null;

    execute_query(
        "INSERT INTO HistoricoAuditoria 
         (idUtilizador, tabelaAfetada, idRegistoAfetado, acao, campoAfetado, valorAntigo, valorNovo)
         VALUES (:idUtilizador, :tabela, :idRegisto, :acao, :campo, :antigo, :novo)",
        [
            'idUtilizador' => $idUtilizador,
            'tabela' => $tabelaAfetada,
            'idRegisto' => $idRegistoAfetado,
            'acao' => $acao,
            'campo' => $campoAfetado,
            'antigo' => $valorAntigo,
            'novo' => $valorNovo
        ],
        $ligacao
    );
}

// Compara valores antigos com novos e regista uma entrada de auditoria por cada campo alterado
function registar_auditoria_edicao(
    $ligacao,
    $tabelaAfetada,
    $idRegistoAfetado,
    $valoresAntigos,
    $valoresNovos
) {
    foreach ($valoresNovos as $campo => $valorNovo) {
        $valorAntigo = $valoresAntigos[$campo] ?? null;
        if ((string) $valorAntigo !== (string) $valorNovo) {
            registar_auditoria(
                $ligacao,
                $tabelaAfetada,
                $idRegistoAfetado,
                'Edição',
                $campo,
                $valorAntigo,
                $valorNovo
            );
        }
    }
}