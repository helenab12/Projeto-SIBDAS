<?php
require_once __DIR__ . '/../config/funcoes.php';
redirect_if_not_logged();

// Definir cabeçalho para JSON
header('Content-Type: application/json');

// Apenas utilizadores com sessão iniciada podem aceder
if (!check_session()) {
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Devolver objeto vazio se a query for muito curta
if (mb_strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$ligacao = connect_to_db();

// Parâmetros para match no início do campo ou no início de qualquer palavra
$qStart = $query . '%';
$qMiddle = '% ' . $query . '%';

$results = [];

try {
    // 1. Equipamentos
    if (tem_permissao('view.equipments')) {
        $stmtEquipamentos = execute_query(
            "SELECT e.idEquipamento, e.designacao, e.codigoInterno, m.nome as marca, c.nome as categoria 
             FROM Equipamento e 
             LEFT JOIN Marca m ON e.idMarca = m.idMarca 
             LEFT JOIN CategoriaEquipamento c ON e.idCategoria = c.idCategoria 
             WHERE e.ativo = 1 AND e.arquivado = 0 
               AND (e.codigoInterno LIKE :qStart OR e.codigoInterno LIKE :qMiddle 
                    OR e.designacao LIKE :qStart OR e.designacao LIKE :qMiddle 
                    OR e.modelo LIKE :qStart OR e.modelo LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsEq = [];
        while ($row = $stmtEquipamentos->fetch(PDO::FETCH_ASSOC)) {
            $itemsEq[] = [
                'title' => $row['designacao'],
                'subtitle' => "{$row['codigoInterno']} • " . ($row['marca'] ?? 'Sem Marca') . " • " . ($row['categoria'] ?? 'Sem Categoria'),
                'url' => BASE_URL . 'private/inventory/equipments/detailed_view.php?id=' . urlencode(aes_encrypt((string) $row['idEquipamento']))
            ];
        }
        if (!empty($itemsEq)) {
            $results[] = [
                'type' => 'equipamentos',
                'title' => 'Equipamentos (' . count($itemsEq) . ')',
                'icon' => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" /><polyline points="3.27 6.96 12 12.01 20.73 6.96" /><line x1="12" y1="22.08" x2="12" y2="12" />',
                'bg' => 'var(--primary-50)',
                'color' => 'var(--primary-500)',
                'items' => $itemsEq
            ];
        }
    }

    // 2. Componentes
    if (tem_permissao('view.components')) {
        $stmtComponentes = execute_query(
            "SELECT idComponente, codigoInterno, descricao, stock 
             FROM Componente 
             WHERE ativo = 1 
               AND (codigoInterno LIKE :qStart OR codigoInterno LIKE :qMiddle 
                    OR descricao LIKE :qStart OR descricao LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsComp = [];
        while ($row = $stmtComponentes->fetch(PDO::FETCH_ASSOC)) {
            $itemsComp[] = [
                'title' => $row['descricao'],
                'subtitle' => "{$row['codigoInterno']} • Stock: {$row['stock']}",
                'url' => BASE_URL . 'private/inventory/components.php?search=' . urlencode($row['codigoInterno'])
            ];
        }
        if (!empty($itemsComp)) {
            $results[] = [
                'type' => 'componentes',
                'title' => 'Componentes (' . count($itemsComp) . ')',
                'icon' => '<path d="M20 16V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v9m16 0H4m16 0 1.28 2.55a1 1 0 0 1-.9 1.45H3.62a1 1 0 0 1-.9-1.45L4 16" />',
                'bg' => 'color-mix(in srgb, var(--warning) 10%, transparent)',
                'color' => 'var(--warning)',
                'items' => $itemsComp
            ];
        }
    }

    // 3. Pessoas
    if (tem_permissao('view.pessoas')) {
        $stmtPessoas = execute_query(
            "SELECT idPessoa, nome, funcao, departamento 
             FROM Pessoa 
             WHERE ativo = 1 
               AND (nome LIKE :qStart OR nome LIKE :qMiddle 
                    OR email LIKE :qStart OR email LIKE :qMiddle 
                    OR funcao LIKE :qStart OR funcao LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsPessoa = [];
        while ($row = $stmtPessoas->fetch(PDO::FETCH_ASSOC)) {
            $itemsPessoa[] = [
                'title' => $row['nome'],
                'subtitle' => ($row['funcao'] ?? 'Sem função') . " • " . ($row['departamento'] ?? 'Sem departamento'),
                'url' => BASE_URL . 'private/entities/people_management.php?search=' . urlencode(aes_encrypt((string) $row['idPessoa']))
            ];
        }
        if (!empty($itemsPessoa)) {
            $results[] = [
                'type' => 'pessoas',
                'title' => 'Pessoas (' . count($itemsPessoa) . ')',
                'icon' => '<path d="m14.305 19.53.923-.382" /><path d="m15.228 16.852-.923-.383" /><path d="m16.852 15.228-.383-.923" /><path d="m16.852 20.772-.383.924" /><path d="m19.148 15.228.383-.923" /><path d="m19.53 21.696-.382-.924" /><path d="M2 21a8 8 0 0 1 10.434-7.62" /><path d="m20.772 16.852.924-.383" /><path d="m20.772 19.148.924.383" /><circle cx="10" cy="8" r="5" /><circle cx="18" cy="18" r="3" />',
                'bg' => 'color-mix(in srgb, var(--success) 10%, transparent)',
                'color' => 'var(--success)',
                'items' => $itemsPessoa
            ];
        }
    }

    // 4. Fornecedores
    if (tem_permissao('view.fornecedores')) {
        $stmtFornecedores = execute_query(
            "SELECT idFornecedor, nome, tipoFornecedor, email 
             FROM Fornecedor 
             WHERE ativo = 1 
               AND (nome LIKE :qStart OR nome LIKE :qMiddle 
                    OR nifFornecedor LIKE :qStart OR nifFornecedor LIKE :qMiddle 
                    OR email LIKE :qStart OR email LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsForn = [];
        while ($row = $stmtFornecedores->fetch(PDO::FETCH_ASSOC)) {
            $itemsForn[] = [
                'title' => $row['nome'],
                'subtitle' => ($row['tipoFornecedor'] ?? 'S/ Tipo') . " • " . ($row['email'] ?? 'S/ Email'),
                'url' => BASE_URL . 'private/entities/suppliers.php?search=' . urlencode(aes_encrypt((string) $row['idFornecedor']))
            ];
        }
        if (!empty($itemsForn)) {
            $results[] = [
                'type' => 'fornecedores',
                'title' => 'Fornecedores (' . count($itemsForn) . ')',
                'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><path d="M16 3.128a4 4 0 0 1 0 7.744" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><circle cx="9" cy="7" r="4" />',
                'bg' => 'color-mix(in srgb, var(--info) 10%, transparent)',
                'color' => 'var(--info)',
                'items' => $itemsForn
            ];
        }
    }

    // 5. Utilizadores
    if (tem_permissao('view.users')) {
        $stmtUtilizadores = execute_query(
            "SELECT u.idUtilizador, u.emailAutenticacao, p.nome as pessoaNome, pf.nome as perfilNome 
             FROM Utilizador u 
             LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa 
             LEFT JOIN Perfil pf ON u.idPerfil = pf.idPerfil 
             WHERE u.ativo = 1 
               AND (u.emailAutenticacao LIKE :qStart OR u.emailAutenticacao LIKE :qMiddle 
                    OR p.nome LIKE :qStart OR p.nome LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsUsers = [];
        while ($row = $stmtUtilizadores->fetch(PDO::FETCH_ASSOC)) {
            $itemsUsers[] = [
                'title' => $row['pessoaNome'] ?? 'Desconhecido',
                'subtitle' => "{$row['emailAutenticacao']} • Perfil: " . ($row['perfilNome'] ?? 'Sem Perfil'),
                'url' => BASE_URL . 'private/security/users.php?search=' . urlencode(aes_encrypt((string) $row['idUtilizador']))
            ];
        }
        if (!empty($itemsUsers)) {
            $results[] = [
                'type' => 'utilizadores',
                'title' => 'Utilizadores (' . count($itemsUsers) . ')',
                'icon' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />',
                'bg' => 'color-mix(in srgb, #8B5CF6 10%, transparent)',
                'color' => '#8B5CF6',
                'items' => $itemsUsers
            ];
        }
    }

    // 6. Categorias
    if (tem_permissao('view.categorias')) {
        $stmtCategorias = execute_query(
            "SELECT idCategoria, nome, descricao 
             FROM CategoriaEquipamento 
             WHERE ativo = 1 
               AND (nome LIKE :qStart OR nome LIKE :qMiddle) 
             LIMIT 5",
            ['qStart' => $qStart, 'qMiddle' => $qMiddle],
            $ligacao
        );

        $itemsCat = [];
        while ($row = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
            $desc = mb_strlen((string) $row['descricao']) > 50 ? mb_substr((string) $row['descricao'], 0, 50) . '...' : $row['descricao'];
            $itemsCat[] = [
                'title' => $row['nome'],
                'subtitle' => $desc ?: 'Sem descrição',
                'url' => BASE_URL . 'private/inventory/categories.php?search=' . urlencode(aes_encrypt((string) $row['idCategoria']))
            ];
        }
        if (!empty($itemsCat)) {
            $results[] = [
                'type' => 'categorias',
                'title' => 'Categorias (' . count($itemsCat) . ')',
                'icon' => '<path d="M20 10a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1h-2.5a1 1 0 0 1-.8-.4l-.9-1.2A1 1 0 0 0 15 3h-2a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" /><path d="M20 21a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1h-2.9a1 1 0 0 1-.88-.55l-.42-.85a1 1 0 0 0-.92-.6H13a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1Z" /><path d="M3 5a2 2 0 0 0 2 2h3" /><path d="M3 3v13a2 2 0 0 0 2 2h3" />',
                'bg' => 'color-mix(in srgb, #EC4899 10%, transparent)',
                'color' => '#EC4899',
                'items' => $itemsCat
            ];
        }
    }

    echo json_encode($results);

} catch (Exception $e) {
    echo json_encode(['error' => 'Erro na base de dados']);
}
