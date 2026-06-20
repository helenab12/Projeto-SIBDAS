<!-- qr_print_modal.php -->
<div class="modal fade" id="qrPrintModal" tabindex="-1" aria-labelledby="qrPrintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="qrPrintModalLabel">
                        Código QR do Equipamento
                    </h2>
                    <span class="text-secondary fw-400">Pronto para imprimir a etiqueta</span>
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
                <div class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column align-items-center" id="qrPrintArea">
                    <!-- Zona de Impressão -->
                    <img id="qrImage" class="mb-3 qr-canvas-preview" alt="QR Code" style="width: 200px; height: 200px;" />
                    <h4 id="qrEquipCode" class="mb-1 fw-bold qr-equip-code text-center">---</h4>
                    <p id="qrEquipDesignation" class="mb-0 qr-equip-designation text-center">---</p>
                </div>
            </div>

            <!-- Footer do Modal -->
            <div class="modal-footer border-0 padding-6 pt-0 d-flex justify-content-end gap-4">
                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary gap-2" id="btnDownloadQR">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Transferir Imagem
                </button>
            </div>
        </div>
    </div>
</div>

