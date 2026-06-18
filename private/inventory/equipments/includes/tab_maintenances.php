<?php
$stmtManutencoes = execute_query(
    "SELECT m.*, p.nome AS pessoaNome, f.nome AS fornecedorNome 
     FROM Manutencao m 
     LEFT JOIN Pessoa p ON m.idPessoaResponsavel = p.idPessoa 
     LEFT JOIN Fornecedor f ON m.idFornecedor = f.idFornecedor 
     WHERE m.idEquipamento = :id AND m.ativo = 1 
     ORDER BY m.dataInicio DESC",
    ['id' => $id],
    $ligacao
);

$listaManutencoes = [];
while ($rowM = $stmtManutencoes->fetch(PDO::FETCH_ASSOC)) {
    $listaManutencoes[] = new Manutencao(
        (string) $rowM['idManutencao'],
        (string) $rowM['idEquipamento'],
        TipoManutencao::from($rowM['tipoManutencao']),
        new DateTime($rowM['dataInicio']),
        $rowM['dataFim'] ? new DateTime($rowM['dataFim']) : null,
        (string) $rowM['idPessoaResponsavel'],
        $rowM['idFornecedor'] ? (string) $rowM['idFornecedor'] : null,
        $rowM['custoManutencao'] !== null ? (float) $rowM['custoManutencao'] : null,
        $rowM['observacoes'],
        (bool) $rowM['ativo'],
        new DateTime($rowM['dataCriacao']),
        new DateTime($rowM['dataAtualizacao']),
        $rowM['pessoaNome'],
        $rowM['fornecedorNome']
    );
}
?>
<div class="tab-pane fade <?= $activeTab === 'manutencoes' ? 'show active' : '' ?>" id="nav-manutencoes" role="tabpanel"
    aria-labelledby="nav-manutencoes-tab">
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Manutenções</h2>
            <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#add-maintenance-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                <span>Nova Manutenção</span>
            </button>
        </div>

        <?php if (empty($listaManutencoes)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 py-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-wrench text-muted opacity-50">
                    <path
                        d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                </svg>
                <span class="text-secondary fw-500">Sem registos de manutenção</span>
            </div>
        <?php else: ?>
            <table id="maintenancesTable" class="sibdas-table w-100 display border-0">
                <thead>
                    <tr>
                        <th>TIPO</th>
                        <th>DATA INÍCIO</th>
                        <th>DATA FIM</th>
                        <th>RESPONSÁVEL</th>
                        <th>FORNECEDOR</th>
                        <th>CUSTO</th>
                        <th>OBSERVAÇÕES</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaManutencoes as $item): ?>
                        <?php $encId = aes_encrypt($item->getIdManutencao()); ?>
                        <tr>
                            <td>
                                <span
                                    class="text-secondary fw-500"><?= htmlspecialchars($item->getTipoManutencao()->value) ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-400"><?= htmlspecialchars($item->getDataInicio()->format('d/m/Y')) ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-400"><?= $item->getDataFim() ? htmlspecialchars($item->getDataFim()->format('d/m/Y')) : '—' ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-primary-500 fw-700"><?= htmlspecialchars($item->getPessoaNome() ?? 'Desconhecido') ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-400"><?= htmlspecialchars($item->getFornecedorNome() ?? '—') ?></span>
                            </td>
                            <td>
                                <span
                                    class="text-secondary fw-400"><?= $item->getCustoManutencao() !== null ? htmlspecialchars(number_format($item->getCustoManutencao(), 2, ',', ' ')) . ' €' : '—' ?></span>
                            </td>
                            <td class="max-width-200">
                                <?php
                                $obs = $item->getObservacoes() ?? '';
                                $shortObs = mb_strlen($obs) > 50 ? mb_substr($obs, 0, 50) . '...' : $obs;
                                ?>
                                <span class="text-secondary fw-400 d-inline-block w-100" title="<?= htmlspecialchars($obs) ?>">
                                    <?= htmlspecialchars($shortObs ?: '—') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Editar" data-bs-toggle="modal"
                                        data-bs-target="#edit-maintenance-modal-<?= $encId ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-pencil">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                            <path d="m15 5 4 4" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Eliminar" data-bs-toggle="modal"
                                        data-bs-target="#delete-maintenance-modal-<?= $encId ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-trash-2 text-secondary">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Nova Manutenção -->
