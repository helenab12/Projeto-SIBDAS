<div class="tab-pane fade <?= $activeTab === 'fornecedores' ? 'show active' : '' ?>" id="nav-fornecedores" role="tabpanel"
    aria-labelledby="nav-fornecedores-tab">
    <?php

    // Mock data para fornecedores do equipamento atual
    $fabricante = [
        'nome' => 'Dräger Portugal, Lda.',
        'email' => 'info@drager.pt',
        'telefone' => '+351 214 567 890',
        'website' => 'https://www.drager.com/pt'
    ];

    $distribuidor = [
        'nome' => 'Medical Tech Distribuição Lda',
        'email' => 'info@medicaltech.pt',
        'telefone' => '+351 219 876 543',
        'website' => null
    ];

    $assistencias = [
        [
            'nome' => 'Dräger Portugal, Lda.',
            'telefone' => '+351 214 567 890'
        ],
        [
            'nome' => 'BioServiços - Assistência Técnica',
            'telefone' => '+351 215 432 109'
        ]
    ];
    ?>

    <div class="d-flex flex-column flex-lg-row gap-4 w-100">
        <!-- Card 1: Fabricante -->
        <div class="card bento-card supplier-card padding-6 d-flex flex-column gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="table-icon-wrapper manufacturer-icon-wrapper text-primary-500 padding-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-factory-icon lucide-factory">
                        <path d="M12 16h.01" />
                        <path d="M16 16h.01" />
                        <path
                            d="M3 19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5a.5.5 0 0 0-.769-.422l-4.462 2.844A.5.5 0 0 1 15 10.5v-2a.5.5 0 0 0-.769-.422L9.77 10.922A.5.5 0 0 1 9 10.5V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z" />
                        <path d="M8 16h.01" />
                    </svg>
                </div>
                <h3 class="fw-700 m-0 text-primary">Fabricante</h3>
            </div>

            <div class="d-flex flex-column flex-grow-1 gap-2">
                <?php if ($fabricante): ?>
                    <div class="d-flex flex-column gap-1 align-items-start">
                        <h2 class="fw-700 text-primary"><?= $fabricante['nome'] ?>
                        </h2>
                        <p class="text-secondary fw-400"><?= $fabricante['email'] ?>
                        </p>
                        <p class="text-secondary fw-400">
                            <?= $fabricante['telefone'] ?>
                        </p>
                    </div>
                    <?php if ($fabricante['website']): ?>
                        <a href="<?= $fabricante['website'] ?>" target="_blank"
                            class="d-flex align-items-center gap-1 text-primary-500 text-decoration-none fw-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-external-link-icon lucide-external-link">
                                <path d="M15 3h6v6" />
                                <path d="M10 14 21 3" />
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            </svg>
                            <span>Website</span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-secondary opacity-75 fw-500 m-0">Não definido</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card 2: Distribuidor -->
        <div class="card bento-card supplier-card padding-6 d-flex flex-column gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="table-icon-wrapper distributor-icon-wrapper padding-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-truck-icon lucide-truck">
                        <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                        <path d="M15 18H9" />
                        <path
                            d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" />
                        <circle cx="17" cy="18" r="2" />
                        <circle cx="7" cy="18" r="2" />
                    </svg>
                </div>
                <h3 class="fw-700 m-0 text-primary">Distribuidor</h3>
            </div>

            <div class="d-flex flex-column flex-grow-1">
                <?php if ($distribuidor): ?>
                    <div class="d-flex flex-column gap-1 align-items-start">
                        <h2 class="fw-700 text-primary">
                            <?= $distribuidor['nome'] ?>
                        </h2>
                        <p class="text-secondary fw-400">
                            <?= $distribuidor['email'] ?>
                        </p>
                        <p class="text-secondary fw-400">
                            <?= $distribuidor['telefone'] ?>
                        </p>
                    </div>
                    <?php if ($distribuidor['website']): ?>
                        <a href="<?= $distribuidor['website'] ?>" target="_blank"
                            class="d-flex align-items-center gap-1 text-primary-500 text-decoration-none fw-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-external-link-icon lucide-external-link">
                                <path d="M15 3h6v6" />
                                <path d="M10 14 21 3" />
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            </svg>
                            <span>Website</span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-secondary opacity-75 fw-500 m-0">Não definido</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card 3: Assistência Técnica -->
        <div class="card bento-card supplier-card padding-6 d-flex flex-column gap-4">
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center gap-3">
                    <div class="table-icon-wrapper support-icon-wrapper text-success padding-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-headset-icon lucide-headset">
                            <path
                                d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z" />
                            <path d="M21 16v2a4 4 0 0 1-4 4h-5" />
                        </svg>
                    </div>
                    <h3 class="fw-700 m-0 text-primary">Assistência Técnica</h3>
                </div>
                <button class="btn text-success d-flex align-items-center gap-1 p-0 border-0 bg-transparent"
                    type="button" data-bs-toggle="modal" data-bs-target="#add-technical-support-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-plus">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>Adicionar</span>
                </button>
            </div>

            <div class="d-flex flex-column gap-3 flex-grow-1">
                <?php if (!empty($assistencias)): ?>
                    <?php foreach ($assistencias as $assist): ?>
                        <div class="support-list-item d-flex flex-column gap-1">
                            <h3 class="fw-700 text-primary m-0"><?= $assist['nome'] ?></h3>
                            <p class="text-secondary fw-400 m-0"><?= $assist['telefone'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary opacity-75 fw-500 m-0">Não definido</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Associar Assistência Técnica -->
<div class="modal fade" id="add-technical-support-modal" tabindex="-1" aria-labelledby="addSupportModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addSupportModalLabel">
                    Associar Assistência Técnica</h2>
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
                <form id="add-support-form" class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Entidade -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="support-entity">Entidade de Assistência</label>
                        <select id="support-entity" name="support-entity" class="form-select w-100">
                            <option value="" disabled selected>Selecionar assistência...</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="BioServiços - Assistência Técnica">BioServiços - Assistência Técnica</option>
                            <option value="TecnoMed Assistência">TecnoMed Assistência</option>
                        </select>
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
                            <span>Associar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
