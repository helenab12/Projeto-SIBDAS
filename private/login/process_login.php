<?php
require_once __DIR__ . "/../../config/funcoes.php";
start_session();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['server_error'] = 'Método não permitido.';
    header("Location: " . BASE_URL . "private/login/login.php");
    exit;
}

try {
    // 1. Verificar se os campos estão preenchidos
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    if (empty($email) || empty($password)) {
        $_SESSION['server_error'] = 'Preencha todos os campos.';
        header("Location: " . BASE_URL . "private/login/login.php");
        exit;
    }

    $ligacao = connect_to_db();

    // 2. Verificar Utilizador e Pessoa
    $comando = execute_query(
        "SELECT u.*, p.email, AES_DECRYPT(u.password, :key) as clear_password
        FROM Utilizador u
        INNER JOIN Pessoa p ON u.idPessoa = p.idPessoa
        WHERE p.email = :email AND u.ativo = 1 AND p.ativo = 1",
        ['key' => MYSQL_AES_KEY, 'email' => $email],
        $ligacao
    );
    $utilizador = $comando->fetch(PDO::FETCH_OBJ);

    // 3. Validação de senha
    if (!$utilizador) {
        $_SESSION['server_error'] = 'Login inválido';
        header('Location: ' . BASE_URL . 'private/login/login.php');
        exit;
    }

    $db_pass = $utilizador->clear_password ?? $utilizador->password;
    if (!password_verify($password, $db_pass) && $password !== $db_pass) {
        $_SESSION['server_error'] = 'Login inválido';
        header('Location: ' . BASE_URL . 'private/login/login.php');
        exit;
    }

    // 3. Guarda os campos na sessão (usando os nomes corretos das colunas da tabela)
    $_SESSION['utilizador'] = $utilizador->email;
    $_SESSION['id_utilizador'] = $utilizador->idUtilizador;

    header('Location: ' . BASE_URL . 'private/index.php');
    exit;
} catch (PDOException $err) {
    $_SESSION['server_error'] = "Erro ao gravar os dados: " . $err->getMessage();
    header('Location: ' . BASE_URL . 'private/login/login.php');
    exit;
}