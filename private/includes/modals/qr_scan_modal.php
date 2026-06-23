<!-- Modal Leitura QR Code -->
<div class="modal fade" id="qrScanModal" tabindex="-1" aria-labelledby="qrScanModalLabel" aria-hidden="true">
    <!-- Wrapper Dialog -->
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <!-- Conteúdo Modal -->
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Secção Título -->
            <div class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <!-- Wrapper Textos -->
                <div class="d-flex flex-column">
                    <!-- Título -->
                    <h2 class="equipment-creation-modal-title modal-title" id="qrScanModalLabel">
                        Ler QR Code da Etiqueta
                    </h2>
                    <!-- Subtítulo -->
                    <span class="text-secondary fw-400">Aponte a câmara para a etiqueta do equipamento</span>
                </div>

                <!-- Botão Fechar -->
                <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <!-- SVG Fechar -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x stroke-secondary">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <!-- Corpo Modal -->
            <div class="modal-body p-0">
                <!-- Wrapper Leitor -->
                <div class="equipment-creation-modal-content padding-6 d-flex flex-column">
                    <!-- Leitor QR Code -->
                    <div id="reader" style="width: 100%; border-radius: var(--radius-md); overflow: hidden;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
