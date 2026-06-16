<?php
// Buscar todos os fornecedores associados a este equipamento e agrupar por tipo usando a classe Fornecedor
$fornecedoresPorTipo = [
    'Fabricante' => [],
    'Distribuidor' => [],
    'Assistência Técnica' => [],
    'Consumíveis' => []
];

try {
    // A ligação à BD já deve estar aberta ($ligacao) a partir do detailed_view.php, mas caso não:
    if (!isset($ligacao)) {
        $ligacao = connect_to_db();
    }

    $stmtFornecedoresTab = execute_query(
        "SELECT f.* FROM Fornecedor f
         INNER JOIN FornecedorEquipamento fe ON f.idFornecedor = fe.idFornecedor
         WHERE fe.idEquipamento = :id AND f.ativo = 1 AND fe.ativo = 1
         ORDER BY f.nome ASC",
        ['id' => $id],
        $ligacao
    );

    while ($row = $stmtFornecedoresTab->fetch(PDO::FETCH_ASSOC)) {
        $fornecedor = new Fornecedor(
            (string) $row['idFornecedor'],
            $row['nome'],
            $row['nifFornecedor'] ?? '',
            $row['contactoTelefonico'] ?? '',
            $row['email'] ?? '',
            $row['website'] ?? '',
            $row['idPessoaResponsavel'],
            TipoFornecedor::from($row['tipoFornecedor']),
            (bool) $row['ativo'],
            new DateTime($row['dataCriacao']),
            new DateTime($row['dataAtualizacao'])
        );

        $tipo = $fornecedor->getTipoFornecedor()->value;
        if (isset($fornecedoresPorTipo[$tipo])) {
            $fornecedoresPorTipo[$tipo][] = $fornecedor;
        }
    }
} catch (Exception $e) {
    // Em caso de erro, os arrays permanecem vazios e os cartões exibirão "Não definido"
}

$cardsConfig = [
    [
        'tipo' => 'Fabricante',
        'titulo' => 'Fabricante',
        'iconClass' => 'manufacturer-icon-wrapper text-primary-500',
        'iconSvg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-factory-icon lucide-factory"><path d="M12 16h.01" /><path d="M16 16h.01" /><path d="M3 19a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5a.5.5 0 0 0-.769-.422l-4.462 2.844A.5.5 0 0 1 15 10.5v-2a.5.5 0 0 0-.769-.422L9.77 10.922A.5.5 0 0 1 9 10.5V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z" /><path d="M8 16h.01" /></svg>'
    ],
    [
        'tipo' => 'Distribuidor',
        'titulo' => 'Distribuidor',
        'iconClass' => 'distributor-icon-wrapper',
        'iconSvg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck-icon lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" /><path d="M15 18H9" /><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" /><circle cx="17" cy="18" r="2" /><circle cx="7" cy="18" r="2" /></svg>'
    ],
    [
        'tipo' => 'Assistência Técnica',
        'titulo' => 'Assistência Técnica',
        'iconClass' => 'support-icon-wrapper text-success',
        'iconSvg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-headset-icon lucide-headset"><path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0v5a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3Z" /><path d="M21 16v2a4 4 0 0 1-4 4h-5" /></svg>'
    ],
    [
        'tipo' => 'Consumíveis',
        'titulo' => 'Fornecedores de Consumíveis',
        'iconClass' => 'supplier-badge-consumable-supplier',
        'iconSvg' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>'
    ]
];
?>

<div class="tab-pane fade <?= $activeTab === 'fornecedores' ? 'show active' : '' ?>" id="nav-fornecedores"
    role="tabpanel" aria-labelledby="nav-fornecedores-tab">
    <div class="row g-4 w-100 m-0">
        <?php foreach ($cardsConfig as $config): ?>
            <div class="col-12 col-lg-6">
                <div class="card bento-card supplier-card padding-6 d-flex flex-column gap-4 h-100">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="table-icon-wrapper <?= $config['iconClass'] ?> padding-2">
                                <?= $config['iconSvg'] ?>
                            </div>
                            <h3 class="fw-700 m-0 text-primary"><?= $config['titulo'] ?></h3>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-4 flex-grow-1">
                        <?php $suppliers = $fornecedoresPorTipo[$config['tipo']]; ?>
                        <?php if (!empty($suppliers)): ?>
                            <?php foreach ($suppliers as $index => $sup): ?>
                                <?php if ($index > 0): ?>
                                    <hr class="m-0 text-secondary opacity-25">
                                <?php endif; ?>
                                <div class="d-flex flex-column gap-1">
                                    <h4 class="fw-700 text-primary m-0 fs-5"><?= htmlspecialchars($sup->getNome()) ?></h4>

                                    <div class="d-flex flex-column gap-1 mt-2">
                                        <?php if (!empty($sup->getNifFornecedor())): ?>
                                            <p class="text-secondary fw-500 m-0 d-flex align-items-center gap-2">
                                                <span class="text-muted fw-700">NIF</span>
                                                <?= htmlspecialchars($sup->getNifFornecedor()) ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($sup->getEmail())): ?>
                                            <p class="text-secondary fw-500 m-0 d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-mail text-muted">
                                                    <rect width="20" height="16" x="2" y="4" rx="2" />
                                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                                </svg>
                                                <a href="mailto:<?= htmlspecialchars($sup->getEmail()) ?>"
                                                    class="text-secondary text-decoration-none"><?= htmlspecialchars($sup->getEmail()) ?></a>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($sup->getContactoTelefonico())): ?>
                                            <p class="text-secondary fw-500 m-0 d-flex align-items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="lucide lucide-phone text-muted">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                <?= htmlspecialchars($sup->getContactoTelefonico()) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($sup->getWebsite())): ?>
                                        <a href="<?= htmlspecialchars($sup->getWebsite()) ?>" target="_blank"
                                            class="d-flex align-items-center gap-1 text-primary-500 text-decoration-none fw-500 mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-external-link">
                                                <path d="M15 3h6v6" />
                                                <path d="M10 14 21 3" />
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                            </svg>
                                            Website
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-secondary opacity-75 fw-500 m-0">Não definido</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>