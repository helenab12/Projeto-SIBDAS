<?php
// Carregar dependências
require_once __DIR__ . "/../../config/funcoes.php";
// Iniciar sessão
start_session();

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['server_error'] = 'Método não permitido.';
    // Redirecionar
    header("Location: " . BASE_URL . "private/login/login.php");
    exit;
}

try {
    // Recolher dados do POST
    $email = $_POST["email"] ?? null;
    $password = $_POST["password"] ?? null;

    // Sanitizar dados
    if (!empty($email)) {
        $email = sanitizar_array_dados(['email' => $email])['email'];
    }

    // Validar preenchimento
    if (empty($email) || empty($password)) {
        $_SESSION['server_error'] = 'Preencha todos os campos.';
        // Redirecionar
        header("Location: " . BASE_URL . "private/login/login.php");
        exit;
    }

    // Ligar à BD
$ligacao = connect_to_db();

    // Consultar utilizador
    $comando = execute_query(
        "SELECT u.*, p.email as pessoa_email, AES_DECRYPT(u.password, :key) as clear_password
        FROM Utilizador u
        INNER JOIN Pessoa p ON u.idPessoa = p.idPessoa
        WHERE u.emailAutenticacao = :email AND u.ativo = 1 AND p.ativo = 1",
        ['key' => MYSQL_AES_KEY, 'email' => $email],
        $ligacao
    );
    $utilizador = $comando->fetch(PDO::FETCH_OBJ);

    // Validar existência
    if (!$utilizador) {
        $_SESSION['server_error'] = 'Login inválido';
        // Redirecionar
        header('Location: ' . BASE_URL . 'private/login/login.php');
        exit;
    }

    // Verificar senha
    $db_pass = $utilizador->clear_password ?? $utilizador->password;
    if (!password_verify($password, $db_pass) && $password !== $db_pass) {
        $_SESSION['server_error'] = 'Login inválido';
        // Redirecionar
        header('Location: ' . BASE_URL . 'private/login/login.php');
        exit;
    }

    // Guardar na sessão
    $_SESSION['utilizador'] = $utilizador->emailAutenticacao;
    $_SESSION['id_utilizador'] = $utilizador->idUtilizador;

    // Carregar permissões
    $stmtPerms = execute_query(
        "SELECT pm.chave, pp.possui
         FROM PerfilPermissao pp
         INNER JOIN Permissao pm ON pp.idPermissao = pm.idPermissao
         WHERE pp.idPerfil = :idPerfil AND pm.ativo = 1",
        ['idPerfil' => $utilizador->idPerfil],
        $ligacao
    );
    $permissoes = [];
    while ($row = $stmtPerms->fetch(PDO::FETCH_OBJ)) {
        $permissoes[$row->chave] = (bool) $row->possui;
    }
    $_SESSION['permissoes'] = $permissoes;

    // Redirecionar
    header('Location: ' . BASE_URL . 'private/index.php');
    exit;
} catch (PDOException $err) {
        // Capturar erro
$_SESSION['server_error'] = "Erro ao gravar os dados: " . $err->getMessage();
    // Redirecionar
    header('Location: ' . BASE_URL . 'private/login/login.php');
    exit;
}