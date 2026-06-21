<div class="tab-pane fade <?= $activeTab === 'documentos' ? 'show active' : '' ?>" id="nav-documentos" role="tabpanel"
    aria-labelledby="nav-documentos-tab">
    <div class="d-flex flex-column gap-6 w-100">

        <?php if ($totalEmFalta > 0): ?>
            <!-- Card 1: Documentos em Falta -->
            <div class="card bento-card padding-6 d-flex flex-column gap-4">
                <div class="d-flex align-items-center gap-2 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-alert-circle">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <h2 class="fw-700 m-0 text-primary">Documentos em Falta (<?= $totalEmFalta ?> de <?= $totalTipos ?>)
                    </h2>
                </div>

                <div class="document-grid d-grid gap-4">
                    <?php foreach ($tiposEmFalta as $index => $tipoFalta): ?>
                        <div class="missing-doc-card d-flex align-items-center justify-content-between padding-4">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    class="missing-doc-icon-wrapper d-flex align-items-center justify-content-center text-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-file-text">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                        <path d="M10 9H8" />
                                        <path d="M16 13H8" />
                                        <path d="M16 17H8" />
                                    </svg>
                                </div>
                                <div class="d-flex flex-column gap-half">
                                    <p class="fw-700"><?= htmlspecialchars($tipoFalta->value) ?></p>
                                    <span class="fw-600 text-warning">Pendente</span>
                                </div>
                            </div>
                            <?php if (tem_permissao('documents.create')): ?>
                                <button
                                    class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                                    data-bs-toggle="modal" data-bs-target="#add-document-modal-<?= $index ?>"
                                    title="Adicionar <?= htmlspecialchars($tipoFalta->value) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-upload">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Modais de Adicionar (Em Falta) gerados logo a seguir ao cartão para manter a organização -->
            <?php if (tem_permissao('documents.create')): ?>
                <?php foreach ($tiposEmFalta as $index => $tipoFalta): ?>
                    <div class="modal fade" id="add-document-modal-<?= $index ?>" tabindex="-1"
                        aria-labelledby="addDocumentModalLabel<?= $index ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                            <div class="modal-content custom-modal-content d-flex flex-column">
                                <div
                                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                                        id="addDocumentModalLabel<?= $index ?>">
                                        Adicionar Documento</h2>
                                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                                        data-bs-dismiss="modal" aria-label="Close">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-x stroke-secondary">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="modal-body p-0">
                                    <form action="equipments-crud/create-document.php" method="POST" enctype="multipart/form-data"
                                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">

                                        <div class="d-flex flex-column form-item w-100">
                                            <div class="d-flex gap-1">
                                                <label for="doc-name-<?= $index ?>">Nome do Documento</label>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                                    <path d="M12 6v12" />
                                                    <path d="M17.196 9 6.804 15" />
                                                    <path d="m6.804 9 10.392 6" />
                                                </svg>
                                            </div>
                                            <input type="text" id="doc-name-<?= $index ?>" name="doc-name"
                                                placeholder="Ex: Manual de Utilizador V2" required>
                                        </div>

                                        <div class="d-flex flex-column form-item w-100">
                                            <div class="d-flex gap-1">
                                                <label for="doc-type-<?= $index ?>">Tipo</label>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                                    <path d="M12 6v12" />
                                                    <path d="M17.196 9 6.804 15" />
                                                    <path d="m6.804 9 10.392 6" />
                                                </svg>
                                            </div>
                                            <select id="doc-type-<?= $index ?>" name="doc-type" class="form-select w-100" required>
                                                <option value="" disabled>Selecionar tipo...</option>
                                                <?php foreach (TipoDocumento::cases() as $t): ?>
                                                    <option value="<?= htmlspecialchars($t->value) ?>" <?= $t->value === $tipoFalta->value ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($t->value) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="d-flex flex-column form-item w-100">
                                            <label for="doc-supplier-<?= $index ?>">Fornecedor Associado</label>
                                            <select id="doc-supplier-<?= $index ?>" name="doc-supplier" class="form-select w-100">
                                                <option value="" selected>Nenhum</option>
                                                <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                                    <option value="<?= htmlspecialchars($f['idFornecedor']) ?>">
                                                        <?= htmlspecialchars($f['nome']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                                            id="add-dropzone-<?= $index ?>" data-dropzone-target="doc-file-<?= $index ?>"
                                            data-text-target="add-dropzone-text-<?= $index ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-upload file-upload-icon">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="17 8 12 3 7 8" />
                                                <line x1="12" x2="12" y1="3" y2="15" />
                                            </svg>
                                            <p class="file-upload-text">Arraste ficheiros ou
                                                <span class="file-upload-text-action text-primary-500">clique para selecionar</span>
                                            </p>
                                            <span class="m-0 text-muted" id="add-dropzone-text-<?= $index ?>">PDF, JPG, PNG — máx.
                                                25MB</span>
                                            <input type="file" id="doc-file-<?= $index ?>" name="doc-file" class="d-none"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                        </div>

                                        <div class="d-flex justify-content-end gap-3 align-items-center mt-3">
                                            <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                                data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit"
                                                class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-check">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                                Guardar
                                            </button>
                                        </div>
                                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'doc-name-<?= $index ?>': 'Documento Preenchido'}); setTimeout(() => { document.getElementById('doc-name-<?= $index ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Preencher Nome</button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php endif; ?>

        <!-- Card 2: Documentos Associados -->
        <div class="card bento-card padding-6 d-flex flex-column gap-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-700 m-0 text-primary">Documentos Associados</h2>
                <?php if (tem_permissao('documents.create')): ?>
                    <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#add-document-modal-generic">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        <span>Adicionar</span>
                    </button>
                <?php endif; ?>
            </div>

            <?php if (count($documentos) === 0): ?>
                <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                    <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-file-x">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="m9 15 6-6" />
                            <path d="m9 9 6 6" />
                        </svg>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <h3 class="fw-700 m-0">Sem Documentos</h3>
                        <p class="text-secondary m-0">Ainda não existem documentos associados a este equipamento.</p>
                    </div>
                </div>
            <?php else: ?>
                <table id="documentsTable" class="sibdas-table w-100 display border-0">
                    <thead>
                        <tr>
                            <th>NOME</th>
                            <th>TIPO</th>
                            <th>DATA</th>
                            <th>FORNECEDOR</th>
                            <th class="text-end">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documentos as $doc): ?>
                            <tr>
                                <td>
                                    <span class="fw-700"><?= htmlspecialchars($doc->getNome()) ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary fw-400"><?= htmlspecialchars($doc->getTipo()->value) ?></span>
                                </td>
                                <td>
                                    <span
                                        class="text-secondary fw-400"><?= $doc->getDataDocumento() ? $doc->getDataDocumento()->format('d/m/Y') : '—' ?></span>
                                </td>
                                <td>
                                    <span
                                        class="text-secondary fw-400"><?= htmlspecialchars($doc->getFornecedorNome() ?? '—') ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-3 align-items-center">
                                        <?php if ($doc->getCaminhoFicheiro()): ?>
                                            <a href="<?= BASE_URL . htmlspecialchars($doc->getCaminhoFicheiro()) ?>" download
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                title="Download">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-download">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (tem_permissao('documents.edit')): ?>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Editar" data-bs-toggle="modal"
                                                data-bs-target="#edit-document-modal-<?= htmlspecialchars(aes_encrypt($doc->getIdDocumento())) ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-pencil">
                                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                    <path d="m15 5 4 4" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (tem_permissao('documents.delete')): ?>
                                            <button
                                                class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                type="button" title="Eliminar" data-bs-toggle="modal"
                                                data-bs-target="#delete-document-modal-<?= htmlspecialchars(aes_encrypt($doc->getIdDocumento())) ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-trash-2 text-secondary">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Modal Adicionar Genérico -->
<?php if (tem_permissao('documents.create')): ?>
    <div class="modal fade" id="add-document-modal-generic" tabindex="-1" aria-labelledby="addDocumentModalLabelGeneric"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="addDocumentModalLabelGeneric">
                        Adicionar Documento</h2>
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
                <div class="modal-body p-0">
                    <form action="equipments-crud/create-document.php" method="POST" enctype="multipart/form-data"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                        <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">

                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="doc-name-generic">Nome do Documento</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <input type="text" id="doc-name-generic" name="doc-name" placeholder="Ex: Manual de Utilizador"
                                required>
                        </div>

                        <div class="d-flex flex-column form-item w-100">
                            <div class="d-flex gap-1">
                                <label for="doc-type-generic">Tipo</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                    <path d="M12 6v12" />
                                    <path d="M17.196 9 6.804 15" />
                                    <path d="m6.804 9 10.392 6" />
                                </svg>
                            </div>
                            <select id="doc-type-generic" name="doc-type" class="form-select w-100" required>
                                <option value="" disabled selected>Selecionar tipo...</option>
                                <?php foreach (TipoDocumento::cases() as $t): ?>
                                    <option value="<?= htmlspecialchars($t->value) ?>">
                                        <?= htmlspecialchars($t->value) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex flex-column form-item w-100">
                            <label for="doc-supplier-generic">Fornecedor Associado</label>
                            <select id="doc-supplier-generic" name="doc-supplier" class="form-select w-100">
                                <option value="" selected>Nenhum</option>
                                <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                    <option value="<?= htmlspecialchars($f['idFornecedor']) ?>">
                                        <?= htmlspecialchars($f['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                            id="add-dropzone-generic" data-dropzone-target="doc-file-generic"
                            data-text-target="add-dropzone-text-generic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-upload file-upload-icon">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" x2="12" y1="3" y2="15" />
                            </svg>
                            <p class="file-upload-text">Arraste ficheiros ou
                                <span class="file-upload-text-action text-primary-500">clique para selecionar</span>
                            </p>
                            <span class="m-0 text-muted" id="add-dropzone-text-generic">PDF, JPG, PNG — máx. 25MB</span>
                            <input type="file" id="doc-file-generic" name="doc-file" class="d-none"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>

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
                        <?php if (SHOW_DEBUG_BUTTONS): ?>
                            <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'doc-name-generic': 'Documento Genérico Preenchido'}); setTimeout(() => { document.getElementById('doc-name-generic').dispatchEvent(new Event('input', { bubbles: true })); document.getElementById('doc-type-generic').selectedIndex = 1; document.getElementById('doc-type-generic').dispatchEvent(new Event('change', { bubbles: true })); }, 100);">Preencher Campos</button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($documentos as $doc): ?>
    <?php $encDocId = htmlspecialchars(aes_encrypt($doc->getIdDocumento())); ?>

    <?php if (tem_permissao('documents.edit')): ?>
        <!-- Modal Editar Documento -->
        <div class="modal fade" id="edit-document-modal-<?= $encDocId ?>" tabindex="-1"
            aria-labelledby="editDocumentModalLabel<?= $encDocId ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                            id="editDocumentModalLabel<?= $encDocId ?>">
                            Editar Documento</h2>
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
                    <div class="modal-body p-0">
                        <form action="equipments-crud/edit-document.php" method="POST"
                            class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <input type="hidden" name="document-id" value="<?= $encDocId ?>">

                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label for="edit-doc-name-<?= $encDocId ?>">Nome do Documento</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <input type="text" id="edit-doc-name-<?= $encDocId ?>" name="doc-name"
                                    value="<?= htmlspecialchars($doc->getNome()) ?>" required>
                            </div>

                            <div class="d-flex flex-column form-item w-100">
                                <div class="d-flex gap-1">
                                    <label for="edit-doc-type-<?= $encDocId ?>">Tipo</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                        <path d="M12 6v12" />
                                        <path d="M17.196 9 6.804 15" />
                                        <path d="m6.804 9 10.392 6" />
                                    </svg>
                                </div>
                                <select id="edit-doc-type-<?= $encDocId ?>" name="doc-type" class="form-select w-100" required>
                                    <?php foreach (TipoDocumento::cases() as $t): ?>
                                        <option value="<?= htmlspecialchars($t->value) ?>" <?= $t->value === $doc->getTipo()->value ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t->value) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100">
                                <label for="edit-doc-supplier-<?= $encDocId ?>">Fornecedor Associado</label>
                                <select id="edit-doc-supplier-<?= $encDocId ?>" name="doc-supplier" class="form-select w-100">
                                    <option value="" <?= !$doc->getIdFornecedor() ? 'selected' : '' ?>>Nenhum</option>
                                    <?php foreach ($fornecedoresDisponiveis as $f): ?>
                                        <option value="<?= htmlspecialchars($f['idFornecedor']) ?>"
                                            <?= $doc->getIdFornecedor() === (string) $f['idFornecedor'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($f['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

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
                            <?php if (SHOW_DEBUG_BUTTONS): ?>
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light mt-4">
                                    <span class="w-100 text-secondary fw-500" style="font-size: 12px;">Preenchimento Rápido (Debug)</span>
                                    <button type="button" class="btn btn-primary-outline btn-small fw-700 flex-grow-1" onclick="prefillFields({'edit-doc-name-<?= $encDocId ?>': 'Documento Editado'}); setTimeout(() => { document.getElementById('edit-doc-name-<?= $encDocId ?>').dispatchEvent(new Event('input', { bubbles: true })); }, 100);">Editar Nome</button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <?php if (tem_permissao('documents.delete')): ?>
        <!-- Modal Eliminar Documento -->
        <div class="modal fade" id="delete-document-modal-<?= $encDocId ?>" tabindex="-1"
            aria-labelledby="deleteDocumentModalLabel<?= $encDocId ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                                id="deleteDocumentModalLabel<?= $encDocId ?>">Eliminar Documento</h2>
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
                    <div class="modal-body p-0">
                        <form action="equipments-crud/delete-document.php" method="POST"
                            class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">
                            <input type="hidden" name="equipment-id" value="<?= htmlspecialchars($encryptedId) ?>">
                            <input type="hidden" name="document-id" value="<?= $encDocId ?>">

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
                                        <p class="text-secondary m-0">Tem a certeza que deseja apagar o documento</p>
                                        <h2 class="fw-700 text-primary m-0">"<?= htmlspecialchars($doc->getNome()) ?>"</h2>
                                    </div>
                                    <div class="danger-banner text-error text-center padding-3">
                                        <span>⚠️ O ficheiro será movido para o arquivo. Esta ação pode afetar relatórios.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex w-100 justify-content-end gap-4 button-row">
                                <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger btn-glowing text-white">Sim, Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>