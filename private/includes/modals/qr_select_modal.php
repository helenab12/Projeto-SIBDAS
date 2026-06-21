<?php
$stmtEquip = execute_query("SELECT idEquipamento, designacao, codigoInterno FROM Equipamento WHERE ativo = 1 ORDER BY designacao ASC", [], connect_to_db());
$equipamentosQR = $stmtEquip->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Modal de Seleção de Equipamento para Gerar QR -->
<div class="modal fade" id="qrSelectModal" tabindex="-1" aria-labelledby="qrSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title" id="qrSelectModalLabel">
                        Gerar QR Code
                    </h2>
                    <span class="text-secondary fw-400">Selecione um equipamento do inventário</span>
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
                <div class="floor-create-form equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Select -->
                    <div class="d-flex flex-column form-item w-100 mw-0">
                        <label for="qrEquipamentoSelect" class="d-flex gap-1 align-items-center">Equipamento</label>
                        <select class="form-select" id="qrEquipamentoSelect">
                            <option value="" disabled selected>Escolha um equipamento...</option>
                            <?php foreach ($equipamentosQR as $eq):
                                $encId = aes_encrypt((string) $eq['idEquipamento']);
                                ?>
                                <option value="<?= htmlspecialchars($encId) ?>"
                                    data-code="<?= htmlspecialchars($eq['codigoInterno']) ?>"
                                    data-desc="<?= htmlspecialchars($eq['designacao']) ?>">
                                    <?= htmlspecialchars($eq['codigoInterno']) ?> -
                                    <?= htmlspecialchars($eq['designacao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Footer do Formulario -->
                    <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4">
                        <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnProceedQRPrint"
                            class="btn-create-floor-submit btn btn-primary gap-2">
                            Avançar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>