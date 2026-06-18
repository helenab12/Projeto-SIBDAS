<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

$success_message = null;
$server_error = null;

if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

class TipoReciclagem
{
    public string $nome;
    public string $caminhoSvg;
    public string $cor;
}

$tiposReciclagem = [
    'equipment' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Equipamentos';
        $type->cor = 'var(--primary-500)';
        $type->caminhoSvg = '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path> <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline> <line x1="12" y1="22.08" x2="12" y2="12"></line>';
        return $type;
    })(),
    'supplier' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Fornecedores';
        $type->cor = 'var(--primary-500)';
        $type->caminhoSvg = '<path d="M10 12h4" /> <path d="M10 8h4" /> <path d="M14 21v-3a2 2 0 0 0-4 0v3" /> <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" /> <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />';
        return $type;
    })(),
    'user' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Utilizadores';
        $type->cor = '#a855f7';
        $type->caminhoSvg = '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /> <path d="M16 3.128a4 4 0 0 1 0 7.744" /> <path d="M22 21v-2a4 4 0 0 0-3-3.87" /> <circle cx="9" cy="7" r="4" />';
        return $type;
    })(),
    'person' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Pessoas';
        $type->cor = 'var(--success)';
        $type->caminhoSvg = '<path d="m14.305 19.53.923-.382" /> <path d="m15.228 16.852-.923-.383" /> <path d="m16.852 15.228-.383-.923" /> <path d="m16.852 20.772-.383.924" /> <path d="m19.148 15.228.383-.923" /> <path d="m19.53 21.696-.382-.924" /> <path d="M2 21a8 8 0 0 1 10.434-7.62" /> <path d="m20.772 16.852.924-.383" /> <path d="m20.772 19.148.924.383" /> <circle cx="10" cy="8" r="5" /> <circle cx="18" cy="18" r="3" />';
        return $type;
    })(),
    'component' => (function () {
        $type = new TipoReciclagem();
        $type->nome = 'Componentes';
        $type->cor = 'var(--secondary)';
        $type->caminhoSvg = '<path d="M5.5 8.5 9 12l-3.5 3.5L2 12l3.5-3.5Z" /><path d="m12 2 3.5 3.5L12 9 8.5 5.5 12 2Z" /><path d="M18.5 8.5 22 12l-3.5 3.5L15 12l3.5-3.5Z" /><path d="m12 15 3.5 3.5L12 22l-3.5-3.5L12 15Z" />';
        return $type;
    })(),
];

class ObjetoReciclado
{
    public string $idEncriptado;
    public string $nomeTabela;
    public TipoReciclagem $tipo;
    public string $nome;
    public string $descricao;
    public DateTime $removidoA;

    public function __construct(string $idEncriptado, string $nomeTabela, string $nome, string $descricao, TipoReciclagem $tipo, DateTime $removidoA)
    {
        $this->idEncriptado = $idEncriptado;
        $this->nomeTabela = $nomeTabela;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->removidoA = $removidoA;
    }
}

$objetosReciclados = [];

try {
    $ligacao = connect_to_db();

    // 1. Equipamentos
    $stmt = execute_query("SELECT idEquipamento, designacao, codigoInterno, modelo, dataAtualizacao FROM Equipamento WHERE ativo = 0", [], $ligacao);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $desc = $row['codigoInterno'] ?: 'S/ Código';
        if (!empty($row['modelo']))
            $desc .= ' • ' . $row['modelo'];
        $dt = !empty($row['dataAtualizacao']) ? new DateTime($row['dataAtualizacao']) : new DateTime();

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['idEquipamento']),
            'Equipamento',
            $row['designacao'] ?: 'Equipamento S/N',
            $desc,
            $tiposReciclagem['equipment'],
            $dt
        );
    }

    // 2. Componentes
    $stmt = execute_query("SELECT idComponente, descricao, codigoInterno, dataAtualizacao FROM Componente WHERE ativo = 0", [], $ligacao);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $desc = $row['codigoInterno'] ?: 'S/ Código';
        $dt = !empty($row['dataAtualizacao']) ? new DateTime($row['dataAtualizacao']) : new DateTime();

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['idComponente']),
            'Componente',
            $row['descricao'] ?: 'Componente S/N',
            $desc,
            $tiposReciclagem['component'],
            $dt
        );
    }

    // 3. Fornecedores
    $stmt = execute_query("SELECT idFornecedor, nome, nifFornecedor, dataAtualizacao FROM Fornecedor WHERE ativo = 0", [], $ligacao);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dt = !empty($row['dataAtualizacao']) ? new DateTime($row['dataAtualizacao']) : new DateTime();
        $desc = "NIF: " . ($row['nifFornecedor'] ?: 'N/D');

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['idFornecedor']),
            'Fornecedor',
            $row['nome'] ?: 'Fornecedor S/N',
            $desc,
            $tiposReciclagem['supplier'],
            $dt
        );
    }

    // 4. Pessoas
    $stmt = execute_query("SELECT idPessoa, nome, funcao, dataAtualizacao FROM Pessoa WHERE ativo = 0", [], $ligacao);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dt = !empty($row['dataAtualizacao']) ? new DateTime($row['dataAtualizacao']) : new DateTime();
        $desc = "Função: " . ($row['funcao'] ?: 'S/ Função');

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['idPessoa']),
            'Pessoa',
            $row['nome'] ?: 'Pessoa S/N',
            $desc,
            $tiposReciclagem['person'],
            $dt
        );
    }

    // 5. Utilizadores
    $stmt = execute_query("
        SELECT u.idUtilizador, u.emailAutenticacao, p.nome, u.dataAtualizacao 
        FROM Utilizador u 
        LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa 
        WHERE u.ativo = 0
    ", [], $ligacao);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dt = !empty($row['dataAtualizacao']) ? new DateTime($row['dataAtualizacao']) : new DateTime();
        $desc = "Email: " . ($row['emailAutenticacao'] ?: 'N/D');

        $objetosReciclados[] = new ObjetoReciclado(
            aes_encrypt($row['idUtilizador']),
            'Utilizador',
            $row['nome'] ?: 'Utilizador S/N',
            $desc,
            $tiposReciclagem['user'],
            $dt
        );
    }

} catch (Exception $e) {
    error_log("Erro ao carregar reciclagem: " . $e->getMessage());
}

