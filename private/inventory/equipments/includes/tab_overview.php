<div class="tab-pane fade show active" id="nav-visao-geral" role="tabpanel" aria-labelledby="nav-visao-geral-tab">
    <div class="visao-geral-container d-flex gap-4 w-100">
        <!-- Detalhes do Equipamento -->
        <div class="card bento-card details-card padding-6 d-flex flex-column gap-4">
            <h2 class="fw-700 text-primary">Detalhes do Equipamento</h2>

            <div class="d-flex flex-column gap-6">
                <div class="d-flex">
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Número de Série</label>
                        <p class="fw-700 text-primary"><?= $serialNumber ?></p>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Categoria</label>
                        <p class="fw-700 text-primary"><?= $category ?></p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Data de Compra</label>
                        <p class="fw-700 text-primary"><?= $purchaseDate ?></p>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Fornecedor Principal</label>
                        <p class="fw-700 text-primary"><?= $supplier ?></p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Última Manutenção</label>
                        <p class="fw-700 text-primary"><?= $lastMaintenance ?></p>
                    </div>
                    <div class="col-6 d-flex flex-column gap-1">
                        <label class="text-secondary fw-500 opacity-75">Próxima Manutenção</label>
                        <p class="fw-700 text-primary"><?= $nextMaintenance ?></p>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column gap-1 detailed-view-divider">
                <label class="text-secondary fw-500 opacity-75">Notas</label>
                <p class="text-secondary fw-500"><?= $notes ?></p>
            </div>
        </div>

        <!-- Estado da Garantia -->
        <div class="card bento-card warranty-card padding-6 d-flex flex-column gap-4">
            <h2 class="fw-700 text-primary">Estado da Garantia</h2>

            <?php if ($isExpired): ?>
                <div
                    class="warranty-banner expired padding-5 d-flex flex-column align-items-center justify-content-center text-error gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-alert-triangle">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    <p class="fw-700 m-0">Garantia Expirada</p>
                </div>
            <?php else: ?>
                <div
                    class="warranty-banner active padding-5 d-flex flex-column align-items-center justify-content-center text-error gap-2">
                    <h1 class="fw-700 text-primary section-title"><?= $daysRemaining ?></h1>
                    <p class="fw-500 text-primary">dias restantes</p>
                </div>
            <?php endif; ?>

            <div class="d-flex flex-column gap-1">
                <label class="text-secondary fw-500 opacity-75">Data de Expiração</label>
                <p class="fw-700 text-primary"><?= $warrantyExpirationDate ?></p>
            </div>
        </div>
    </div>
</div>