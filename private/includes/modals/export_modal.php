<!-- Modal de Exportação -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="exportModalLabel">
                        Exportar Dados
                    </h2>
                    <span class="text-secondary fw-400">Selecione o formato pretendido para exportação</span>
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

            <!-- Body -->
            <div class="modal-body p-0">
                <div class="padding-6 pt-2 pb-2">
                    <input type="hidden" id="exportTypeInput" value="csv">
                    
                    <div class="d-flex flex-column gap-3">
                        <!-- Option: CSV -->
                        <div class="export-option-card bento-card p-3 d-flex align-items-center gap-3 selected-csv" data-export-type="csv">
                            <div class="export-icon-wrapper icon-csv">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-spreadsheet text-primary-500"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M14 13h2"/><path d="M14 17h2"/></svg>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-600 text-primary">CSV (Excel)</span>
                                <span class="text-secondary" style="font-size: 12px;">Para tabelas de cálculo</span>
                            </div>
                        </div>

                        <!-- Option: JSON -->
                        <div class="export-option-card bento-card p-3 d-flex align-items-center gap-3" data-export-type="json">
                            <div class="export-icon-wrapper icon-json">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-json text-warning"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M10 12a1 1 0 0 0-1 1v1a1 1 0 0 1-1 1 1 1 0 0 1 1 1v1a1 1 0 0 0 1 1"/><path d="M14 18a1 1 0 0 0 1-1v-1a1 1 0 0 1 1-1 1 1 0 0 1-1-1v-1a1 1 0 0 0-1-1"/></svg>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-600 text-primary">JSON</span>
                                <span class="text-secondary" style="font-size: 12px;">Para sistemas e APIs</span>
                            </div>
                        </div>

                        <!-- Option: PDF -->
                        <div class="export-option-card bento-card p-3 d-flex align-items-center gap-3" data-export-type="pdf">
                            <div class="export-icon-wrapper icon-pdf">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text text-error"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-600 text-primary">PDF</span>
                                <span class="text-secondary" style="font-size: 12px;">Documento pronto a imprimir</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 padding-6 pt-4 pb-6 d-flex justify-content-end gap-4">
                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnConfirmExport" class="btn btn-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-download">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Exportar
                </button>
            </div>
        </div>
    </div>
</div>
