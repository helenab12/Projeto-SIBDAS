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

$listaFornecedores = [];
$pessoasDisponiveis = [];

try {
    $ligacao = connect_to_db();

    // Obter Pessoas para os dropdowns
    $stmtPessoas = execute_query(
        "SELECT idPessoa, nome, email, nif, contactoTelefonico, funcao, departamento, ativo, dataCriacao, dataAtualizacao 
         FROM Pessoa WHERE ativo = 1 AND funcao IN ('Fornecedor', 'Outro') ORDER BY nome ASC",
        [],
        $ligacao
    );
    while ($row = $stmtPessoas->fetch(PDO::FETCH_ASSOC)) {
        $pessoasDisponiveis[] = new Pessoa(
            (string) $row['idPessoa'],
            $row['nome'],
            $row['email'],
            $row['contactoTelefonico'],
            $row['nif'],
            $row['funcao'] ? Funcao::tryFrom($row['funcao']) : null,
            $row['departamento'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
        );
    }

    // Obter Fornecedores
    $stmtFornecedores = execute_query(
        "SELECT f.*, p.nome as pessoa_nome, p.email as pessoa_email, p.contactoTelefonico as pessoa_contacto, 
                p.nif as pessoa_nif, p.funcao as pessoa_funcao, p.departamento as pessoa_departamento, 
                p.ativo as pessoa_ativo, p.dataCriacao as pessoa_dataCriacao, p.dataAtualizacao as pessoa_dataAtualizacao 
         FROM Fornecedor f 
         LEFT JOIN Pessoa p ON f.idPessoaResponsavel = p.idPessoa 
         WHERE f.ativo = 1 
         ORDER BY f.nome ASC",
        [],
        $ligacao
    );

    while ($row = $stmtFornecedores->fetch(PDO::FETCH_ASSOC)) {
        $pessoa = null;
        if ($row['idPessoaResponsavel']) {
            $pessoa = new Pessoa(
                (string) $row['idPessoaResponsavel'],
                $row['pessoa_nome'],
                $row['pessoa_email'],
                $row['pessoa_contacto'],
                $row['pessoa_nif'],
                $row['pessoa_funcao'] ? Funcao::tryFrom($row['pessoa_funcao']) : null,
                $row['pessoa_departamento'],
                (bool) $row['pessoa_ativo'],
                new DateTime($row['pessoa_dataCriacao']),
                new DateTime($row['pessoa_dataAtualizacao'])
            );
        }

        $listaFornecedores[] = new Fornecedor(
            (string) $row['idFornecedor'],
            $row['nome'],
            $row['nifFornecedor'],
            $row['contactoTelefonico'],
            $row['email'],
            $row['website'] ?? '',
            $row['idPessoaResponsavel'] ? (string) $row['idPessoaResponsavel'] : null,
            TipoFornecedor::from($row['tipoFornecedor']),
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $pessoa
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
                <h1>Fornecedores</h1>
                <p class="text-secondary fw-400">Gestão de fornecedores</p>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-open-create-supplier-modal" class="btn btn-primary btn-glowing gap-2"
                    data-bs-toggle="modal" data-bs-target="#supplier-creation-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Criar Fornecedor
                </button>
            </div>
        </div>

        <!-- Barra de Pesquisa -->
        <div class="bento-card padding-4 gap-4 equipment-list-search-bar">
            <form action="" class="flex-grow-1" onsubmit="event.preventDefault();">
                <div class="form-item w-100 position-relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search search-bar-icon position-absolute text-secondary">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <input type="text" class="form-item w-100 search-bar-input" id="search-input"
                        placeholder="Pesquisar por nome, NIF ou email...">
                </div>
            </form>
            <div class="d-flex gap-2 equipment-list-search-bar-filters">
                <select class="form-select" aria-label="Filtro Tipo" id="filter-type">
                    <option value="" selected>Todos os Tipos</option>
                    <option value="Fabricante">Fabricante</option>
                    <option value="Distribuidor">Distribuidor</option>
                    <option value="Assistência Técnica">Assistência Técnica</option>
                    <option value="Consumíveis">Consumíveis</option>
                </select>
            </div>
        </div>

        <!-- Tabela -->
        <div class="bento-card w-100 p-0 border-0" id="table-container">
            <table id="equipmentsTable" class="sibdas-table w-100 display">
                <thead>
                    <tr>
                        <th>FORNECEDOR</th>
                        <th>TIPO</th>
                        <th>CONTACTO</th>
                        <th>TELEFONE</th>
                        <th>WEBSITE</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listaFornecedores)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhum fornecedor encontrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listaFornecedores as $fornecedor):
                            $encryptedId = aes_encrypt($fornecedor->getIdFornecedor());
                            $tipoBadgeClass = '';
                            switch ($fornecedor->getTipoFornecedor()->value) {
                                case 'Fabricante':
                                    $tipoBadgeClass = 'supplier-badge-supplier';
                                    break;
                                case 'Distribuidor':
                                    $tipoBadgeClass = 'supplier-badge-distributor';
                                    break;
                                case 'Assistência Técnica':
                                    $tipoBadgeClass = 'supplier-badge-tech-assistance';
                                    break;
                                case 'Consumíveis':
                                    $tipoBadgeClass = 'supplier-badge-consumable-supplier';
                                    break;
                            }
                            ?>
                            <tr class="searchable-row"
                                data-search="<?= htmlspecialchars(strtolower($fornecedor->getNome() . ' ' . $fornecedor->getNifFornecedor() . ' ' . $fornecedor->getEmail() . ' ' . $fornecedor->getTipoFornecedor()->value)) ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="table-icon-wrapper equipment-icon-wrapper">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                                <path d="M10 12h4" />
                                                <path d="M10 8h4" />
                                                <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                                <path
                                                    d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                                <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                            </svg>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <p class="equipment-title fw-700 mb-0">
                                                <?= htmlspecialchars($fornecedor->getNome()) ?>
                                            </p>
                                            <span
                                                class="equipment-subtitle text-secondary fw-400"><?= htmlspecialchars($fornecedor->getEmail()) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="equipment-badge <?= $tipoBadgeClass ?>">
                                        <?= htmlspecialchars($fornecedor->getTipoFornecedor()->value) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fornecedor->getPessoaResponsavel()): ?>
                                        <?= htmlspecialchars($fornecedor->getPessoaResponsavel()->getNome()) ?>
                                    <?php else: ?>
                                        <span class="fst-italic text-muted">Sem contacto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a
                                        href="tel:<?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?>"><?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?></a>
                                </td>
                                <td>
                                    <?php if (!empty($fornecedor->getWebsite())): ?>
                                        <a href="<?= htmlspecialchars($fornecedor->getWebsite()) ?>" target="_blank"
                                            class="d-flex gap-1 align-items-center text-primary-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="lucide lucide-globe-icon lucide-globe stroke-primary-500">
                                                <circle cx="12" cy="12" r="10" />
                                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20" />
                                                <path d="M2 12h20" />
                                            </svg>
                                            <span>Website</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">&mdash;</span>
                                    <?php endif; ?>
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
                                                    data-bs-target="#supplier-edit-modal-<?= $encryptedId ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-pencil">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                        <path d="m15 5 4 4" />
                                                    </svg>
                                                    Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item action-dropdown-item text-error" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#delete-confirm-modal-<?= $encryptedId ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-archive">
                                                        <rect width="20" height="5" x="2" y="3" rx="1" />
                                                        <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                                                        <path d="M10 12h4" />
                                                    </svg>
                                                    Mover para Reciclagem
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div id="empty-state-card" class="bento-card p-5 text-center d-none">
            <h4 class="text-secondary">Não foram encontrados resultados consoante a pesquisa</h4>
        </div>

    </section>
</div>

<?php include_once BASE_PATH . 'private/includes/sidebar-mobile.php'; ?>

<!-- Modal de Criação de Fornecedor -->
<div class="modal fade" id="supplier-creation-modal" tabindex="-1" aria-labelledby="supplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="supplierModalLabel">Novo Fornecedor</h2>
                    <span class="text-secondary fw-400">Informações do fornecedor</span>
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

            <!-- Body do Modal com scroll automático -->
            <div class="modal-body p-0">
                <form id="supplier-creation-form" action="suppliers-crud/create-supplier.php" method="POST"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                    <!-- Row 1: Nome da Empresa e NIF -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-name">Nome da Empresa</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-name" name="supplier-name"
                                placeholder="Ex: Dräger Portugal, Lda." required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-nif">NIF</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-nif" name="supplier-nif" placeholder="501234567" required
                                pattern="[0-9]{9}" title="O NIF deve conter 9 dígitos">
                        </div>
                    </div>

                    <!-- Row 2: Tipo -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <div class="d-flex gap-1">
                            <label for="supplier-type">Tipo</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <select id="supplier-type" name="supplier-type" class="form-select w-100" required>
                            <option value="Fabricante" selected>Fabricante</option>
                            <option value="Distribuidor">Distribuidor</option>
                            <option value="Assistência Técnica">Assistência Técnica</option>
                            <option value="Consumíveis">Consumíveis</option>
                        </select>
                    </div>

                    <!-- Row 3: Email e Telefone -->
                    <div class="d-flex gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-email">Email</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="email" id="supplier-email" name="supplier-email"
                                placeholder="email@empresa.com" required>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-phone">Telefone de Contacto</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="supplier-phone" name="supplier-phone" placeholder="+351 21X XXX XXX"
                                required>
                        </div>
                    </div>

                    <!-- Row 4: Website -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-website">Website</label>
                        <input type="url" id="supplier-website" name="supplier-website"
                            placeholder="https://www.empresa.pt">
                    </div>

                    <!-- Row 5: Pessoa Responsável -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="supplier-contact-person">Pessoa Responsável</label>
                        <select id="supplier-contact-person" name="supplier-contact-person" class="form-select w-100">
                            <option value="" selected>Sem pessoa associada</option>
                            <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                <option value="<?= htmlspecialchars($pessoa->getId()) ?>">
                                    <?= htmlspecialchars($pessoa->getNome() . ' (' . ($pessoa->getFuncao() ? $pessoa->getFuncao()->value : 'Sem função') . ')') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="criar_fornecedor" id="btn-submit-modal"
                            class="btn btn-primary btn-glowing" disabled>
                            Criar Fornecedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php foreach ($listaFornecedores as $fornecedor):
    $encryptedId = aes_encrypt($fornecedor->getIdFornecedor());
    ?>
    <!-- Modal de Edição para <?= htmlspecialchars($fornecedor->getNome()) ?> -->
    <div class="modal fade" id="supplier-edit-modal-<?= $encryptedId ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title">Editar Fornecedor</h2>
                        <span class="text-secondary fw-400">Atualizar informações do fornecedor</span>
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
                    <form action="suppliers-crud/edit-supplier.php" method="POST"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                        <input type="hidden" name="supplier-id" value="<?= $encryptedId ?>">

                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-name-<?= $encryptedId ?>">Nome da Empresa</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-name-<?= $encryptedId ?>" name="supplier-name"
                                    value="<?= htmlspecialchars($fornecedor->getNome()) ?>" required>
                            </div>
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-nif-<?= $encryptedId ?>">NIF</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-nif-<?= $encryptedId ?>" name="supplier-nif"
                                    value="<?= htmlspecialchars($fornecedor->getNifFornecedor()) ?>" required
                                    pattern="[0-9]{9}">
                            </div>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <div class="d-flex gap-1">
                                <label for="supplier-type-<?= $encryptedId ?>">Tipo</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="supplier-type-<?= $encryptedId ?>" name="supplier-type" class="form-select w-100"
                                required>
                                <option value="Fabricante" <?= $fornecedor->getTipoFornecedor()->value === 'Fabricante' ? 'selected' : '' ?>>Fabricante</option>
                                <option value="Distribuidor" <?= $fornecedor->getTipoFornecedor()->value === 'Distribuidor' ? 'selected' : '' ?>>Distribuidor</option>
                                <option value="Assistência Técnica"
                                    <?= $fornecedor->getTipoFornecedor()->value === 'Assistência Técnica' ? 'selected' : '' ?>>
                                    Assistência Técnica</option>
                                <option value="Consumíveis" <?= $fornecedor->getTipoFornecedor()->value === 'Consumíveis' ? 'selected' : '' ?>>Consumíveis</option>
                            </select>
                        </div>

                        <div class="d-flex gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-email-<?= $encryptedId ?>">Email</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="email" id="supplier-email-<?= $encryptedId ?>" name="supplier-email"
                                    value="<?= htmlspecialchars($fornecedor->getEmail()) ?>" required>
                            </div>
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <div class="d-flex gap-1">
                                    <label for="supplier-phone-<?= $encryptedId ?>">Telefone</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="supplier-phone-<?= $encryptedId ?>" name="supplier-phone"
                                    value="<?= htmlspecialchars($fornecedor->getContactoTelefonico()) ?>" required>
                            </div>
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-website-<?= $encryptedId ?>">Website</label>
                            <input type="url" id="supplier-website-<?= $encryptedId ?>" name="supplier-website"
                                value="<?= htmlspecialchars($fornecedor->getWebsite()) ?>">
                        </div>

                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <label for="supplier-contact-person-<?= $encryptedId ?>">Pessoa Responsável</label>
                            <select id="supplier-contact-person-<?= $encryptedId ?>" name="supplier-contact-person"
                                class="form-select w-100">
                                <option value="" <?= !$fornecedor->getIdPessoaResponsavel() ? 'selected' : '' ?>>Sem pessoa
                                    associada</option>
                                <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                    <option value="<?= htmlspecialchars($pessoa->getId()) ?>"
                                        <?= $fornecedor->getIdPessoaResponsavel() == $pessoa->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pessoa->getNome() . ' (' . ($pessoa->getFuncao() ? $pessoa->getFuncao()->value : 'Sem função') . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex w-100 justify-content-end gap-4 button-row mt-4">
                            <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="editar_fornecedor"
                                class="btn btn-primary btn-glowing">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Eliminação de Fornecedor para <?= htmlspecialchars($fornecedor->getNome()) ?> -->
    <div class="modal fade" id="delete-confirm-modal-<?= $encryptedId ?>" tabindex="-1"
        aria-labelledby="deleteModalLabel-<?= $encryptedId ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Titulo -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title" id="deleteModalLabel-<?= $encryptedId ?>">
                            Eliminar Fornecedor</h2>
                        <span class="text-secondary fw-400">O fornecedor será movido para a reciclagem.</span>
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

                <!-- Body do Modal -->
                <div class="modal-body p-0">
                    <form method="POST" action="suppliers-crud/delete-supplier.php">
                        <input type="hidden" name="supplier-id" value="<?= $encryptedId ?>">
                        <div
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                            <div class="d-flex flex-column align-items-center gap-4">
                                <div class="d-flex padding-3 danger-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                        <path
                                            d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                        <path d="M12 9v4" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                        <p class="text-secondary">
                                            Tem a certeza que deseja eliminar o fornecedor?
                                        </p>
                                        <h2 class="fw-700">
                                            "<?= htmlspecialchars($fornecedor->getNome()) ?>"
                                        </h2>
                                        <span class="text-muted">Tipo: Fornecedor</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botoes -->
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" name="apagar_fornecedor"
                                    class="btn btn-danger btn-glowing text-white">
                                    Sim, Apagar.
                                </button>
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

<?php include_once BASE_PATH . 'private/includes/footer.php'; ?>