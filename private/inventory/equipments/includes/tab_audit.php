<?php
$auditoria = [
    [
        'id' => 1,
        'data' => '07/04/2026, 13:45:00',
        'acao' => 'Atualização',
        'utilizador' => 'Dr. Manuel Costa',
        'detalhes' => 'Campo atualizado de 2025-09-20 para 2025-11-20'
    ]
];
?>
<div class="tab-pane fade" id="nav-auditoria" role="tabpanel" aria-labelledby="nav-auditoria-tab">
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Histórico de Auditoria</h2>
        </div>

        <?php if (empty($auditoria)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 py-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-file-text text-muted opacity-50">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <path d="M10 9h8" />
                    <path d="M10 13h8" />
                    <path d="M10 17h8" />
                </svg>
                <span class="text-secondary fw-500">Sem registos de auditoria para este equipamento</span>
            </div>
        <?php else: ?>
            <table id="auditTable" class="sibdas-table w-100 display border-0">
                <thead>
                    <tr>
                        <th>DATA</th>
                        <th>AÇÃO</th>
                        <th>UTILIZADOR</th>
                        <th>DETALHES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditoria as $item): ?>
                        <tr>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['data']) ?></span>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?= htmlspecialchars($item['acao']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['utilizador']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($item['detalhes']) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>