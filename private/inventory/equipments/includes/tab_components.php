<?php
try {
    if (!isset($ligacao)) {
        $ligacao = connect_to_db();
    }

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
    $componentes_associados = [];
}
?>
<div class="tab-pane fade <?= $activeTab === 'componentes' ? 'show active' : '' ?>" id="nav-componentes" role="tabpanel" aria-labelledby="nav-componentes-tab">
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Componentes Associados</h2>
        </div>

        <?php if (empty($componentes_associados)): ?>
            <div class="d-flex flex-column align-items-center justify-content-center gap-2 py-5 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-puzzle text-muted opacity-50">
                    <path
                        d="M15.39 4.39a1 1 0 0 0 1.68-.474 2.5 2.5 0 1 1 3.014 3.015 1 1 0 0 0-.474 1.68l1.683 1.682a2.414 2.414 0 0 1 0 3.414L19.61 15.39a1 1 0 0 1-1.68-.474 2.5 2.5 0 1 0-3.014 3.015 1 1 0 0 1 .474 1.68l-1.683 1.682a2.414 2.414 0 0 1-3.414 0L8.61 19.61a1 1 0 0 0-1.68.474 2.5 2.5 0 1 1-3.014-3.015 1 1 0 0 0 .474-1.68l-1.683-1.682a2.414 2.414 0 0 1 0-3.414L4.39 8.61a1 1 0 0 1 1.68.474 2.5 2.5 0 1 0 3.014-3.015 1 1 0 0 1-.474-1.68l1.683-1.682a2.414 2.414 0 0 1 3.414 0z" />
                </svg>
                <span class="text-secondary fw-500">Sem componentes associados</span>
            </div>
        <?php else: ?>
            <table id="componentsTable" class="sibdas-table w-100 display border-0">
                <thead>
                    <tr>
                        <th>COMPONENTE</th>
                        <th>CATEGORIA</th>
                        <th>QTD. UTILIZADA</th>
                        <th>STOCK DISPONÍVEL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($componentes_associados as $comp): ?>
                        <tr>
                            <td>
                                <a href="../components.php"
                                    class="text-primary-500 fw-700 text-decoration-none hover-underline">
                                    <?= htmlspecialchars($comp['nome']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="text-secondary fw-400"><?= htmlspecialchars($comp['categoria']) ?></span>
                            </td>
                            <td>
                                <span class="fw-700 text-primary"><?= htmlspecialchars($comp['quantidade']) ?></span>
                            </td>
                            <td>
                                <span class="fw-700 text-primary"><?= htmlspecialchars($comp['stock']) ?> un.</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>