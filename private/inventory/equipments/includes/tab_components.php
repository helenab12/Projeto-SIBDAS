<?php
try {
    // Ligar à BD
    if (!isset($ligacao)) {
        $ligacao = connect_to_db();
    }

    // Consultar componentes
    $stmtComponentes = execute_query(
        "SELECT c.idComponente, c.descricao AS nome, c.stock, ce.quantidade, cat.nome AS categoriaNome
         FROM ComponenteEquipamento ce
         INNER JOIN Componente c ON ce.idComponente = c.idComponente
         LEFT JOIN ComponenteCategoria cc ON c.idComponente = cc.idComponente
         LEFT JOIN CategoriaEquipamento cat ON cc.idCategoria = cat.idCategoria
         WHERE ce.idEquipamento = :id AND c.ativo = 1
         ORDER BY c.descricao ASC",
        ['id' => $id],
        $ligacao
    );

    // Processar resultados
    $componentes_associados = [];
    while ($row = $stmtComponentes->fetch(PDO::FETCH_ASSOC)) {
        $componentes_associados[] = [
            'id' => $row['idComponente'],
            'nome' => $row['nome'],
            'categoria' => $row['categoriaNome'] ?? 'Sem Categoria',
            'quantidade' => $row['quantidade'],
            'stock' => $row['stock']
        ];
    }
} catch (Exception $e) {
    // Capturar erro
    $componentes_associados = [];
}
?>
<!-- Tab Componentes -->
<div class="tab-pane fade <?= $activeTab === 'componentes' ? 'show active' : '' ?>" id="nav-componentes" role="tabpanel"
    aria-labelledby="nav-componentes-tab">
    <!-- Card Componentes -->
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- Título -->
            <h2 class="fw-700 m-0 text-primary">Componentes Associados</h2>
        </div>

        <?php if (empty($componentes_associados)): ?>
            <!-- Estado Vazio -->
            <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                <!-- Wrapper Ícone -->
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <!-- SVG Puzzle -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-off-icon lucide-bell-off">
                        <path d="M9 10h.01" />
                        <path d="M15 10h.01" />
                        <path d="M12 2a8 8 0 0 0-8 8v12l3-3 2.5 2.5L12 19l2.5 2.5L17 19l3 3V10a8 8 0 0 0-8-8z" />
                    </svg>
                </div>
                <!-- Conteúdo -->
                <div class="d-flex flex-column gap-2">
                    <!-- Título -->
                    <h3 class="fw-700 m-0">Sem Componentes</h3>
                    <!-- Texto -->
                    <p class="text-secondary m-0">Ainda não existem componentes associados a este equipamento.</p>
                </div>
            </div>
        <?php else: ?>
            <!-- Tabela Componentes -->
            <div class="w-100 overflow-auto">
                <table id="componentsTable" class="heba-table w-100 display border-0">
                <!-- Cabeçalho -->
                <thead>
                    <tr>
                        <!-- Coluna Componente -->
                        <th>COMPONENTE</th>
                        <!-- Coluna Categoria -->
                        <th>CATEGORIA</th>
                        <!-- Coluna Qtd -->
                        <th>QTD. UTILIZADA</th>
                        <!-- Coluna Stock -->
                        <th>STOCK DISPONÍVEL</th>
                    </tr>
                </thead>
                <!-- Corpo da Tabela -->
                <tbody>
                    <?php foreach ($componentes_associados as $comp): ?>
                        <!-- Linha Componente -->
                        <tr>
                            <!-- Componente -->
                            <td>
                                <!-- Link Componente -->
                                <a href="../components.php?search=<?= urlencode($comp['nome']) ?>"
                                    class="text-primary-500 fw-700 text-decoration-none hover-underline">
                                    <?= htmlspecialchars($comp['nome']) ?>
                                </a>
                            </td>
                            <!-- Categoria -->
                            <td>
                                <!-- Texto -->
                                <span class="text-secondary fw-400"><?= htmlspecialchars($comp['categoria']) ?></span>
                            </td>
                            <!-- Quantidade -->
                            <td>
                                <!-- Texto -->
                                <span class="fw-700 text-primary"><?= htmlspecialchars($comp['quantidade']) ?></span>
                            </td>
                            <!-- Stock -->
                            <td>
                                <!-- Texto -->
                                <span class="fw-700 text-primary"><?= htmlspecialchars($comp['stock']) ?> un.</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>