// Ordenar por data (mais recente primeiro)
usort($objetosReciclados, function ($a, $b) {
    return $b->removidoA <=> $a->removidoA;
});

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6 recycling">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Reciclagem</h1>
                <p class="text-secondary fw-400">Registos removidos do sistema (Soft Delete).</p>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" class="flex-grow-1 d-flex gap-2">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" id="search-input-recycling"
                        placeholder="Pesquisar na reciclagem..." value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="d-flex gap-2 equipment-list-search-bar-filters">
                    <select class="form-select" id="filter-type-recycling" aria-label="Filtro de Tipo">
                        <option value="" selected>Todos os Tipos</option>
                        <option value="Equipamentos">Equipamentos</option>
                        <option value="Componentes">Componentes</option>
                        <option value="Fornecedores">Fornecedores</option>
                        <option value="Pessoas">Pessoas</option>
                        <option value="Utilizadores">Utilizadores</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Tabela de Reciclagem -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="recyclingTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>Entidade</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Removido a</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objetosReciclados as $object): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="recycle-type-icon d-flex align-items-center justify-content-center padding-2"
                                        style="background-color: color-mix(in srgb, <?php echo $object->tipo->cor; ?> 10%, transparent); color: <?php echo $object->tipo->cor; ?>; width: 36px; height: 36px; border-radius: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide">
                                            <?php echo $object->tipo->caminhoSvg; ?>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-600 item-name"><?php echo htmlspecialchars($object->nome); ?></span>
                                        <span
                                            class="visually-hidden item-hidden-id"><?php echo htmlspecialchars($object->idEncriptado); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-500"><?php echo htmlspecialchars($object->tipo->nome); ?></span>
                            </td>
                            <td>
                                <span class="text-secondary"><?php echo htmlspecialchars($object->descricao); ?></span>
                            </td>
                            <td>
                                <span class="text-secondary"><?php echo $object->removidoA->format('Y-m-d H:i'); ?></span>
                            </td>
                            <td>
                                <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#restore-modal-<?php echo htmlspecialchars($object->idEncriptado); ?>"
                                    class="btn btn-small text-success d-flex align-items-center gap-2 restore badge badge-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2">
                                        <path d="M9 14 4 9l5-5" />
                                        <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                    </svg>
                                    <span class="fw-700 ">Restaurar</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>
</div>

<?php foreach ($objetosReciclados as $object): ?>
    <!-- Modal de Restauro de Registo -->
    <div class="modal fade" id="restore-modal-<?= htmlspecialchars($object->idEncriptado) ?>" tabindex="-1"
        aria-labelledby="restoreModalLabel-<?= htmlspecialchars($object->idEncriptado) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="restoreModalLabel-<?= htmlspecialchars($object->idEncriptado) ?>">
                            Restaurar Registo</h2>
                        <span class="text-secondary fw-400">O registo será restaurado no sistema principal.</span>
                    </div>
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x stroke-secondary">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <form method="POST" action="<?php echo BASE_URL; ?>/private/security/restore_item.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($object->idEncriptado) ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($object->nomeTabela) ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon"
                                    style="background-color: var(--primary-100); color: var(--primary-500);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-archive-restore">
                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                        <path d="M4 8v11a2 2 0 0 0 2 2h2" />
                                        <path d="M20 8v11a2 2 0 0 1-2 2h-2" />
                                        <path d="m9 15 3-3 3 3" />
                                        <path d="M12 12v9" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary">Tem a certeza que deseja restaurar este registo?</p>
                                        <h2 class="fw-700">"<?= htmlspecialchars($object->nome) ?>"</h2>
                                        <span class="text-muted">Tipo: <?= htmlspecialchars($object->tipo->nome) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-glowing text-white">Sim, Restaurar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 100;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($server_error)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($server_error) ?>
                </div>
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
include_once BASE_PATH . 'private/includes/footer.php';
?>