<?php
$manutencoes = [
    [
        'id' => 1,
        'data_inicio' => '18/11/2025',
        'data_fim' => '20/11/2025',
        'responsavel' => 'Eng. Carlos Mendes',
        'observacoes' => 'Calibração completa de sensores de fluxo e pressão.'
    ],
    [
        'id' => 2,
        'data_inicio' => '14/05/2025',
        'data_fim' => '15/05/2025',
        'responsavel' => 'Eng.ª Ana Ferreira',
        'observacoes' => 'Substituição de filtros HEPA e limpeza geral.'
    ]
];
?>
<div class="tab-pane fade" id="nav-manutencoes" role="tabpanel" aria-labelledby="nav-manutencoes-tab">
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

        <?php if (empty($manutencoes)): ?>
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
                        <th>DATA INÍCIO</th>
                        <th>DATA FIM</th>
                        <th>RESPONSÁVEL</th>
                        <th>OBSERVAÇÕES</th>
                        <th class="text-end">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($manutencoes as $item): ?>
                        <tr>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['data_inicio']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['data_fim']) ?></span>
                            </td>
                            <td>
                                <a href="../../entities/people_management.php"
                                    class="text-primary-500 fw-700 text-decoration-none hover-underline">
                                    <?= htmlspecialchars($item['responsavel']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['observacoes']) ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Editar" data-bs-toggle="modal"
                                        data-bs-target="#edit-maintenance-modal">
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
                                        data-bs-target="#delete-maintenance-modal">
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
                <form id="add-maintenance-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
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
                                <input type="text" id="maintenance-start-date" name="data_inicio" class="w-100"
                                    placeholder="dd/mm/yyyy" required>
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
                                <input type="text" id="maintenance-end-date" name="data_fim" class="w-100"
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
                        <select id="maintenance-responsible" name="responsavel" class="form-select w-100" required>
                            <option value="" disabled selected>Selecionar...</option>
                            <option value="Dr. Manuel Costa">Dr. Manuel Costa — Médico</option>
                            <option value="Eng.ª Ana Ferreira">Eng.ª Ana Ferreira — Engenheira Biomédica</option>
                            <option value="Eng. Carlos Mendes">Eng. Carlos Mendes — Técnico de Manutenção</option>
                            <option value="Dr.ª Helena Barbosa">Dr.ª Helena Barbosa — Diretora Clínica</option>
                            <option value="Sofia Oliveira">Sofia Oliveira — Administrativa</option>
                            <option value="Eng. Pedro Santos">Eng. Pedro Santos — Técnico de Manutenção</option>
                            <option value="Dr.ª Maria Lopes">Dr.ª Maria Lopes — Médica</option>
                        </select>
                    </div>

                    <!-- Observações -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="maintenance-obs">Observações</label>
                        <textarea id="maintenance-obs" name="observacoes" class="w-100 no-resize" rows="3"
                            placeholder="Detalhes da manutenção..."></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
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

<!-- Modal de Editar Manutenção -->
<div class="modal fade" id="edit-maintenance-modal" tabindex="-1" aria-labelledby="editMaintenanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                    id="editMaintenanceModalLabel">
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
                <form id="edit-maintenance-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Row: Datas -->
                    <div class="d-flex flex-column flex-md-row gap-4 w-100">
                        <!-- Data Inicio -->
                        <div class="d-flex flex-column form-item w-100 w-md-50">
                            <div class="d-flex gap-1">
                                <label for="edit-maintenance-start-date">Data Início</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <div class="position-relative w-100 date-input">
                                <input type="text" id="edit-maintenance-start-date" name="data_inicio" class="w-100"
                                    placeholder="dd/mm/yyyy" value="14/05/2025" required>
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
                            <label for="edit-maintenance-end-date">Data Fim</label>
                            <div class="position-relative w-100 date-input">
                                <input type="text" id="edit-maintenance-end-date" name="data_fim" class="w-100"
                                    placeholder="dd/mm/yyyy" value="15/05/2025">
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
                            <label for="edit-maintenance-responsible">Pessoa Responsável</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <select id="edit-maintenance-responsible" name="responsavel" class="form-select w-100" required>
                            <option value="" disabled>Selecionar...</option>
                            <option value="Dr. Manuel Costa">Dr. Manuel Costa — Médico</option>
                            <option value="Eng.ª Ana Ferreira" selected>Eng.ª Ana Ferreira — Engenheira Biomédica
                            </option>
                            <option value="Eng. Carlos Mendes">Eng. Carlos Mendes — Técnico de Manutenção</option>
                            <option value="Dr.ª Helena Barbosa">Dr.ª Helena Barbosa — Diretora Clínica</option>
                            <option value="Sofia Oliveira">Sofia Oliveira — Administrativa</option>
                            <option value="Eng. Pedro Santos">Eng. Pedro Santos — Técnico de Manutenção</option>
                            <option value="Dr.ª Maria Lopes">Dr.ª Maria Lopes — Médica</option>
                        </select>
                    </div>

                    <!-- Observações -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-maintenance-obs">Observações</label>
                        <textarea id="edit-maintenance-obs" name="observacoes" class="w-100 no-resize" rows="3"
                            placeholder="Detalhes da manutenção...">Substituição de filtros HEPA e limpeza geral.</textarea>
                    </div>

                    <!-- Footer -->
                    <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
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

<!-- Modal de Confirmação de Remoção de Manutenção -->
<div class="modal fade" id="delete-maintenance-modal" tabindex="-1" aria-labelledby="deleteMaintenanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="deleteMaintenanceModalLabel">Eliminar Registo de Manutenção</h2>
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
                                <p class="text-secondary m-0">Tem a certeza que deseja apagar o registo
                                    de manutenção?</p>
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