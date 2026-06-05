<div class="tab-pane fade" id="nav-garantias" role="tabpanel" aria-labelledby="nav-garantias-tab">
    <?php
    $garantias = [
        [
            'id' => 1,
            'data' => '12/06/2023',
            'descricao' => 'Substituição de sensor de fluxo sob garantia',
            'estado' => 'Resolvido',
            'fornecedor' => 'Dräger Portugal, Lda.'
        ],
        [
            'id' => 2,
            'data' => '08/02/2024',
            'descricao' => 'Atualização de firmware v3.2.1',
            'estado' => 'Resolvido',
            'fornecedor' => 'Dräger Portugal, Lda.'
        ],
        [
            'id' => 3,
            'data' => '15/03/2024',
            'descricao' => 'Garantia alargada de componentes vitais',
            'estado' => 'Ativo',
            'fornecedor' => 'Dräger Portugal, Lda.'
        ],
        [
            'id' => 4,
            'data' => '15/03/2022',
            'descricao' => 'Contrato de manutenção preventiva anual',
            'estado' => 'Expirado',
            'fornecedor' => 'Dräger Portugal, Lda.'
        ]
    ];
    ?>
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Garantias & Contratos</h2>
            <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#add-warranty-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-plus">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                <span>Adicionar</span>
            </button>
        </div>

        <?php if (empty($garantias)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 py-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-shield text-muted opacity-50">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <span class="text-secondary fw-500">Sem registos de garantia</span>
            </div>
        <?php else: ?>
            <table id="warrantiesTable" class="sibdas-table w-100 display border-0">
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>DESCRIÇÃO</th>
                        <th>ESTADO</th>
                        <th>FORNECEDOR</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($garantias as $item): ?>
                        <?php
                        $badgeClass = '';
                        if ($item['estado'] === 'Resolvido') {
                            $badgeClass = 'equipment-badge-status-active';
                        } elseif ($item['estado'] === 'Ativo') {
                            $badgeClass = 'equipment-badge new';
                        } elseif ($item['estado'] === 'Expirado') {
                            $badgeClass = 'equipment-badge-status-abated';
                        }
                        ?>
                        <tr>
                            <td>
                                <span class="text-secondary fw-400"><?= $item['data'] ?></span>
                            </td>
                            <td>
                                <span class="fw-700"><?= $item['descricao'] ?></span>
                            </td>
                            <td>
                                <span class="equipment-badge <?= $badgeClass ?>"><?= $item['estado'] ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= $item['fornecedor'] ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Editar" data-bs-toggle="modal"
                                        data-bs-target="#edit-warranty-modal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-pencil">
                                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                            <path d="m15 5 4 4" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Eliminar" data-bs-toggle="modal"
                                        data-bs-target="#delete-warranty-modal">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-trash-2 text-secondary">
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

<!-- Modal de Nova Garantia / Contrato -->
<div class="modal fade" id="add-warranty-modal" tabindex="-1" aria-labelledby="addWarrantyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addWarrantyModalLabel">
                    Nova Garantia / Contrato</h2>
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
                <form id="add-warranty-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Row: Data & Estado -->
                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <!-- Data -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="warranty-date">Data</label>
                            <div class="position-relative w-100">
                                <input type="text" id="warranty-date" name="warranty-date" class="w-100"
                                    placeholder="dd/mm/yyyy" required>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-calendar text-secondary position-absolute"
                                    style="right: var(--space-4); top: 50%; transform: translateY(-50%); pointer-events: none;">
                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                    <line x1="16" x2="16" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="2" y2="6" />
                                    <line x1="3" x2="21" y1="10" y2="10" />
                                </svg>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="warranty-status">Estado</label>
                            <select id="warranty-status" name="warranty-status" class="form-select w-100">
                                <option value="Ativo" selected>Ativo</option>
                                <option value="Resolvido">Resolvido</option>
                                <option value="Expirado">Expirado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="warranty-supplier">Fornecedor Associado</label>
                        <select id="warranty-supplier" name="warranty-supplier" class="form-select w-100">
                            <option value="Nenhum fornecedor" selected>Nenhum fornecedor</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="B. Braun Medical, Lda.">B. Braun Medical, Lda.</option>
                            <option value="Stryker Portugal">Stryker Portugal</option>
                            <option value="GE Healthcare Portugal">GE Healthcare Portugal</option>
                            <option value="Medtronic Portugal">Medtronic Portugal</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                            <option value="MedicalTech Distribuição, Lda.">MedicalTech Distribuição, Lda.</option>
                            <option value="EquipHospital, S.A.">EquipHospital, S.A.</option>
                            <option value="BioServiços - Assistência Técnica">BioServiços - Assistência Técnica</option>
                            <option value="TecnoMed Assistência">TecnoMed Assistência</option>
                            <option value="Consumíveis Hospitalares, Lda.">Consumíveis Hospitalares, Lda.</option>
                        </select>
                    </div>

                    <!-- Descrição -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="warranty-desc">Descrição</label>
                        <textarea id="warranty-desc" name="warranty-desc" class="w-100 no-resize" rows="3"
                            placeholder="Descrição da garantia ou contrato..." required></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                            Guardar Ocorrência
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Editar Garantia / Contrato -->
<div class="modal fade" id="edit-warranty-modal" tabindex="-1" aria-labelledby="editWarrantyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="editWarrantyModalLabel">
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
                <form id="edit-warranty-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Row: Data & Estado -->
                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <!-- Data -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="edit-warranty-date">Data</label>
                            <div class="position-relative w-100">
                                <input type="text" id="edit-warranty-date" name="warranty-date" class="w-100"
                                    placeholder="dd/mm/yyyy" value="08/02/2024" required>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="lucide lucide-calendar text-secondary position-absolute"
                                    style="right: var(--space-4); top: 50%; transform: translateY(-50%); pointer-events: none;">
                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                    <line x1="16" x2="16" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="2" y2="6" />
                                    <line x1="3" x2="21" y1="10" y2="10" />
                                </svg>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <label for="edit-warranty-status">Estado</label>
                            <select id="edit-warranty-status" name="warranty-status" class="form-select w-100">
                                <option value="Ativo">Ativo</option>
                                <option value="Resolvido" selected>Resolvido</option>
                                <option value="Expirado">Expirado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-warranty-supplier">Fornecedor Associado</label>
                        <select id="edit-warranty-supplier" name="warranty-supplier" class="form-select w-100">
                            <option value="Nenhum fornecedor">Nenhum fornecedor</option>
                            <option value="Dräger Portugal, Lda." selected>Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="B. Braun Medical, Lda.">B. Braun Medical, Lda.</option>
                            <option value="Stryker Portugal">Stryker Portugal</option>
                            <option value="GE Healthcare Portugal">GE Healthcare Portugal</option>
                            <option value="Medtronic Portugal">Medtronic Portugal</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                            <option value="MedicalTech Distribuição, Lda.">MedicalTech Distribuição, Lda.</option>
                            <option value="EquipHospital, S.A.">EquipHospital, S.A.</option>
                            <option value="BioServiços - Assistência Técnica">BioServiços - Assistência Técnica</option>
                            <option value="TecnoMed Assistência">TecnoMed Assistência</option>
                            <option value="Consumíveis Hospitalares, Lda.">Consumíveis Hospitalares, Lda.</option>
                        </select>
                    </div>

                    <!-- Descrição -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-warranty-desc">Descrição</label>
                        <textarea id="edit-warranty-desc" name="warranty-desc" class="w-100 no-resize" rows="3"
                            placeholder="Descrição da garantia ou contrato..."
                            required>Atualização de firmware v3.2.1</textarea>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                            Guardar Ocorrência
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Remoção de Garantia -->
<div class="modal fade" id="delete-warranty-modal" tabindex="-1" aria-labelledby="deleteWarrantyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="deleteWarrantyModalLabel">Eliminar Garantia / Contrato</h2>
                    <span class="text-secondary fw-400">Esta ação não pode ser revertida.</span>
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

            <!-- Body do Modal -->
            <div class="modal-body p-0">
                <div
                    class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                    <div class="d-flex flex-column align-items-center gap-4">
                        <div class="d-flex padding-3 danger-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-alert-triangle text-error">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                <line x1="12" y1="9" x2="12" y2="13" />
                                <line x1="12" y1="17" x2="12.01" y2="17" />
                            </svg>
                        </div>
                        <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                            <div class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                <p class="text-secondary m-0">Tem a certeza que deseja apagar permanentemente a
                                    garantia/contrato</p>
                                <h2 class="fw-700 text-primary m-0" id="delete-warranty-display-name">"Atualização de
                                    firmware v3.2.1"</h2>
                            </div>
                            <div class="danger-banner text-error text-center padding-3">
                                <span>⚠️ Esta ocorrência será eliminada permanentemente. Todos os dados associados serão
                                    perdidos.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botoes -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-glowing text-white">Sim, Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
