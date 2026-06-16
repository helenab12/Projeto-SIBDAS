<?php
require_once(__DIR__ . "/../../../config/funcoes.php");
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

$listaEquipamentos = [];
$categoriasDisponiveis = [];
$localizacoesDisponiveis = [];

try {
    $ligacao = connect_to_db();

    // Obter Categorias para os dropdowns
    $stmtCategorias = execute_query(
        "SELECT * FROM CategoriaEquipamento WHERE ativo = 1 ORDER BY nome ASC",
        [],
        $ligacao
    );
    while ($row = $stmtCategorias->fetch(PDO::FETCH_ASSOC)) {
        $categoriasDisponiveis[] = new Categoria(
            (string) $row['idCategoria'],
            $row['nome'],
            $row['codigoPrefix'],
            $row['descricao'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
        );
    }

    // Obter Localizações para os dropdowns (Formato: Edifício, Piso, Serviço, Sala)
    $stmtLocalizacoes = execute_query(
        "SELECT 
            l.idLocalizacao,
            l.idServico,
            e.nome AS edificioNome,
            p.nome AS pisoNome,
            s.nome AS servicoNome,
            l.nomeSala AS salaNome
         FROM Localizacao l
         JOIN Servico s ON l.idServico = s.idServico
         JOIN Piso p ON s.idPiso = p.idPiso
         JOIN Edificio e ON p.idEdificio = e.idEdificio
         WHERE l.ativo = 1 AND s.ativo = 1 AND p.ativo = 1 AND e.ativo = 1
         ORDER BY e.nome, p.nome, s.nome, l.nomeSala ASC",
        [],
        $ligacao
    );
    while ($row = $stmtLocalizacoes->fetch(PDO::FETCH_ASSOC)) {
        $nomeCompleto = $row['edificioNome'] . ', ' . $row['pisoNome'] . ', ' . $row['servicoNome'] . ', ' . $row['salaNome'];
        $localizacoesDisponiveis[] = new Localizacao(
            (int) $row['idLocalizacao'],
            (int) $row['idServico'],
            $nomeCompleto
        );
    }

    // Obter Equipamentos
    $stmtEquipamentos = execute_query(
        "SELECT e.*, m.nome as marcaNome 
         FROM Equipamento e 
         LEFT JOIN Marca m ON e.idMarca = m.idMarca 
         WHERE e.ativo = 1 AND e.arquivado = 1 
         ORDER BY e.designacao ASC",
        [],
        $ligacao
    );

    while ($row = $stmtEquipamentos->fetch(PDO::FETCH_ASSOC)) {
        $listaEquipamentos[] = new Equipamento(
            (string) $row['idEquipamento'],
            $row['idCategoria'] ? (string) $row['idCategoria'] : null,
            $row['codigoInterno'],
            $row['designacao'],
            $row['idMarca'] ? (string) $row['idMarca'] : null,
            $row['modelo'],
            $row['numeroSerie'],
            $row['dataAquisicao'] ? new DateTime($row['dataAquisicao']) : null,
            $row['dataFabrico'] ? new DateTime($row['dataFabrico']) : null,
            (float) $row['custoAquisicao'],
            TipoEntrada::from($row['tipoEntrada']),
            EstadoEquipamento::from($row['estadoAtual']),
            CriticidadeEquipamento::from($row['criticidade']),
            $row['observacoes'] ?? '',
            $row['idLocalizacao'] ? (string) $row['idLocalizacao'] : null,
            (bool) $row['arquivado'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $row['marcaNome']
        );
    }
} catch (Exception $e) {
    $server_error = "Erro ao carregar dados: " . $e->getMessage();
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
                <h1>Equipamentos Arquivados</h1>
                <p class="text-secondary fw-400"><?= count($listaEquipamentos) ?> equipamentos arquivados</p>
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
                    <input type="text" class="form-item w-100 search-bar-input"
                        placeholder="Pesquisar por nome, nº série, marca, modelo...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" id="filter-estado" aria-label="Filtro Estado">
                    <option value="" selected>Estado</option>
                    <?php foreach (EstadoEquipamento::cases() as $estado): ?>
                        <option value="<?= htmlspecialchars($estado->value) ?>"><?= htmlspecialchars($estado->value) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <select class="form-select" id="filter-criticidade" aria-label="Filtro Criticidade">
                    <option value="" selected>Criticidade</option>
                    <?php foreach (CriticidadeEquipamento::cases() as $criticidade): ?>
                        <option value="<?= htmlspecialchars($criticidade->value) ?>">
                            <?= htmlspecialchars($criticidade->value) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <select class="form-select" id="filter-categoria" aria-label="Filtro Categoria">
                    <option value="" selected>Categoria</option>
                    <?php foreach ($categoriasDisponiveis as $catDisp): ?>
                        <option value="<?= htmlspecialchars($catDisp->getNome()) ?>">
                            <?= htmlspecialchars($catDisp->getNome()) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>EQUIPAMENTO</th>
                        <th>CATEGORIA</th>
                        <th>LOCALIZAÇÃO</th>
                        <th>ESTADO</th>
                        <th>CRITICIDADE</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaEquipamentos as $equipamento): ?>
                        <?php
                        $encryptedEqId = aes_encrypt((string) $equipamento->getIdEquipamento());

                        $catNome = "Sem Categoria";
                        $catDescricao = "";
                        $idCat = $equipamento->getIdCategoria();
                        if ($idCat !== null) {
                            foreach ($categoriasDisponiveis as $catDisp) {
                                if ($catDisp->getIdCategoria() === $idCat) {
                                    $catNome = $catDisp->getNome();
                                    $catDescricao = $catDisp->getDescricao();
                                    break;
                                }
                            }
                        }

                        $locNome = "Desconhecida";
                        $idLoc = $equipamento->getIdLocalizacao();
                        if ($idLoc !== null) {
                            foreach ($localizacoesDisponiveis as $locDisp) {
                                if ((string) $locDisp->getIdLocalizacao() === $idLoc) {
                                    $locNome = $locDisp->getNomeSala(); // A localizacao tem getNomeSala que na verdade usamos para passar a string inteira
                                    break;
                                }
                            }
                        }

                        // Estado Badge Class
                        $estado = $equipamento->getEstadoAtual()->value;
                        $statusClass = match ($estado) {
                            EstadoEquipamento::EM_MANUTENCAO->value, EstadoEquipamento::EM_CALIBRACAO->value => 'equipment-badge-status-maintenance',
                            EstadoEquipamento::INATIVO->value, EstadoEquipamento::EM_QUARENTENA->value => 'equipment-badge-status-inactive',
                            EstadoEquipamento::ABATIDO->value => 'equipment-badge-status-scrapped',
                            default => 'equipment-badge-status-active',
                        };

                        // Criticidade Badge Class
                        $criticidade = $equipamento->getCriticidade()->value;
                        $critClass = match ($criticidade) {
                            CriticidadeEquipamento::MEDIA->value => 'equipment-badge-criticality-medium',
                            CriticidadeEquipamento::ALTA->value => 'equipment-badge-criticality-high',
                            CriticidadeEquipamento::CRITICO->value => 'equipment-badge-criticality-critical',
                            default => 'equipment-badge-criticality-low',
                        };

                        $marcaModeloString = trim($equipamento->getMarcaNome() . ' ' . $equipamento->getModelo());
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="table-icon-wrapper equipment-icon-wrapper">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-box text-primary">
                                            <path
                                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                            </path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="detailed_view.php?id=<?= htmlspecialchars($encryptedEqId) ?>"
                                            class="text-primary">
                                            <p class="equipment-title fw-700 mb-0">
                                                <?= htmlspecialchars($equipamento->getDesignacao()) ?>
                                            </p>
                                        </a>
                                        <span
                                            class="equipment-subtitle text-secondary fw-400"><?= htmlspecialchars($marcaModeloString) ?>
                                            &bull; <?= htmlspecialchars($equipamento->getNumeroSerie()) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="equipment-category">
                                <span data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="<?= htmlspecialchars($catDescricao) ?>">
                                    <?= htmlspecialchars($catNome) ?>
                                </span>
                            </td>
                            <td class="equipment-location"><?= htmlspecialchars($locNome) ?></td>
                            <td class="equipment-status">
                                <?php
                                $estadoTooltip = match ($estado) {
                                    'Ativo' => "Equipamento operacional e disponível para uso clínico.",
                                    'Em manutenção' => "Equipamento temporariamente indisponível por estar em manutenção preventiva ou corretiva.",
                                    'Inativo' => "Equipamento fora de serviço temporariamente.",
                                    'Em calibração' => "Equipamento em processo de calibração para garantir precisão.",
                                    'Em Quarentena' => "Equipamento retido aguardando inspeção ou decisão técnica.",
                                    'Abatido' => "Equipamento desativado definitivamente (em fim de vida útil).",
                                    default => "Estado: " . $estado,
                                };
                                ?>
                                <span class="equipment-badge <?= $statusClass ?> equipment-badge-tooltip"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="<?= htmlspecialchars($estadoTooltip) ?>">
                                    <?= htmlspecialchars($estado) ?>
                                </span>
                            </td>
                            <td class="equipment-criticality">
                                <?php
                                $criticidadeTooltip = match ($criticidade) {
                                    'Crítico' => "Equipamento vital — falha pode resultar em risco de vida para o paciente.",
                                    'Alta' => "Equipamento importante — falha impacta significativamente o serviço clínico.",
                                    'Média' => "Equipamento de impacto moderado — existem alternativas para suprir a falha.",
                                    'Baixa' => "Equipamento de apoio — falha com impacto mínimo no serviço.",
                                    default => "Criticidade: " . $criticidade,
                                };
                                ?>
                                <span class="equipment-badge <?= $critClass ?> equipment-badge-tooltip"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="<?= htmlspecialchars($criticidadeTooltip) ?>">
                                    <?= htmlspecialchars($criticidade) ?>
                                </span>
                            </td>
                            <td class="text-end equipment-actions">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-icon opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-white"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="19" cy="12" r="1" />
                                            <circle cx="5" cy="12" r="1" />
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end action-dropdown-menu">
                                        <li>
                                            <a class="dropdown-item action-dropdown-item text-primary" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#equipment-unarchive-modal-<?= htmlspecialchars($encryptedEqId) ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-archive-restore">
                                                    <rect width="20" height="5" x="2" y="3" rx="1" />
                                                    <path d="M4 8v11a2 2 0 0 0 2 2h2" />
                                                    <path d="M20 8v11a2 2 0 0 1-2 2h-2" />
                                                    <path d="m9 15 3-3 3 3" />
                                                    <path d="M12 12v9" />
                                                </svg>
                                                Desarquivar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

    </section>
</div>

<?php
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<?php foreach ($listaEquipamentos as $equipamento): ?>
    <?php $encryptedEqId = aes_encrypt((string) $equipamento->getIdEquipamento()); ?>
    <!-- Modal de Desarquivo de Equipamento -->
    <div class="modal fade" id="equipment-unarchive-modal-<?= htmlspecialchars($encryptedEqId) ?>" tabindex="-1"
        aria-labelledby="equipmentUnarchiveModalLabel-<?= htmlspecialchars($encryptedEqId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title"
                            id="equipmentUnarchiveModalLabel-<?= htmlspecialchars($encryptedEqId) ?>">
                            Desarquivar Equipamento</h2>
                        <span class="text-secondary fw-400">O equipamento será restaurado para a lista de
                            equipamentos.</span>
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
                    <form method="POST" action="equipments-crud/unarchive-equipment.php">
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedEqId) ?>">
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
                                        <p class="text-secondary">Tem a certeza que deseja desarquivar o equipamento?</p>
                                        <h2 class="fw-700">"<?= htmlspecialchars($equipamento->getDesignacao()) ?>"</h2>
                                        <span class="text-muted">Tipo: Equipamento</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-glowing text-white">Sim,
                                    Desarquivar</button>
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
include_once BASE_PATH . 'private/includes/footer.php';
?>