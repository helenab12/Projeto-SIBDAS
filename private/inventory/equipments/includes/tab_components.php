<?php
$componentes_associados = [
    [
        'id' => 1,
        'nome' => 'Sensor de Fluxo',
        'categoria' => 'Ventiladores',
        'quantidade' => 2,
        'stock' => 24
    ],
    [
        'id' => 2,
        'nome' => 'Válvula de Expiração',
        'categoria' => 'Ventiladores',
        'quantidade' => 1,
        'stock' => 15
    ],
    [
        'id' => 3,
        'nome' => 'Filtro HEPA',
        'categoria' => 'Ventiladores',
        'quantidade' => 3,
        'stock' => 48
    ]
];
?>
<div class="tab-pane fade" id="nav-componentes" role="tabpanel" aria-labelledby="nav-componentes-tab">
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Componentes Associados</h2>
            <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#add-component-modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                <span>Adicionar Componente</span>
            </button>
        </div>

        <?php if (empty($componentes_associados)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 py-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-puzzle text-muted opacity-50">
                    <path
                        d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                </svg>
                <span class="text-secondary fw-500">Sem componentes associados</span>
            </div>
        <?php else: ?>
            <table id="componentsTable" class="sibdas-table w-100 display border-0">
                <thead>
                    <tr>
                        <th>COMPONENTE</th>
                        <th>CATEGORIA</th>
                        <th>QTD. UTILIZADA</th>
                        <th>STOCK DISPONÍVEL</th>
                        <th class="text-end">AÇÃO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($componentes_associados as $comp): ?>
                        <tr>
                            <td>
                                <a href="../components.php"
                                    class="text-primary-500 fw-700 text-decoration-none hover-underline">
                                    <?= htmlspecialchars($comp['nome']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($comp['categoria']) ?></span>
                            </td>
                            <td>
                                <span class="fw-700 text-primary"><?= $comp['quantidade'] ?></span>
                            </td>
                            <td>
                                <span class="fw-700 text-primary"><?= $comp['stock'] ?> un.</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-3 align-items-center">
                                    <button
                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                        type="button" title="Editar" data-bs-toggle="modal"
                                        data-bs-target="#edit-component-modal" data-comp-id="<?= $comp['id'] ?>"
                                        data-comp-name="<?= htmlspecialchars($comp['nome']) ?>"
                                        data-comp-qty="<?= $comp['quantidade'] ?>">
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
                                        data-bs-target="#delete-component-modal" data-comp-id="<?= $comp['id'] ?>"
                                        data-comp-name="<?= htmlspecialchars($comp['nome']) ?>">
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

<!-- Modal de Adicionar Componente -->
<div class="modal fade" id="add-component-modal" tabindex="-1" aria-labelledby="addComponentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addComponentModalLabel">
                    Adicionar Componente</h2>
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
                <form id="add-component-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Selecionar Componente -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="select-component">Selecionar Componente</label>
                        <select id="select-component" name="select-component" class="form-select w-100" required>
                            <option value="" disabled selected>Selecione pela categoria...</option>
                            <option value="Sensor de Fluxo">Sensor de Fluxo (Stock: 24)</option>
                            <option value="Válvula de Expiração">Válvula de Expiração (Stock: 15)</option>
                            <option value="Filtro HEPA">Filtro HEPA (Stock: 48)</option>
                            <option value="Cabo de ECG (5 derivações)">Cabo de ECG (5 derivações) (Stock: 32)</option>
                            <option value="Sensor SpO2 reutilizável">Sensor SpO2 reutilizável (Stock: 18)</option>
                            <option value="Braçadeira PNI (adulto)">Braçadeira PNI (adulto) (Stock: 40)</option>
                            <option value="Cassete de Infusão">Cassete de Infusão (Stock: 120)</option>
                            <option value="Seringa de 50ml (BD)">Seringa de 50ml (BD) (Stock: 200)</option>
                            <option value="Bateria Li-Ion">Bateria Li-Ion (Stock: 6)</option>
                            <option value="Pás de Desfibrilhação (adulto)">Pás de Desfibrilhação (adulto) (Stock: 30)
                            </option>
                            <option value="Elétrodos de Desfibrilhação">Elétrodos de Desfibrilhação (Stock: 50)</option>
                            <option value="Tubo de Rx">Tubo de Rx (Stock: 3)</option>
                            <option value="Lâmpada de foco cirúrgico">Lâmpada de foco cirúrgico (Stock: 12)</option>
                            <option value="Reagentes bioquímicos">Reagentes bioquímicos (Stock: 8)</option>
                            <option value="Indicador biológico">Indicador biológico (Stock: 60)</option>
                        </select>
                    </div>

                    <!-- Quantidade Utilizada -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="component-qty">Quantidade Utilizada</label>
                        <input type="number" id="component-qty" name="component-qty" value="1" min="1" required>
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
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Editar Componente -->
<div class="modal fade" id="edit-component-modal" tabindex="-1" aria-labelledby="editComponentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="editComponentModalLabel">
                    Editar Componente Selecionado</h2>
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
                <form id="edit-component-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Selecionar Componente -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-select-component">Selecionar Componente</label>
                        <select id="edit-select-component" name="select-component" class="form-select w-100" required>
                            <option value="" disabled>Selecione pela categoria...</option>
                            <option value="Sensor de Fluxo">Sensor de Fluxo (Stock: 24)</option>
                            <option value="Válvula de Expiração">Válvula de Expiração (Stock: 15)</option>
                            <option value="Filtro HEPA">Filtro HEPA (Stock: 48)</option>
                            <option value="Cabo de ECG (5 derivações)">Cabo de ECG (5 derivações) (Stock: 32)</option>
                            <option value="Sensor SpO2 reutilizável">Sensor SpO2 reutilizável (Stock: 18)</option>
                            <option value="Braçadeira PNI (adulto)">Braçadeira PNI (adulto) (Stock: 40)</option>
                            <option value="Cassete de Infusão">Cassete de Infusão (Stock: 120)</option>
                            <option value="Seringa de 50ml (BD)">Seringa de 50ml (BD) (Stock: 200)</option>
                            <option value="Bateria Li-Ion">Bateria Li-Ion (Stock: 6)</option>
                            <option value="Pás de Desfibrilhação (adulto)">Pás de Desfibrilhação (adulto) (Stock: 30)
                            </option>
                            <option value="Elétrodos de Desfibrilhação">Elétrodos de Desfibrilhação (Stock: 50)</option>
                            <option value="Tubo de Rx">Tubo de Rx (Stock: 3)</option>
                            <option value="Lâmpada de foco cirúrgico">Lâmpada de foco cirúrgico (Stock: 12)</option>
                            <option value="Reagentes bioquímicos">Reagentes bioquímicos (Stock: 8)</option>
                            <option value="Indicador biológico">Indicador biológico (Stock: 60)</option>
                        </select>
                    </div>

                    <!-- Quantidade Utilizada -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-component-qty">Quantidade Utilizada</label>
                        <input type="number" id="edit-component-qty" name="component-qty" min="1" required>
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
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Remoção de Componente -->
<div class="modal fade" id="delete-component-modal" tabindex="-1" aria-labelledby="deleteComponentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="deleteComponentModalLabel">Eliminar Componente</h2>
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
                                <p class="text-secondary m-0">Tem a certeza que deseja apagar o
                                    componente</p>
                                <h2 class="fw-700 text-primary m-0" id="delete-comp-display-name">"Sensor de Fluxo"</h2>
                            </div>
                            <div class="danger-banner text-error text-center padding-3">
                                <span>⚠️ Esta associação será eliminada permanentemente. Todos os dados associados serão
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