<div class="modal fade" id="add-maintenance-modal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                    id="addMaintenanceModalLabel">
                    Nova Manutenção</h2>
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
                <form id="add-maintenance-form" action="equipments-crud/create-maintenance.php" method="POST"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">

                    <!-- Tipo de Manutenção -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="maintenance-type">Tipo de Manutenção</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <select id="maintenance-type" name="maintenance-type" class="form-select w-100" required>
                            <option value="" disabled selected>Selecionar...</option>
                            <?php foreach (TipoManutencao::cases() as $t): ?>
                                <option value="<?= htmlspecialchars($t->value) ?>"><?= htmlspecialchars($t->value) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Row: Datas -->
                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <!-- Data Inicio -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <div class="d-flex gap-1">
                                <label for="maintenance-start-date">Data Início</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <div class="position-relative w-100 date-input">
                                <input type="text" id="maintenance-start-date" name="maintenance-start-date"
                                    class="w-100" placeholder="dd/mm/yyyy" required>
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

                        <!-- Data Fim -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="maintenance-end-date">Data Fim</label>
                            <div class="position-relative w-100 date-input">
                                <input type="text" id="maintenance-end-date" name="maintenance-end-date" class="w-100"
                                    placeholder="dd/mm/yyyy">
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

                    <!-- Pessoa Responsavel -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="maintenance-responsible">Pessoa Responsável</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <select id="maintenance-responsible" name="maintenance-responsible" class="form-select w-100"
                            required>
                            <option value="" disabled selected>Selecionar...</option>
                            <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                <option value="<?= htmlspecialchars($pessoa['idPessoa']) ?>">
                                    <?= htmlspecialchars($pessoa['nome'] . ' — ' . $pessoa['funcao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fornecedor & Custo -->
                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="maintenance-supplier">Fornecedor Associado</label>
                            <select id="maintenance-supplier" name="maintenance-supplier" class="form-select w-100">
                                <option value="" selected>Nenhum</option>
                                <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                    <option value="<?= htmlspecialchars($f['idFornecedor']) ?>">
                                        <?= htmlspecialchars($f['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="maintenance-cost">Custo da Manutenção</label>
                            <input type="number" step="0.01" min="0" id="maintenance-cost" name="maintenance-cost"
                                class="w-100" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="maintenance-obs">Observações</label>
                        <textarea id="maintenance-obs" name="maintenance-notes" class="w-100 no-resize" rows="3"
                            placeholder="Detalhes da manutenção..."></textarea>
                    </div>

                    <?php if (SHOW_DEBUG_BUTTONS): ?>
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light">
                            <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                (Debug)</span>
                            <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                onclick="prefillMaintenance('Preventiva', '10/05/2026', '12/05/2026', '1', '1', '150.00', 'Manutenção preventiva anual.')">
                                Preventiva Completa
                            </button>
                            <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                onclick="prefillMaintenance('Corretiva', '15/05/2026', '', '1', '', '', 'Aguardar peça de substituição.')">
                                Corretiva em Curso
                            </button>
                            <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                onclick="prefillMaintenance('Calibração', '20/05/2026', '20/05/2026', '1', '2', '80.00', 'Calibração efetuada com sucesso.')">
                                Calibração
                            </button>
                        </div>
                        <script>
                            function prefillMaintenance(type, start, end, responsible, supplier, cost, notes) {
                                document.getElementById('maintenance-type').value = type;
                                document.getElementById('maintenance-start-date').value = start;
                                document.getElementById('maintenance-end-date').value = end;
                                document.getElementById('maintenance-responsible').value = responsible;
                                document.getElementById('maintenance-supplier').value = supplier;
                                document.getElementById('maintenance-cost').value = cost;
                                document.getElementById('maintenance-obs').value = notes;

                                // Dispatch events
                                ['maintenance-type', 'maintenance-start-date', 'maintenance-end-date', 'maintenance-responsible'].forEach(id => {
                                    const el = document.getElementById(id);
                                    if (el) {
                                        el.dispatchEvent(new Event('change', { bubbles: true }));
                                        el.dispatchEvent(new Event('input', { bubbles: true }));
                                    }
                                });
                            }
                        </script>
                    <?php endif; ?>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-submit-maintenance"
                            class="btn btn-primary btn-glowing d-flex align-items-center gap-2" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-check">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Registar Manutenção
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php foreach ($listaManutencoes as $item): ?>
    <?php
    $encId = aes_encrypt($item->getIdManutencao());
    ?>
    <!-- Modal de Editar Manutenção -->
    <div class="modal fade" id="edit-maintenance-modal-<?= $encId ?>" tabindex="-1"
        aria-labelledby="editMaintenanceModalLabel-<?= $encId ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Header -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="editMaintenanceModalLabel-<?= $encId ?>">
                        Editar Manutenção</h2>
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
                    <form action="equipments-crud/edit-maintenance.php" method="POST"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column edit-maintenance-form">
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                        <input type="hidden" name="maintenance-id" value="<?= htmlspecialchars($encId) ?>">

                        <!-- Tipo de Manutenção -->
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="edit-maintenance-type-<?= $encId ?>">Tipo de Manutenção</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="edit-maintenance-type-<?= $encId ?>" name="maintenance-type"
                                class="form-select w-100 edit-maintenance-type" required>
                                <option value="" disabled>Selecionar...</option>
                                <?php foreach (TipoManutencao::cases() as $t): ?>
                                    <option value="<?= htmlspecialchars($t->value) ?>"
                                        <?= $item->getTipoManutencao()->value === $t->value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t->value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Row: Datas -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <!-- Data Inicio -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <div class="d-flex gap-1">
                                    <label for="edit-maintenance-start-date-<?= $encId ?>">Data Início</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <div class="position-relative w-100 date-input">
                                    <input type="text" id="edit-maintenance-start-date-<?= $encId ?>"
                                        name="maintenance-start-date" class="w-100 edit-maintenance-start-date"
                                        placeholder="dd/mm/yyyy"
                                        value="<?= htmlspecialchars($item->getDataInicio()->format('d/m/Y')) ?>" required>
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

                            <!-- Data Fim -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <label for="edit-maintenance-end-date-<?= $encId ?>">Data Fim</label>
                                <div class="position-relative w-100 date-input">
                                    <input type="text" id="edit-maintenance-end-date-<?= $encId ?>"
                                        name="maintenance-end-date" class="w-100 edit-maintenance-end-date"
                                        placeholder="dd/mm/yyyy"
                                        value="<?= $item->getDataFim() ? htmlspecialchars($item->getDataFim()->format('d/m/Y')) : '' ?>">
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

                        <!-- Pessoa Responsavel -->
                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="edit-maintenance-responsible-<?= $encId ?>">Pessoa Responsável</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="edit-maintenance-responsible-<?= $encId ?>" name="maintenance-responsible"
                                class="form-select w-100 edit-maintenance-responsible" required>
                                <option value="" disabled>Selecionar...</option>
                                <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                    <option value="<?= htmlspecialchars($pessoa['idPessoa']) ?>"
                                        <?= $item->getIdPessoaResponsavel() === (string) $pessoa['idPessoa'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pessoa['nome'] . ' — ' . $pessoa['funcao']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fornecedor & Custo -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <label for="edit-maintenance-supplier-<?= $encId ?>">Fornecedor Associado</label>
                                <select id="edit-maintenance-supplier-<?= $encId ?>" name="maintenance-supplier"
                                    class="form-select w-100">
                                    <option value="">Nenhum</option>
                                    <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                        <option value="<?= htmlspecialchars($f['idFornecedor']) ?>"
                                            <?= $item->getIdFornecedor() === (string) $f['idFornecedor'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($f['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <label for="edit-maintenance-cost-<?= $encId ?>">Custo da Manutenção</label>
                                <input type="number" step="0.01" min="0" id="edit-maintenance-cost-<?= $encId ?>"
                                    name="maintenance-cost" class="w-100" placeholder="0.00"
                                    value="<?= $item->getCustoManutencao() !== null ? htmlspecialchars($item->getCustoManutencao()) : '' ?>">
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="d-flex flex-column form-item w-100">
                            <label for="edit-maintenance-obs-<?= $encId ?>">Observações</label>
                            <textarea id="edit-maintenance-obs-<?= $encId ?>" name="maintenance-notes"
                                class="w-100 no-resize" rows="3"
                                placeholder="Detalhes da manutenção..."><?= htmlspecialchars($item->getObservacoes() ?? '') ?></textarea>
                        </div>

                        <!-- Footer -->
                        <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit"
                                class="btn btn-primary btn-glowing d-flex align-items-center gap-2 btn-submit-edit-maintenance">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-check">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Guardar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Remoção de Manutenção -->
    <div class="modal fade" id="delete-maintenance-modal-<?= htmlspecialchars($encId) ?>" tabindex="-1"
        aria-labelledby="deleteMaintenanceModalLabel-<?= htmlspecialchars($encId) ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                            id="deleteMaintenanceModalLabel-<?= htmlspecialchars($encId) ?>">Eliminar Registo</h2>
                        <span class="text-secondary fw-400">Esta ação moverá o registo para o arquivo.</span>
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
                    <form method="POST" action="equipments-crud/delete-maintenance.php">
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                        <input type="hidden" name="maintenance-id" value="<?= htmlspecialchars($encId) ?>">
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
                                        <p class="text-secondary m-0">Tem a certeza que deseja remover o registo de
                                            manutenção de</p>
                                        <h2 class="fw-700 text-primary m-0">
                                            "<?= htmlspecialchars($item->getTipoManutencao()->value) ?>"</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
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
<?php endforeach; ?>