<?php
require_once(__DIR__ . "/../../config/funcoes.php");
redirect_if_not_logged();

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

$auditoria = [];
try {
    $stmt = execute_query(
        "SELECT ha.*, p.nome AS nomeUtilizador 
         FROM HistoricoAuditoria ha
         LEFT JOIN Utilizador u ON ha.idUtilizador = u.idUtilizador
         LEFT JOIN Pessoa p ON u.idPessoa = p.idPessoa
         ORDER BY ha.dataCriacao DESC"
    );

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data = new DateTime($row['dataCriacao']);

        $badgeClass = 'badge-primary';
        if ($row['acao'] === 'Criação') {
            $badgeClass = 'badge-success';
        } else if ($row['acao'] === 'Remoção') {
            $badgeClass = 'badge-error';
        }

        $encryptedId = aes_encrypt((string) $row['idRegistoAfetado']);
        $truncatedId = substr($encryptedId, 0, 4) . '...';

        $searchUrl = match ($row['tabelaAfetada']) {
            'Equipamento' => BASE_URL . "/private/inventory/equipments/equipment_list.php?search=" . urlencode($encryptedId),
            'Componente' => BASE_URL . "/private/inventory/components.php?search=" . urlencode($encryptedId),
            'CategoriaEquipamento' => BASE_URL . "/private/inventory/categories.php?search=" . urlencode($encryptedId),
            'Fornecedor' => BASE_URL . "/private/entities/suppliers.php?search=" . urlencode($encryptedId),
            'PedidoDemonstracao' => BASE_URL . "/private/front_office/inbox.php?search=" . urlencode($encryptedId),
            'Pessoa' => BASE_URL . "/private/entities/people_management.php?search=" . urlencode($encryptedId),
            'Utilizador' => BASE_URL . "/private/security/users.php?search=" . urlencode($encryptedId),
            'Perfil' => BASE_URL . "/private/security/profiles.php?search=" . urlencode($encryptedId),
            'Permissao' => BASE_URL . "/private/security/permissions.php?search=" . urlencode($encryptedId),
            'Edificio', 'Piso', 'Servico', 'Localizacao' => BASE_URL . "/private/entities/locations.php?search=" . urlencode($encryptedId),
            default => null
        };

        $idRender = htmlspecialchars($truncatedId);
        if ($searchUrl) {
            $idRender = "<a href=\"" . htmlspecialchars($searchUrl) . "\" class=\"text-primary-500 text-decoration-none fw-700\">" . htmlspecialchars($truncatedId) . "</a>";
        }

        $auditoria[] = [
            'data' => $data->format('d/m/Y, H:i:s'),
            'acao' => $row['acao'],
            'badgeClass' => $badgeClass,
            'utilizador' => $row['nomeUtilizador'] ?? 'Sistema',
            'tabela' => $row['tabelaAfetada'],
            'idRegisto' => $idRender,
            'campo' => $row['campoAfetado'] ?? '-',
            'valorAntigo' => $row['valorAntigo'] ?? '-',
            'valorNovo' => $row['valorNovo'] ?? '-'
        ];
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar histórico de auditoria: " . $e->getMessage();
}


include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';
?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="content-container gap-6">
        <!-- Titulo -->
        <div class="d-flex justify-content-between align-items-center w-100 dashboard-title">
            <div class="d-flex flex-column gap-1">
                <h1>Logs de Auditoria</h1>
                <p class="text-secondary fw-400"><?php echo count($auditoria); ?> registos encontrados</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-outline gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download-icon lucide-download">
                        <path d="M12 15V3" />
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                    Exportar Logs
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" class="flex-grow-1">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" id="search-global-audit"
                        placeholder="Pesquisar por ação, utilizador ou detalhes...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Ações" id="filter-global-audit-type">
                    <option value="" selected>Todas as ações</option>
                    <option value="Criação">Criação</option>
                    <option value="Edição">Edição</option>
                    <option value="Remoção">Remoção</option>
                </select>
            </div>
        </div>

        <!-- Lista de Logs de Auditoria -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="globalAuditTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>AÇÃO</th>
                        <th>UTILIZADOR</th>
                        <th>TABELA</th>
                        <th>ID REGISTO</th>
                        <th>CAMPO</th>
                        <th>VALOR ANTIGO</th>
                        <th>VALOR NOVO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditoria as $item): ?>
                        <tr>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                            </td>
                            <td>
                                <span
                                    class="badge <?= htmlspecialchars($item['badgeClass']) ?>"><?= htmlspecialchars($item['acao']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-400 text-capitalize"><?= htmlspecialchars($item['tabela']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= $item['idRegisto'] ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['campo']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['valorAntigo']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['valorNovo']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </section>
</div>


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