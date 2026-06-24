<?php
// Consultar manutenções do equipamento
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

// Construir lista de objetos
$listaManutencoes = [];
while ($rowM = $stmtManutencoes->fetch(PDO::FETCH_ASSOC)) {
    $listaManutencoes[] = new Manutencao(
        (string) $rowM['idManutencao'],
        (string) $rowM['idEquipamento'],
        TipoManutencao::tryFrom((string)$rowM['tipoManutencao']) ?? TipoManutencao::Preventiva,
        $rowM['dataInicio'] ? new DateTime($rowM['dataInicio']) : new DateTime(),
        $rowM['dataFim'] ? new DateTime($rowM['dataFim']) : null,
        (string) $rowM['idPessoaResponsavel'],
        $rowM['idFornecedor'] ? (string) $rowM['idFornecedor'] : null,
        $rowM['custoManutencao'] !== null ? (float) $rowM['custoManutencao'] : null,
        $rowM['observacoes'],
        (bool) $rowM['ativo'],
        $rowM['dataCriacao'] ? new DateTime($rowM['dataCriacao']) : new DateTime(),
        $rowM['dataAtualizacao'] ? new DateTime($rowM['dataAtualizacao']) : new DateTime(),
        $rowM['pessoaNome'],
        $rowM['fornecedorNome']
    );
}
?>
<!-- Tab Manutenções -->
<div class="tab-pane fade <?= $activeTab === 'manutencoes' ? 'show active' : '' ?>" id="nav-manutencoes" role="tabpanel"
    aria-labelledby="nav-manutencoes-tab">
    <!-- Card Principal -->
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <!-- Cabeçalho -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- Título -->
            <h2 class="fw-700 m-0 text-primary">Manutenções</h2>
            <?php if (tem_permissao('maintenances.create')): ?>
                <!-- Botão Nova Manutenção -->
                <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#add-maintenance-modal">
                    <!-- SVG Plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <!-- Texto Botão -->
                    <span>Nova Manutenção</span>
                </button>
            <?php endif; ?>
        </div>

        <?php if (empty($listaManutencoes)): ?>
            <!-- Estado Vazio -->
            <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Chave -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-wrench">
                        <path
                            d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                    </svg>
                </div>
                <!-- Wrapper Textos -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Manutenções</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">Ainda não existem manutenções associadas a este equipamento.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabela Manutenções -->
            <div class="w-100 overflow-auto">
                <table id="maintenancesTable" class="heba-table w-100 display border-0">
                    <!-- Cabeçalho Tabela -->
                    <thead>
                        <tr>
                            <!-- Coluna Tipo -->
                            <th>TIPO</th>
                            <!-- Coluna Data Início -->
                            <th>DATA INÍCIO</th>
                            <!-- Coluna Data Fim -->
                            <th>DATA FIM</th>
                            <!-- Coluna Responsável -->
                            <th>RESPONSÁVEL</th>
                            <!-- Coluna Fornecedor -->
                            <th>FORNECEDOR</th>
                            <!-- Coluna Custo -->
                            <th>CUSTO</th>
                            <!-- Coluna Observações -->
                            <th>OBSERVAÇÕES</th>
                            <?php if (tem_permissao('maintenances.edit') || tem_permissao('maintenances.delete')): ?>
                                <!-- Coluna Ações -->
                                <th class="text-end">AÇÕES</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <!-- Corpo Tabela -->
                    <tbody>
                        <?php foreach ($listaManutencoes as $item): ?>
                            <?php
                            // Encriptar ID
                            $encId = aes_encrypt($item->getIdManutencao());
                            ?>
                            <!-- Linha Manutenção -->
                            <tr>
                                <!-- Célula Tipo -->
                                <td>
                                    <!-- Texto -->
                                    <span
                                        class="text-secondary fw-500"><?= htmlspecialchars($item->getTipoManutencao()->value) ?></span>
                                </td>
                                <!-- Célula Data Início -->
                                <td>
                                    <!-- Texto -->
                                    <span
                                        class="text-secondary fw-400"><?= htmlspecialchars($item->getDataInicio()->format('d/m/Y')) ?></span>
                                </td>
                                <!-- Célula Data Fim -->
                                <td>
                                    <!-- Texto -->
                                    <span
                                        class="text-secondary fw-400"><?= $item->getDataFim() ? htmlspecialchars($item->getDataFim()->format('d/m/Y')) : '—' ?></span>
                                </td>
                                <!-- Célula Responsável -->
                                <td>
                                    <!-- Link Pessoa -->
                                    <a href="../../entities/people_management.php?search=<?= urlencode(aes_encrypt($item->getIdPessoaResponsavel())) ?>"
                                        class="text-primary-500 fw-700 text-decoration-none hover-underline">
                                        <?= htmlspecialchars($item->getPessoaNome() ?? 'Desconhecido') ?>
                                    </a>
                                </td>
                                <!-- Célula Fornecedor -->
                                <td>
                                    <!-- Texto -->
                                    <span
                                        class="text-secondary fw-400"><?= htmlspecialchars($item->getFornecedorNome() ?? '—') ?></span>
                                </td>
                                <!-- Célula Custo -->
                                <td>
                                    <!-- Texto -->
                                    <span
                                        class="text-secondary fw-400"><?= $item->getCustoManutencao() !== null ? htmlspecialchars(number_format($item->getCustoManutencao(), 2, ',', ' ')) . ' €' : '—' ?></span>
                                </td>
                                <!-- Célula Observações -->
                                <td class="max-width-200">
                                    <?php
                                    // Limitar observações
                                    $obs = $item->getObservacoes() ?? '';
                                    $shortObs = mb_strlen($obs) > 50 ? mb_substr($obs, 0, 50) . '...' : $obs;
                                    ?>
                                    <!-- Texto -->
                                    <span class="text-secondary fw-400 d-inline-block w-100"
                                        title="<?= htmlspecialchars($obs) ?>">
                                        <?= htmlspecialchars($shortObs ?: '—') ?>
                                    </span>
                                </td>
                                <?php if (tem_permissao('maintenances.edit') || tem_permissao('maintenances.delete')): ?>
                                    <!-- Célula Ações -->
                                    <td class="text-end">
                                        <!-- Wrapper Ações -->
                                        <div class="d-flex justify-content-end gap-3 align-items-center">
                                            <?php if (tem_permissao('maintenances.edit')): ?>
                                                <!-- Botão Editar -->
                                                <button
                                                    class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                    type="button" title="Editar" data-bs-toggle="modal"
                                                    data-bs-target="#edit-maintenance-modal-<?= $encId ?>">
                                                    <!-- SVG Lápis -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                        <path d="m15 5 4 4" />
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (tem_permissao('maintenances.delete')): ?>
                                                <!-- Botão Eliminar -->
                                                <button
                                                    class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                    type="button" title="Eliminar" data-bs-toggle="modal"
                                                    data-bs-target="#delete-maintenance-modal-<?= $encId ?>">
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
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Nova Manutenção -->
<?php if (tem_permissao('maintenances.create')): ?>
    <!-- Modal -->
    <div class="modal fade" id="add-maintenance-modal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel"
        aria-hidden="true">
        <!-- Dialog -->
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <!-- Content -->
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Header -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <!-- Título -->
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="addMaintenanceModalLabel">
                        Nova Manutenção</h2>
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

                <!-- Body -->
                <div class="modal-body p-0">
                    <!-- Formulário -->
                    <form id="add-maintenance-form" action="equipments-crud/create-maintenance.php" method="POST"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column" novalidate>
                        <!-- Input ID Equipamento -->
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">

                        <!-- Wrapper Tipo Manutenção -->
                        <div class="d-flex flex-column form-item w-100">
                            <!-- Label -->
                            <div class="d-flex gap-1">
                                <label for="maintenance-type">Tipo de Manutenção</label>
                                <!-- SVG Asterisco -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <!-- Select Tipo Manutenção -->
                            <select id="maintenance-type" name="maintenance-type" class="form-select w-100" required>
                                <!-- Opção Placeholder -->
                                <option value="" disabled selected>Selecionar...</option>
                                <?php foreach (TipoManutencao::cases() as $t): ?>
                                    <!-- Opção -->
                                    <option value="<?= htmlspecialchars($t->value) ?>"><?= htmlspecialchars($t->value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Wrapper Row Datas -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <!-- Wrapper Data Início -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <div class="d-flex gap-1">
                                    <label for="maintenance-start-date">Data Início</label>
                                    <!-- SVG Asterisco -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <!-- Wrapper Input -->
                                <div class="position-relative w-100 date-input">
                                    <!-- Input Data -->
                                    <input type="text" id="maintenance-start-date" name="maintenance-start-date"
                                        class="w-100" placeholder="dd/mm/yyyy" required>
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

                            <!-- Wrapper Data Fim -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <label for="maintenance-end-date">Data Fim</label>
                                <!-- Wrapper Input -->
                                <div class="position-relative w-100 date-input">
                                    <!-- Input Data -->
                                    <input type="text" id="maintenance-end-date" name="maintenance-end-date" class="w-100"
                                        placeholder="dd/mm/yyyy">
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

                        <!-- Wrapper Pessoa Responsável -->
                        <div class="d-flex flex-column form-item w-100">
                            <!-- Label -->
                            <div class="d-flex gap-1">
                                <label for="maintenance-responsible">Pessoa Responsável</label>
                                <!-- SVG Asterisco -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <!-- Select Responsável -->
                            <select id="maintenance-responsible" name="maintenance-responsible" class="form-select w-100"
                                required>
                                <!-- Opção Placeholder -->
                                <option value="" disabled selected>Selecionar...</option>
                                <?php foreach ($pessoasDisponiveis as $pessoa): ?>
                                    <!-- Opção -->
                                    <option value="<?= htmlspecialchars($pessoa['idPessoa']) ?>">
                                        <?= htmlspecialchars($pessoa['nome'] . ' — ' . $pessoa['funcao']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Wrapper Row Fornecedor & Custo -->
                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <!-- Wrapper Fornecedor -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <label for="maintenance-supplier">Fornecedor Associado</label>
                                <!-- Select Fornecedor -->
                                <select id="maintenance-supplier" name="maintenance-supplier" class="form-select w-100">
                                    <!-- Opção Placeholder -->
                                    <option value="" selected>Nenhum</option>
                                    <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                        <!-- Opção -->
                                        <option value="<?= htmlspecialchars($f['idFornecedor']) ?>">
                                            <?= htmlspecialchars($f['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Wrapper Custo -->
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <label for="maintenance-cost">Custo da Manutenção</label>
                                <!-- Input Custo -->
                                <input type="number" step="0.01" min="0" id="maintenance-cost" name="maintenance-cost"
                                    class="w-100" placeholder="0.00">
                            </div>
                        </div>

                        <!-- Wrapper Observações -->
                        <div class="d-flex flex-column form-item w-100">
                            <!-- Label -->
                            <label for="maintenance-obs">Observações</label>
                            <!-- Textarea -->
                            <textarea id="maintenance-obs" name="maintenance-notes" class="w-100 no-resize" rows="3"
                                placeholder="Detalhes da manutenção..."></textarea>
                        </div>

                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <!-- Wrapper Debug Buttons -->
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light">
                                <!-- Texto -->
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                    (Debug)</span>
                                <!-- Botão Debug 1 -->
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillMaintenance('Preventiva', '10/05/2026', '12/05/2026', '1', '1', '150.00', 'Manutenção preventiva anual.')">
                                    Preventiva Completa
                                </button>
                                <!-- Botão Debug 2 -->
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillMaintenance('Corretiva', '15/05/2026', '', '1', '', '', 'Aguardar peça de substituição.')">
                                    Corretiva em Curso
                                </button>
                                <!-- Botão Debug 3 -->
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                    onclick="prefillMaintenance('Calibração', '20/05/2026', '21/05/2026', '1', '2', '80.00', 'Calibração efetuada com sucesso.')">
                                    Calibração
                                </button>
                            </div>
                            <!-- Script Debug -->
                            <script>
                                // Preencher formulário
                                function prefillMaintenance(type, start, end, responsible, supplier, cost, notes) {
                                    document.getElementById('maintenance-type').value = type;
                                    document.getElementById('maintenance-start-date').value = start;
                                    document.getElementById('maintenance-end-date').value = end;
                                    document.getElementById('maintenance-responsible').value = responsible;
                                    document.getElementById('maintenance-supplier').value = supplier;
                                    document.getElementById('maintenance-cost').value = cost;
                                    document.getElementById('maintenance-obs').value = notes;

                                    // Disparar eventos
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

                        <!-- Footer Formulário -->
                        <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                            <!-- Botão Cancelar -->
                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                data-bs-dismiss="modal">Cancelar</button>
                            <!-- Botão Submit -->
                            <button type="submit" id="btn-submit-maintenance"
                                class="btn btn-primary btn-glowing d-flex align-items-center gap-2" disabled>
                                <!-- SVG Check -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-check">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                <!-- Texto Botão -->
                                Registar Manutenção
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($listaManutencoes as $item): ?>
    <?php
    // Encriptar ID
    $encId = aes_encrypt($item->getIdManutencao());
    ?>
    <?php if (tem_permissao('maintenances.edit')): ?>
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
                        <!-- Formulário -->
                        <form action="equipments-crud/edit-maintenance.php" method="POST"
                            class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column edit-maintenance-form" novalidate>
                            <!-- Input ID Equipamento -->
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <!-- Input ID Manutenção -->
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
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido
                                        (Debug)</span>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1"
                                        onclick="prefillFields({'edit-maintenance-obs-<?= $encId ?>': 'Manutenção Editada'}); setTimeout(() => { document.getElementById('edit-maintenance-obs-<?= $encId ?>').dispatchEvent(new Event('input', { bubbles: true })); document.querySelector('#edit-maintenance-modal-<?= $encId ?> .edit-maintenance-type').dispatchEvent(new Event('change', { bubbles: true })); }, 100);">Editar
                                        Observações</button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('maintenances.delete')): ?>
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
                            <span class="text-secondary fw-400">A manutenção será movida para a reciclagem.</span>
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
                    <!-- Body -->
                    <div class="modal-body p-0">
                        <!-- Formulário -->
                        <form method="POST" action="equipments-crud/delete-maintenance.php" novalidate>
                            <!-- Input ID Equipamento -->
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <!-- Input ID Manutenção -->
                            <input type="hidden" name="maintenance-id" value="<?= htmlspecialchars($encId) ?>">
                            <!-- Conteúdo Principal -->
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                                <!-- Wrapper Ícone e Texto -->
                                <div class="d-flex flex-column align-items-center gap-4">
                                    <!-- Wrapper Ícone -->
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