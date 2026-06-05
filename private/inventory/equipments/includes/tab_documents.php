<div class="tab-pane fade" id="nav-documentos" role="tabpanel" aria-labelledby="nav-documentos-tab">
    <div class="d-flex flex-column gap-6 w-100">

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
                <h2 class="fw-700 m-0 text-primary">Documentos em Falta (5 de 7)</h2>
            </div>

            <div class="document-grid d-grid gap-4">
                <!-- Item 1: Manual de Utilizador -->
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
                            <p class="fw-700">Manual de Utilizador</p>
                            <span class="fw-600 text-warning">Pendente</span>
                        </div>
                    </div>
                    <button
                        class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                        data-bs-toggle="modal" data-bs-target="#add-document-modal"
                        data-doc-name="Manual de Utilizador">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </button>
                </div>

                <!-- Item 2: Certificado CE -->
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
                            <p class="fw-700">Certificado CE</p>
                            <span class="fw-600 text-warning">Pendente</span>
                        </div>
                    </div>
                    <button
                        class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                        data-bs-toggle="modal" data-bs-target="#add-document-modal" data-doc-name="Certificado CE">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </button>
                </div>

                <!-- Item 3: Certificado de Calibração -->
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
                            <p class="fw-700">Certificado de Calibração</p>
                            <span class="fw-600 text-warning">Pendente</span>
                        </div>
                    </div>
                    <button
                        class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                        data-bs-toggle="modal" data-bs-target="#add-document-modal"
                        data-doc-name="Certificado de Calibração">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </button>
                </div>

                <!-- Item 4: Contrato de Manutenção -->
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
                            <p class="fw-700">Contrato de Manutenção</p>
                            <span class="fw-600 text-warning">Pendente</span>
                        </div>
                    </div>
                    <button
                        class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                        data-bs-toggle="modal" data-bs-target="#add-document-modal"
                        data-doc-name="Contrato de Manutenção">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </button>
                </div>

                <!-- Item 5: Ficha de Segurança / Risco -->
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
                            <p class="fw-700">Ficha de Segurança / Risco</p>
                            <span class="fw-600 text-warning">Pendente</span>
                        </div>
                    </div>
                    <button
                        class="btn p-0 border-0 bg-transparent text-warning opacity-75 hover-opacity-100 transition-opacity"
                        data-bs-toggle="modal" data-bs-target="#add-document-modal"
                        data-doc-name="Ficha de Segurança / Risco">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-upload">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Card 2: Documentos Associados -->
        <div class="card bento-card padding-6 d-flex flex-column gap-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-700 m-0 text-primary">Documentos Associados</h2>
                <button class="btn btn-primary-outline d-flex align-items-center gap-2" data-bs-toggle="modal"
                    data-bs-target="#add-document-modal" data-doc-name="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    <span>Adicionar</span>
                </button>
            </div>

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
                    <tr>
                        <td>
                            <span class="fw-700">Manual Técnico</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">Manual Técnico / Serviço</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">10/01/2023</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">Philips Iberica, S.A.</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-3 align-items-center">
                                <a href="#"
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
                                <button
                                    class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                    type="button" title="Editar" data-bs-toggle="modal"
                                    data-bs-target="#edit-document-modal" data-doc-id="1" data-doc-name="Manual Técnico"
                                    data-doc-type="Manual Técnico / Serviço" data-doc-supplier="Philips Iberica, S.A.">
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
                                    data-bs-target="#delete-document-modal" data-doc-id="1"
                                    data-doc-name="Manual Técnico">
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
                    <tr>
                        <td>
                            <span class="fw-700">Guia de Instalação</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">Relatório de Instalação</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">10/01/2023</span>
                        </td>
                        <td>
                            <span class="text-secondary fw-400">Philips Iberica, S.A.</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-3 align-items-center">
                                <a href="#"
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
                                <button
                                    class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                    type="button" title="Editar" data-bs-toggle="modal"
                                    data-bs-target="#edit-document-modal" data-doc-id="2"
                                    data-doc-name="Guia de Instalação" data-doc-type="Relatório de Instalação"
                                    data-doc-supplier="Philips Iberica, S.A.">
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
                                    data-bs-target="#delete-document-modal" data-doc-id="2"
                                    data-doc-name="Guia de Instalação">
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
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Adicionar Documento -->
<div class="modal fade" id="add-document-modal" tabindex="-1" aria-labelledby="addDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="addDocumentModalLabel">
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

            <!-- Body -->
            <div class="modal-body p-0">
                <form id="add-document-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Nome do Documento -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="doc-name">Nome do Documento</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="text" id="doc-name" name="doc-name" placeholder="Ex: Manual de Utilizador"
                            required>
                    </div>

                    <!-- Tipo -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="doc-type">Tipo</label>
                        <select id="doc-type" name="doc-type" class="form-select w-100">
                            <option value="" disabled selected>Selecionar tipo...</option>
                            <option value="Manual de Utilizador">Manual de Utilizador</option>
                            <option value="Certificado CE">Certificado CE</option>
                            <option value="Certificado de Calibração">Certificado de Calibração</option>
                            <option value="Contrato de Manutenção">Contrato de Manutenção</option>
                            <option value="Ficha de Segurança / Risco">Ficha de Segurança / Risco</option>
                            <option value="Manual Técnico / Serviço">Manual Técnico / Serviço</option>
                            <option value="Relatório de Instalação">Relatório de Instalação</option>
                        </select>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="doc-supplier">Fornecedor Associado</label>
                        <select id="doc-supplier" name="doc-supplier" class="form-select w-100">
                            <option value="Nenhum" selected>Nenhum</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                        </select>
                    </div>

                    <!-- File Upload Zone -->
                    <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                        id="add-dropzone">
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
                        <span class="m-0 text-muted" id="add-dropzone-text">PDF, JPG, PNG — máx. 10MB</span>
                        <input type="file" id="doc-file" name="doc-file" class="d-none" accept=".pdf,.jpg,.jpeg,.png"
                            required>
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
                            <span>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Editar Documento -->
