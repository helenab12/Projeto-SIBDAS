<?php

require_once __DIR__ . '/config.php';

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

function redirect_if_not_logged($redirect_to = '/private/login/login.php')
{
    start_session();
    if (!check_session()) {
        header("Location: " . BASE_URL . $redirect_to);
        exit;
    }
}

function logout_and_redirect($redirect_to = '/private/login/login.php')
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
        $pdo = new PDO(
            "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
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

?>