<?php
// Inicializar variáveis
$listaGarantias = [];

try {
    // Ligar à BD
    if (!isset($ligacao)) {
        $ligacao = connect_to_db();
    }

    // Consultar garantias
    $stmtGarantias = execute_query(
        "SELECT gc.*, f.nome AS fornecedorNome, d.caminhoFicheiro AS documentoCaminho
         FROM GarantiaContrato gc
         LEFT JOIN Fornecedor f ON gc.idFornecedor = f.idFornecedor
         LEFT JOIN Documento d ON gc.idDocumento = d.idDocumento
         WHERE gc.idEquipamento = :id AND gc.ativo = 1
         ORDER BY gc.dataInicio DESC",
        ['id' => $id],
        $ligacao
    );

    // Mapear resultados
    while ($row = $stmtGarantias->fetch(PDO::FETCH_ASSOC)) {
        $listaGarantias[] = new GarantiaContrato(
            (string) $row['idGarantiaContrato'],
            $row['idEquipamento'] ? (string) $row['idEquipamento'] : null,
            $row['idFornecedor'] ? (string) $row['idFornecedor'] : null,
            $row['idDocumento'] ? (string) $row['idDocumento'] : null,
            TipoRegisto::tryFrom($row['tipoRegisto']) ?? TipoRegisto::GARANTIA_FABRICA,
            $row['dataInicio'] ? new DateTime($row['dataInicio']) : null,
            $row['dataFim'] ? new DateTime($row['dataFim']) : null,
            Periodicidade::tryFrom($row['periodicidade']) ?? Periodicidade::NA,
            $row['observacoes'],
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao']),
            $row['fornecedorNome'],
            $row['documentoCaminho']
        );
    }
} catch (Exception $e) {
    // Capturar erro
    // Em caso de erro, a lista fica vazia e mostra o empty state
}
?>

