<?php
// Obter equipamentos ativos
$stmtEquip = execute_query("SELECT idEquipamento, designacao, codigoInterno FROM Equipamento WHERE ativo = 1 ORDER BY designacao ASC", [], connect_to_db());
// Extrair resultados
$equipamentosQR = $stmtEquip->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Modal QR Code -->
<div class="modal fade" id="qrSelectModal" tabindex="-1" aria-labelledby="qrSelectModalLabel" aria-hidden="true">
    <!-- Dialog -->
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <!-- Conteúdo Modal -->
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header Modal -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <!-- Wrapper Títulos -->
                <div class="d-flex flex-column">
                    <!-- Título -->
                    <h2 class="equipment-creation-modal-title modal-title" id="qrSelectModalLabel">
                        Gerar QR Code
                    </h2>
                    <!-- Subtítulo -->
                    <span class="text-secondary fw-400">Selecione um equipamento do inventário</span>
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

            <!-- Body Modal -->
            <div class="modal-body p-0">
                <!-- Wrapper Formulário -->
                <div class="floor-create-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Wrapper Select -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <!-- Label Equipamento -->
                        <label for="qrEquipamentoSelect" class="d-flex gap-1 align-items-center">Equipamento</label>
                        <!-- Select Equipamento -->
                        <select class="form-select" id="qrEquipamentoSelect">
                            <!-- Opção Placeholder -->
                            <option value="" disabled selected>Escolha um equipamento...</option>
                            <?php foreach ($equipamentosQR as $eq):
                                // Encriptar ID
                                $encId = aes_encrypt((string) $eq['idEquipamento']);
                                ?>
                                <!-- Opção Equipamento -->
                                <option value="<?= htmlspecialchars($encId) ?>"
                                    data-code="<?= htmlspecialchars($eq['codigoInterno']) ?>"
                                    data-desc="<?= htmlspecialchars($eq['designacao']) ?>">
                                    <?= htmlspecialchars($eq['codigoInterno']) ?> -
                                    <?= htmlspecialchars($eq['designacao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Footer Formulário -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                        <!-- Botão Cancelar -->
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <!-- Botão Avançar -->
                        <button type="button" id="btnProceedQRPrint"
                            class="btn-create-floor-submit btn btn-primary gap-2" disabled>
                            Avançar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>