<div class="modal fade" id="edit-document-modal" tabindex="-1" aria-labelledby="editDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Header -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary" id="editDocumentModalLabel">
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

            <!-- Body -->
            <div class="modal-body p-0">
                <form id="edit-document-form"
                    class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                    <!-- Nome do Documento -->
                    <div class="d-flex flex-column form-item w-100">
                        <div class="d-flex gap-1">
                            <label for="edit-doc-name">Nome do Documento</label>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-asterisk text-error">
                                <path d="M12 6v12" />
                                <path d="M17.196 9 6.804 15" />
                                <path d="m6.804 9 10.392 6" />
                            </svg>
                        </div>
                        <input type="text" id="edit-doc-name" name="doc-name" placeholder="Ex: Manual de Utilizador"
                            required>
                    </div>

                    <!-- Tipo -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-doc-type">Tipo</label>
                        <select id="edit-doc-type" name="doc-type" class="form-select w-100">
                            <option value="" disabled>Selecionar tipo...</option>
                            <option value="Manual de Utilizador">Manual de Utilizador</option>
                            <option value="Certificado CE">Certificado CE</option>
                            <option value="Certificado de Calibração">Certificado de Calibração</option>
                            <option value="Contrato de Manutenção">Contrato de Manutenção</option>
                            <option value="Ficha de Segurança / Risco">Ficha de Segurança / Risco</option>
                            <option value="Manual Técnico / Serviço">Manual Técnico / Serviço</option>
                            <option value="Relatório de Instalação">Relatório de Instalação</option>
                        </select>
                    </div>

                    <!-- Fornecedor Associado -->
                    <div class="d-flex flex-column form-item w-100">
                        <label for="edit-doc-supplier">Fornecedor Associado</label>
                        <select id="edit-doc-supplier" name="doc-supplier" class="form-select w-100">
                            <option value="Nenhum">Nenhum</option>
                            <option value="Dräger Portugal, Lda.">Dräger Portugal, Lda.</option>
                            <option value="Philips Iberica, S.A.">Philips Iberica, S.A.</option>
                            <option value="Siemens Healthineers">Siemens Healthineers</option>
                        </select>
                    </div>

                    <!-- File Upload Zone -->
                    <div class="file-upload-zone d-flex flex-column align-items-center justify-content-center gap-2"
                        id="edit-dropzone">
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
                        <span class="m-0 text-muted" id="edit-dropzone-text">PDF, JPG, PNG — máx. 10MB</span>
                        <input type="file" id="edit-doc-file" name="doc-file" class="d-none"
                            accept=".pdf,.jpg,.jpeg,.png">
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
                            <span>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Remoção de Documento -->
<div class="modal fade" id="delete-document-modal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
        <div class="modal-content custom-modal-content d-flex flex-column">
            <!-- Titulo -->
            <div
                class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                <div class="d-flex flex-column">
                    <h2 class="equipment-creation-modal-title modal-title fw-700 text-primary"
                        id="deleteDocumentModalLabel">Eliminar Documento</h2>
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
                                <p class="text-secondary m-0">Tem a certeza que deseja apagar permanentemente o
                                    documento</p>
                                <h2 class="fw-700 text-primary m-0" id="delete-doc-display-name">"Manual Técnico"</h2>
                            </div>
                            <div class="danger-banner text-error text-center padding-3">
                                <span>⚠️ Este ficheiro será eliminado permanentemente. Todos os dados associados serão
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