<!-- Wrapper Aba -->
<div class="tab-pane fade <?= $activeTab === 'garantias' ? 'show active' : '' ?>" id="nav-garantias" role="tabpanel"
    aria-labelledby="nav-garantias-tab">
    <!-- Card Principal -->
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- Título -->
            <h2 class="fw-700 m-0 text-primary">Garantias & Contratos</h2>
            <?php if (tem_permissao('warranties.create')): ?>
                <!-- Botão Adicionar -->
                <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#add-warranty-modal">
                    <!-- SVG Mais -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <!-- Texto Adicionar -->
                    <span>Adicionar</span>
                </button>
            <?php endif; ?>
        </div>

        <?php if (empty($listaGarantias)): ?>
            <!-- Estado Vazio -->
            <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Escudo -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shield-off">
                        <path d="m2 2 20 20" />
                        <path d="M5 5a1 1 0 0 0-1 1v7c0 5 3.5 7.5 7.67 8.94a1 1 0 0 0 .67.01c2.35-.82 4.48-1.97 5.9-3.71" />
                        <path
                            d="M9.309 3.652A12.252 12.252 0 0 0 11.24 2.28a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1v7a9.784 9.784 0 0 1-.08 1.264" />
                    </svg>
                </div>
                <!-- Wrapper Textos -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Garantias ou Contratos</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">Ainda não existem garantias ou contratos associados a este equipamento.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabela -->
            <div class="w-100 overflow-auto">
                <table id="warrantiesTable" class="heba-table w-100 display border-0">
                <!-- Cabeçalho -->
                <thead>
                    <!-- Linha -->
                    <tr>
                        <!-- Coluna Data -->
                        <th>DATA</th>
                        <!-- Coluna Data Fim -->
                        <th>DATA FIM</th>
                        <!-- Coluna Tipo -->
                        <th>TIPO</th>
                        <!-- Coluna Observações -->
                        <th>OBSERVAÇÕES</th>
                        <!-- Coluna Estado -->
                        <th>ESTADO</th>
                        <!-- Coluna Fornecedor -->
                        <th>FORNECEDOR</th>
                        <!-- Coluna Ações -->
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <!-- Corpo -->
                <tbody>
                    <?php foreach ($listaGarantias as $item): ?>
                        <?php
                        // Determinar estado e badge
                        $estado = $item->getEstado();
                        $badgeClass = match ($estado) {
                            'Ativo' => 'equipment-badge d-inline-flex align-items-center justify-content-center fw-500  new',
                            'Expirado' => 'equipment-badge-status-abated',
                            default => 'bg-secondary',
                        };
                        // Encriptar ID
                        $encId = aes_encrypt($item->getIdGarantiaContrato());
                        ?>
                        <!-- Linha -->
                        <tr>
                            <!-- Célula Data -->
                            <td>
                                <!-- Texto Data -->
                                <span
                                    class="text-secondary fw-400"><?= $item->getDataInicio() ? $item->getDataInicio()->format('d/m/Y') : 'N/A' ?></span>
                            </td>
                            <!-- Célula Data Fim -->
                            <td>
                                <!-- Texto Data Fim -->
                                <span
                                    class="text-secondary fw-400"><?= $item->getDataFim() ? $item->getDataFim()->format('d/m/Y') : 'N/A' ?></span>
                            </td>
                            <!-- Célula Tipo -->
                            <td>
                                <!-- Texto Tipo -->
                                <span
                                    class="fw-700 text-secondary"><?= htmlspecialchars($item->getTipoRegisto()->value) ?></span>
                            </td>
                            <!-- Célula Observações -->
                            <td>
                                <!-- Texto Observações -->
                                <span class="fw-700"><?= htmlspecialchars($item->getObservacoes() ?? 'N/A') ?></span>
                            </td>
                            <!-- Célula Estado -->
                            <td>
                                <!-- Badge Estado -->
                                <span
                                    class="equipment-badge d-inline-flex align-items-center justify-content-center fw-500  <?= $badgeClass ?>"><?= $estado ?></span>
                            </td>
                            <!-- Célula Fornecedor -->
                            <td>
                                <!-- Texto Fornecedor -->
                                <span
                                    class="text-secondary fw-400"><?= htmlspecialchars($item->getFornecedorNome() ?? 'Sem Fornecedor') ?></span>
                            </td>
                            <!-- Célula Ações -->
                            <td class="text-end">
                                <!-- Wrapper Ações -->
                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    <?php if ($item->getDocumentoCaminho()): ?>
                                        <!-- Link Download -->
                                        <a href="<?= BASE_URL . htmlspecialchars($item->getDocumentoCaminho()) ?>" download
                                            class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                            title="Download Ficheiro">
                                            <!-- SVG Download -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-download">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7 10 12 15 17 10" />
                                                <line x1="12" x2="12" y1="15" y2="3" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (tem_permissao('warranties.edit')): ?>
                                        <!-- Botão Editar -->
                                        <button
                                            class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                            type="button" title="Editar" data-bs-toggle="modal"
                                            data-bs-target="#edit-warranty-modal-<?= htmlspecialchars($encId) ?>"
                                            data-id="<?= htmlspecialchars($encId) ?>">
                                            <!-- SVG Lápis -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                <path d="m15 5 4 4" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                    <?php if (tem_permissao('warranties.delete')): ?>
                                        <!-- Botão Eliminar -->
                                        <button
                                            class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                            type="button" title="Eliminar" data-bs-toggle="modal"
                                            data-bs-target="#delete-warranty-modal-<?= htmlspecialchars($encId) ?>"
                                            data-id="<?= htmlspecialchars($encId) ?>">
                                            <!-- SVG Lixo -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-trash-2 text-secondary">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Adicionar Garantia -->
