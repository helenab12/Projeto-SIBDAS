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
<div class="tab-pane fade <?= $activeTab === 'componentes' ? 'show active' : '' ?>" id="nav-componentes" role="tabpanel"
    aria-labelledby="nav-componentes-tab">
    <div class="card bento-card padding-6 d-flex flex-column gap-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-700 m-0 text-primary">Componentes Associados</h2>
        </div>

        <?php if (empty($componentes_associados)): ?>
            <div class="padding-6 d-flex flex-column align-items-center justify-content-center text-center gap-4 w-100">
                <div class="d-flex align-items-center justify-content-center text-secondary opacity-50 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-puzzle">
                        <path
                            d="M19.439 7.85c-.049.322.059.648.289.878l1.568 1.568c.47.47.706 1.087.706 1.704s-.235 1.233-.706 1.704l-1.611 1.611a.954.954 0 0 0-.288.877l.154 1.011c.176 1.157-.22 2.339-1.077 3.196-.857.857-2.039 1.253-3.196 1.077l-1.011-.154a.954.954 0 0 0-.877.288l-1.611 1.611c-.47.47-1.087.706-1.704.706s-1.233-.235-1.704-.706l-1.568-1.568a.954.954 0 0 0-.878-.289l-1.011.154c-1.157.176-2.339-.22-3.196-1.077-.857-.857-1.253-2.039-1.077-3.196l.154-1.011a.954.954 0 0 0-.288-.877l-1.611-1.611c-.47-.47-.706-1.087-.706-1.704s.235-1.233.706-1.704l1.568-1.568c.23-.23.338-.556.289-.878l-.154-1.011c-.176-1.157.22-2.339 1.077-3.196.857-.857 2.039-1.253 3.196-1.077l1.011.154c.322.049.648-.059.878-.289l1.568-1.568c.47-.47 1.087-.706 1.704-.706s1.233.235 1.704.706l1.611 1.611c.23.23.556.338.877.288l1.011-.154c1.157-.176 2.339.22 3.196 1.077.857.857 1.253 2.039 1.077 3.196l-.154 1.011Z" />
                    </svg>
                </div>
                <div class="d-flex flex-column gap-2">
                    <h3 class="fw-700 m-0">Sem Componentes</h3>
                    <p class="text-secondary m-0">Ainda não existem componentes associados a este equipamento.</p>
                </div>
            </div>
        <?php else: ?>
            <table id="componentsTable" class="heba-table w-100 display border-0">
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
                                <a href="../components.php?search=<?= urlencode($comp['nome']) ?>"
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