<?php if (tem_permissao('warranties.create')): ?>
    <div class="modal fade" id="add-warranty-modal" tabindex="-1" aria-labelledby="addWarrantyModalLabel"
        aria-hidden="true">
        <!-- Dialog -->
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <!-- Conteúdo -->
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Cabeçalho -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <!-- Título -->
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addWarrantyModalLabel">
                        Nova Garantia / Contrato</h2>
                    <!-- Botão Fechar -->
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <!-- SVG X -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x stroke-secondary">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>

                <!-- Corpo -->
                <div class="modal-body p-0">
                    <!-- Formulário -->
                    <form id="add-warranty-form" action="equipments-crud/create-warranty.php" method="POST"
                        enctype="multipart/form-data"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                        <!-- Input ID Equipamento -->
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">

                        <!-- Linha Tipo & Periodicidade -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <!-- Coluna Tipo -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label Tipo Registo -->
                                <label for="warranty-type" class="d-flex align-items-center gap-1">
                                    Tipo de Registo
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                                <!-- Select Tipo Registo -->
                                <select id="warranty-type" name="warranty-type" class="form-select w-100" required>
                                    <option value="" disabled selected>Selecionar tipo...</option>
                                    <?php foreach (TipoRegisto::cases() as $t): ?>
                                        <option value="<?= htmlspecialchars($t->value) ?>"><?= htmlspecialchars($t->value) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Coluna Periodicidade -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label Periodicidade -->
                                <label for="warranty-periodicity" class="d-flex align-items-center gap-1">
                                    Periodicidade
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                                <!-- Select Periodicidade -->
                                <select id="warranty-periodicity" name="warranty-periodicity" class="form-select w-100"
                                    required>
                                    <option value="" disabled selected>Selecionar periodicidade...</option>
                                    <?php foreach (Periodicidade::cases() as $p): ?>
                                        <option value="<?= htmlspecialchars($p->value) ?>"><?= htmlspecialchars($p->value) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Linha Datas -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <!-- Coluna Data Início -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label Data Início -->
                                <label for="warranty-start-date" class="d-flex align-items-center gap-1">
                                    Data de Início
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                                <!-- Wrapper Data Início -->
                                <div class="position-relative w-100 date-input">
                                    <!-- Input Data Início -->
                                    <input type="text" id="warranty-start-date" name="warranty-start-date" class="w-100"
                                        placeholder="dd/mm/yyyy" required>
                                    <!-- SVG Calendário -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-calendar text-secondary position-absolute">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                </div>
                            </div>
                            <!-- Coluna Data Fim -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label Data Fim -->
                                <label for="warranty-end-date" class="d-flex align-items-center gap-1">
                                    Data de Fim
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </label>
                                <!-- Wrapper Data Fim -->
                                <div class="position-relative w-100 date-input">
                                    <!-- Input Data Fim -->
                                    <input type="text" id="warranty-end-date" name="warranty-end-date" class="w-100"
                                        placeholder="dd/mm/yyyy" required>
                                    <!-- SVG Calendário -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="lucide lucide-calendar text-secondary position-absolute">
                                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Linha Fornecedor -->
                        <div class="d-flex flex-column form-item w-100">
                            <!-- Label Fornecedor -->
                            <label for="warranty-supplier">Fornecedor Associado</label>
                            <!-- Select Fornecedor -->
                            <select id="warranty-supplier" name="warranty-supplier" class="form-select w-100">
                                <option value="" selected>Nenhum fornecedor</option>
                                <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                    <option value="<?= htmlspecialchars($f['idFornecedor']) ?>">
                                        <?= htmlspecialchars($f['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Linha Documento -->
                        <div class="d-flex flex-column form-item w-100 mt-2">
                            <!-- Label Documento -->
                            <label>Documento Adicional</label>
                            <!-- Dropzone -->
                            <div class="file-upload-zone w-100 cursor-pointer bg-transparent  d-flex flex-column align-items-center justify-content-center gap-2"
                                id="add-warranty-dropzone" data-dropzone-target="warranty-file"
                                data-text-target="add-warranty-dropzone-text">
                                <!-- SVG Upload -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-upload file-upload-icon">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" x2="12" y1="3" y2="15" />
                                </svg>
                                <!-- Texto Upload -->
                                <p class="file-upload-text fw-500 text-secondary m-0" id="add-warranty-dropzone-text">
                                    Arraste um ficheiro ou
                                    <!-- Link Selecionar -->
                                    <span class="file-upload-text-action text-primary-500 text-primary-500">clique para
                                        selecionar</span>
                                </p>
                                <!-- Texto Info -->
                                <span class="m-0 text-muted">PDF, JPG, PNG — máx. 25MB</span>
                            </div>
                            <!-- Input File -->
                            <input type="file" id="warranty-file" name="warranty-file" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <!-- Linha Observações -->
                        <div class="d-flex flex-column form-item w-100">
                            <!-- Label Observações -->
                            <label for="warranty-notes">Observações</label>
                            <!-- Textarea Observações -->
                            <textarea id="warranty-notes" name="warranty-notes" class="w-100 no-resize" rows="3"
                                placeholder="Observações da garantia ou contrato..."></textarea>
                        </div>

                        <!-- Rodapé Formulário -->
                        <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                            <!-- Botão Cancelar -->
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <!-- Botão Guardar -->
                            <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2"
                                id="btn-submit-warranty" disabled>
                                Guardar Registo
                            </button>
                        </div>
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <!-- Zona Debug -->
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <!-- Texto Debug -->
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                    (Debug)</span>
                                <!-- Botão Debug -->
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillFields({'warranty-start-date': '01/01/2026', 'warranty-end-date': '01/01/2028', 'warranty-notes': 'Garantia Preenchida'}); setTimeout(() => { document.getElementById('warranty-type').selectedIndex = 1; document.getElementById('warranty-periodicity').selectedIndex = 1; document.querySelectorAll('#add-warranty-form input, #add-warranty-form select, #add-warranty-form textarea').forEach(el => { el.dispatchEvent(new Event('change', { bubbles: true })); el.dispatchEvent(new Event('input', { bubbles: true })); }); }, 100);">Preencher
                                    Campos</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($listaGarantias as $item): ?>
    <?php
    $encId = aes_encrypt($item->getIdGarantiaContrato());
    $tipoId = 'edit-warranty-type-' . $encId;
    $periodId = 'edit-warranty-periodicity-' . $encId;
    $startId = 'edit-warranty-start-date-' . $encId;
    $endId = 'edit-warranty-end-date-' . $encId;
    ?>

    <?php if (tem_permissao('warranties.edit')): ?>
        <!-- Modal de Editar Garantia / Contrato -->
        <div class="modal fade" id="edit-warranty-modal-<?= htmlspecialchars($encId) ?>" tabindex="-1"
            aria-labelledby="editWarrantyModalLabel-<?= htmlspecialchars($encId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Header -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                            id="editWarrantyModalLabel-<?= htmlspecialchars($encId) ?>">
                            Editar Garantia / Contrato</h2>
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x stroke-secondary">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-0">
                        <form id="edit-warranty-form-<?= htmlspecialchars($encId) ?>" action="equipments-crud/edit-warranty.php"
                            method="POST" enctype="multipart/form-data"
                            class="edit-warranty-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <input type="hidden" name="warranty-id" value="<?= htmlspecialchars($encId) ?>">

                            <!-- Row 1: Tipo de Registo & Periodicidade -->
                            <div class="d-flex flex-column flex-md-row gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="<?= $tipoId ?>" class="d-flex align-items-center gap-1">
                                        Tipo de Registo
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                    <select id="<?= $tipoId ?>" name="warranty-type"
                                        class="form-select w-100 edit-warranty-type" required>
                                        <option value="" disabled>Selecionar tipo...</option>
                                        <?php foreach (TipoRegisto::cases() as $t): ?>
                                            <option value="<?= htmlspecialchars($t->value) ?>"
                                                <?= ($item->getTipoRegisto()->value === $t->value) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($t->value) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="<?= $periodId ?>" class="d-flex align-items-center gap-1">
                                        Periodicidade
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                    <select id="<?= $periodId ?>" name="warranty-periodicity"
                                        class="form-select w-100 edit-warranty-periodicity" required>
                                        <option value="" disabled>Selecionar periodicidade...</option>
                                        <?php foreach (Periodicidade::cases() as $p): ?>
                                            <option value="<?= htmlspecialchars($p->value) ?>"
                                                <?= ($item->getPeriodicidade()->value === $p->value) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p->value) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Row 2: Data Início & Data Fim -->
                            <div class="d-flex flex-column flex-md-row gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="<?= $startId ?>" class="d-flex align-items-center gap-1">
                                        Data de Início
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                    <div class="position-relative w-100 date-input">
                                        <input type="text" id="<?= $startId ?>" name="warranty-start-date"
                                            class="w-100 edit-warranty-start-date" placeholder="dd/mm/yyyy"
                                            value="<?= $item->getDataInicio() ? $item->getDataInicio()->format('d/m/Y') : '' ?>"
                                            required>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-calendar text-secondary position-absolute">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                            <line x1="16" x2="16" y1="2" y2="6" />
                                            <line x1="8" x2="8" y1="2" y2="6" />
                                            <line x1="3" x2="21" y1="10" y2="10" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <label for="<?= $endId ?>" class="d-flex align-items-center gap-1">
                                        Data de Fim
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-asterisk-icon lucide-asterisk text-error">
                                            <path d="M12 6v12" />
                                            <path d="M17.196 9 6.804 15" />
                                            <path d="m6.804 9 10.392 6" />
                                        </svg>
                                    </label>
                                    <div class="position-relative w-100 date-input">
                                        <input type="text" id="<?= $endId ?>" name="warranty-end-date"
                                            class="w-100 edit-warranty-end-date" placeholder="dd/mm/yyyy"
                                            value="<?= $item->getDataFim() ? $item->getDataFim()->format('d/m/Y') : '' ?>"
                                            required>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="lucide lucide-calendar text-secondary position-absolute">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                            <line x1="16" x2="16" y1="2" y2="6" />
                                            <line x1="8" x2="8" y1="2" y2="6" />
                                            <line x1="3" x2="21" y1="10" y2="10" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: Fornecedor Associado -->
                            <div class="d-flex flex-column form-item w-100">
                                <label for="edit-warranty-supplier-<?= htmlspecialchars($encId) ?>">Fornecedor Associado</label>
                                <select id="edit-warranty-supplier-<?= htmlspecialchars($encId) ?>" name="warranty-supplier"
                                    class="form-select w-100">
                                    <option value="" <?= ($item->getIdFornecedor() === null) ? 'selected' : '' ?>>Nenhum fornecedor
                                    </option>
                                    <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                        <option value="<?= htmlspecialchars($f['idFornecedor']) ?>"
                                            <?= ($item->getIdFornecedor() === (string) $f['idFornecedor']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($f['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Row 4: Upload Ficheiro -->
                            <div class="d-flex flex-column form-item w-100 mt-2">
                                <label>Documento Adicional (Atualizar Ficheiro)</label>
                                <?php if ($item->getDocumentoCaminho()): ?>
                                    <p class="text-secondary text-sm mb-2">Já existe um ficheiro associado. Se fizer upload de um
                                        novo, o antigo será apagado.</p>
                                <?php endif; ?>
                                <div class="file-upload-zone w-100 cursor-pointer bg-transparent  d-flex flex-column align-items-center justify-content-center gap-2"
                                    id="edit-warranty-dropzone-<?= htmlspecialchars($encId) ?>"
                                    data-dropzone-target="edit-warranty-file-<?= htmlspecialchars($encId) ?>"
                                    data-text-target="edit-warranty-dropzone-text-<?= htmlspecialchars($encId) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-upload file-upload-icon">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" x2="12" y1="3" y2="15" />
                                    </svg>
                                    <p class="file-upload-text fw-500 text-secondary m-0"
                                        id="edit-warranty-dropzone-text-<?= htmlspecialchars($encId) ?>">Arraste um ficheiro ou
                                        <span class="file-upload-text-action text-primary-500 text-primary-500">clique para
                                            selecionar</span>
                                    </p>
                                    <span class="m-0 text-muted">PDF, JPG, PNG — máx. 25MB</span>
                                </div>
                                <input type="file" id="edit-warranty-file-<?= htmlspecialchars($encId) ?>"
                                    name="edit-warranty-file" class="d-none" accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            <!-- Row 5: Observações -->
                            <div class="d-flex flex-column form-item w-100">
                                <label for="edit-warranty-notes-<?= htmlspecialchars($encId) ?>">Observações</label>
                                <textarea id="edit-warranty-notes-<?= htmlspecialchars($encId) ?>" name="warranty-notes"
                                    class="w-100 no-resize" rows="3"
                                    placeholder="Observações da garantia ou contrato..."><?= htmlspecialchars($item->getObservacoes() ?? '') ?></textarea>
                            </div>

                            <!-- Footer -->
                            <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit"
                                    class="btn btn-primary btn-glowing d-flex align-items-center gap-2 btn-submit-edit-warranty"
                                    disabled>
                                    Guardar Alterações
                                </button>
                            </div>
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                        (Debug)</span>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'edit-warranty-notes-<?= htmlspecialchars($encId) ?>': 'Garantia Editada'}); setTimeout(() => { document.getElementById('edit-warranty-notes-<?= htmlspecialchars($encId) ?>').dispatchEvent(new Event('input', { bubbles: true })); document.querySelector('#edit-warranty-form-<?= htmlspecialchars($encId) ?> .edit-warranty-start-date').dispatchEvent(new Event('change', { bubbles: true })); }, 100);">Editar
                                        Notas</button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('warranties.delete')): ?>
        <!-- Modal de Remoção de Garantia -->
        <div class="modal fade" id="delete-warranty-modal-<?= htmlspecialchars($encId) ?>" tabindex="-1"
            aria-labelledby="deleteWarrantyModalLabel-<?= htmlspecialchars($encId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                                id="deleteWarrantyModalLabel-<?= htmlspecialchars($encId) ?>">Eliminar Registo</h2>
                            <span class="text-secondary fw-400">A garantia será movida para a reciclagem.</span>
                        </div>
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x stroke-secondary">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <form method="POST" action="equipments-crud/delete-warranty.php">
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <input type="hidden" name="warranty-id" value="<?= htmlspecialchars($encId) ?>">
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                <div class="d-flex flex-column align-items-center gap-4">
                                    <div class="d-flex padding-3 danger-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-alert-triangle text-error">
                                            <path
                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                            <line x1="12" y1="9" x2="12" y2="13" />
                                            <line x1="12" y1="17" x2="12.01" y2="17" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <p class="text-secondary m-0">Tem a certeza que deseja remover o registo de</p>
                                            <h2 class="fw-700 text-primary m-0">
                                                "<?= htmlspecialchars($item->getTipoRegisto()->value) ?>"</h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger btn-glowing text-white">Sim, Eliminar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>