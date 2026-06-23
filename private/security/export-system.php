<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Carregar dependências
require_once BASE_PATH . 'vendor/fpdf/fpdf.php';

// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['security.backups']);

$format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'csv';
$allowedFormats = ['csv', 'json', 'pdf'];

if (!in_array($format, $allowedFormats)) {
    die("Formato inválido.");
}

try {
    // Ligar à BD
$ligacao = connect_to_db();

    // 1. Equipamentos
    $sqlEquipamentos = "SELECT e.*, m.nome as marcaNome, c.nome as categoriaNome,
                        l.nomeSala, s.nome as servicoNome
                        FROM Equipamento e
                        LEFT JOIN Marca m ON e.idMarca = m.idMarca
                        LEFT JOIN CategoriaEquipamento c ON e.idCategoria = c.idCategoria
                        LEFT JOIN Localizacao l ON e.idLocalizacao = l.idLocalizacao
                        LEFT JOIN Servico s ON l.idServico = s.idServico";
    $equipamentosRaw = execute_query($sqlEquipamentos, [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // Detalhes dos Equipamentos (para aninhar no JSON)
    $documentos = execute_query("SELECT * FROM Documento", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);
    $manutencoes = execute_query("SELECT * FROM Manutencao", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);
    $garantias = execute_query("SELECT * FROM GarantiaContrato", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    $equipamentos = [];
    foreach ($equipamentosRaw as $eq) {
        $eqId = $eq['idEquipamento'];
        if ($format === 'json') {
            $eq['documentos'] = array_filter($documentos, fn($d) => $d['idEquipamento'] == $eqId);
            $eq['manutencoes'] = array_filter($manutencoes, fn($m) => $m['idEquipamento'] == $eqId);
            $eq['garantias_contratos'] = array_filter($garantias, fn($g) => $g['idEquipamento'] == $eqId);
        }
        $equipamentos[] = $eq;
    }

    // 2. Perfis e Permissões
    $perfisRaw = execute_query("SELECT * FROM Perfil", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);
    $perfis = [];
    foreach ($perfisRaw as $p) {
        $idPerfil = $p['idPerfil'];
        $sqlPerm = "SELECT p.chave FROM Permissao p JOIN PerfilPermissao pp ON p.idPermissao = pp.idPermissao WHERE pp.idPerfil = ? AND pp.possui = 1";
        $perms = execute_query($sqlPerm, [$idPerfil], $ligacao)->fetchAll(PDO::FETCH_COLUMN);
        $p['permissoes'] = $perms;
        $perfis[] = $p;
    }

    // 3. Utilizadores e Pessoas
    $sqlUtilizadores = "SELECT u.idUtilizador, u.emailAutenticacao, u.ativo, p.nome as perfilNome,
                        pe.nome as pessoaNome, pe.funcao, pe.nif, pe.contactoTelefonico
                        FROM Utilizador u
                        LEFT JOIN Perfil p ON u.idPerfil = p.idPerfil
                        LEFT JOIN Pessoa pe ON u.idPessoa = pe.idPessoa";
    $utilizadores = execute_query($sqlUtilizadores, [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fornecedores
    $fornecedores = execute_query("SELECT * FROM Fornecedor", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 5. Auditoria
    $sqlAuditoria = "SELECT h.*, u.emailAutenticacao as utilizadorNome
                     FROM HistoricoAuditoria h
                     LEFT JOIN Utilizador u ON h.idUtilizador = u.idUtilizador
                     ORDER BY h.dataCriacao DESC";
    $auditoria = execute_query($sqlAuditoria, [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 6. Componentes
    $sqlComponentes = "SELECT c.*, l.nomeSala FROM Componente c LEFT JOIN Localizacao l ON c.idLocalizacao = l.idLocalizacao";
    $componentes = execute_query($sqlComponentes, [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 7. Localizacoes
    $sqlLocalizacoes = "SELECT l.idLocalizacao, l.nomeSala, s.nome as servico, p.nome as piso, e.nome as edificio
                        FROM Localizacao l
                        LEFT JOIN Servico s ON l.idServico = s.idServico
                        LEFT JOIN Piso p ON s.idPiso = p.idPiso
                        LEFT JOIN Edificio e ON p.idEdificio = e.idEdificio";
    $localizacoes = execute_query($sqlLocalizacoes, [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 8. Categorias
    $categorias = execute_query("SELECT * FROM CategoriaEquipamento", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 9. Pedidos Demonstracao
    $pedidos = execute_query("SELECT * FROM PedidoDemonstracao", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // 10. Conteudos
    $conteudos = execute_query("SELECT * FROM ConteudoFrontOffice", [], $ligacao)->fetchAll(PDO::FETCH_ASSOC);

    // Junta tudo num array mestre
    $systemData = [
        'equipamentos' => $equipamentos,
        'perfis' => $perfis,
        'utilizadores' => $utilizadores,
        'fornecedores' => $fornecedores,
        'auditoria' => $auditoria,
        'componentes' => $componentes,
        'localizacoes' => $localizacoes,
        'categorias' => $categorias,
        'pedidos_demonstracao' => $pedidos,
        'gestao_conteudos' => $conteudos
    ];

    if ($format === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=heba_export_' . date('Ymd_His') . '.json');
        echo json_encode($systemData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $tempDir = BASE_PATH . 'files/exports';
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    $zipFile = $tempDir . '/heba_export_' . date('Ymd_His') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        die("Erro ao criar ficheiro ZIP em: " . $zipFile);
    }

    if ($format === 'csv') {
        foreach ($systemData as $tableName => $dataArray) {
            if (empty($dataArray))
                continue;

            $tempCsv = $tempDir . '/' . $tableName . '.csv';
            $output = fopen($tempCsv, 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8 para Excel

            if ($tableName === 'perfis') {
                fputcsv($output, ['idPerfil', 'nome', 'ativo', 'dataCriacao', 'dataAtualizacao', 'permissoes'], ';');
                foreach ($dataArray as $row) {
                    $row['permissoes'] = implode(', ', $row['permissoes']);
                    fputcsv($output, $row, ';');
                }
            } else {
                $firstRow = reset($dataArray);
                $headers = [];
                foreach ($firstRow as $key => $val) {
                    if (!is_array($val))
                        $headers[] = $key;
                }
                fputcsv($output, $headers, ';');

                foreach ($dataArray as $row) {
                    $csvRow = [];
                    foreach ($row as $key => $val) {
                        if (!is_array($val))
                            $csvRow[] = $val;
                    }
                    fputcsv($output, $csvRow, ';');
                }
            }
            fclose($output);
            $zip->addFile($tempCsv, "export_{$tableName}.csv");
        }
    } elseif ($format === 'pdf') {
        class PDF_Export extends FPDF
        {
            public $titleText = '';
            function Header()
            {
                if (file_exists(BASE_PATH . 'assets/img/logo.png')) {
                    $this->Image(BASE_PATH . 'assets/img/logo.png', 10, 8, 18);
                }
                $this->SetFont('Arial', 'B', 15);
                $this->Cell(80);
                $this->Cell(120, 10, utf8_decode($this->titleText), 0, 0, 'C');
                $this->Ln(20);
            }
            function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
            }
        }

        // Criar PDF principal da Dashboard
        $pdfDash = new PDF_Export('P', 'mm', 'A4');
        $pdfDash->titleText = 'Dashboard HEBA';
        $pdfDash->AliasNbPages();
        $pdfDash->AddPage();

        // Calcular Métricas usando o helper real da dashboard
        // Carregar dependências
        require_once BASE_PATH . 'private/includes/dashboard_stats.php';
        
        $totalEq = $dashboardStats['totalEquipamentos']['count'] ?? 0;
        $eqAtivos = $dashboardStats['equipamentosAtivos']['count'] ?? 0;
        $eqManutencao = $dashboardStats['emManutencao']['count'] ?? 0;
        $eqInativos = $dashboardStats['equipamentosInativos']['count'] ?? 0;
        $eqCriticidadeAlta = $dashboardStats['criticidadeElevada']['count'] ?? 0;
        $semDocs = $dashboardStats['semDocumentos']['count'] ?? 0;
        $garantiasAExpirar = $dashboardStats['garantiasAExpirar']['count'] ?? 0;
        $garantiasExpiradas = $dashboardStats['garantiasExpiradas']['count'] ?? 0;

        // Seção 1: Métricas Principais
        $pdfDash->SetFont('Arial', 'B', 14);
        $pdfDash->Cell(0, 10, utf8_decode("Métricas Principais"), 0, 1, 'L');
        $pdfDash->SetFont('Arial', '', 11);
        $pdfDash->Cell(100, 8, utf8_decode("- Total de Equipamentos: $totalEq"), 0, 0);
        $pdfDash->Cell(90, 8, utf8_decode("- Equipamentos Ativos: $eqAtivos"), 0, 1);
        $pdfDash->Cell(100, 8, utf8_decode("- Em Manutenção: $eqManutencao"), 0, 0);
        $pdfDash->Cell(90, 8, utf8_decode("- Equipamentos Inativos: $eqInativos"), 0, 1);
        $pdfDash->Cell(100, 8, utf8_decode("- Criticidade Elevada (Alta/Crítico): $eqCriticidadeAlta"), 0, 0);
        $pdfDash->Cell(90, 8, utf8_decode("- Equipamentos s/ Documentos: $semDocs"), 0, 1);
        $pdfDash->Ln(5);

        // Seção 2: Alertas (Garantias)
        $pdfDash->SetFont('Arial', 'B', 14);
        $pdfDash->Cell(0, 10, utf8_decode("Garantias e Contratos"), 0, 1, 'L');
        $pdfDash->SetFont('Arial', '', 11);
        $pdfDash->SetTextColor(200, 0, 0); // Red text
        $pdfDash->Cell(100, 8, utf8_decode("! Garantias a Expirar (< 30 dias): $garantiasAExpirar"), 0, 0);
        $pdfDash->Cell(90, 8, utf8_decode("! Garantias Expiradas: $garantiasExpiradas"), 0, 1);
        $pdfDash->SetTextColor(0, 0, 0); // Reset text color
        $pdfDash->Ln(5);

        // Seção 3: Distribuição por Categoria
        $pdfDash->SetFont('Arial', 'B', 12);
        $pdfDash->Cell(0, 8, utf8_decode("Distribuição por Categoria"), 0, 1, 'L');
        $pdfDash->SetFont('Arial', '', 10);
        if (isset($dashboardStats['graficoCategoria'])) {
            $catLabels = $dashboardStats['graficoCategoria']['labels'];
            $catData = $dashboardStats['graficoCategoria']['data'];
            for ($j = 0; $j < count($catLabels); $j++) {
                $nome = $catLabels[$j] ?: 'Sem Categoria';
                $qtd = $catData[$j];
                $pdfDash->Cell(90, 6, utf8_decode("- $nome: $qtd"), 0, 1);
            }
        }
        $pdfDash->Ln(5);

        // Seção 4: Equipamentos por Serviço
        $pdfDash->SetFont('Arial', 'B', 12);
        $pdfDash->Cell(0, 8, utf8_decode("Equipamentos por Serviço"), 0, 1, 'L');
        $pdfDash->SetFont('Arial', '', 10);
        if (isset($dashboardStats['graficoServico'])) {
            $servLabels = $dashboardStats['graficoServico']['labels'];
            $servData = $dashboardStats['graficoServico']['data'];
            $i = 0;
            for ($j = 0; $j < count($servLabels); $j++) {
                $nome = $servLabels[$j] ?: 'Sem Serviço';
                $qtd = $servData[$j];
                $pdfDash->Cell(95, 6, utf8_decode("- $nome: $qtd"), 0, 0);
                $i++;
                if ($i % 2 == 0) $pdfDash->Ln();
            }
            if ($i % 2 != 0) $pdfDash->Ln();
        }
        $pdfDash->Ln(5);

        // Seção 5: Tendências de Manutenção (Últimos 12 meses)
        $pdfDash->SetFont('Arial', 'B', 12);
        $pdfDash->Cell(0, 8, utf8_decode("Tendências de Manutenção (Últimos 12 Meses)"), 0, 1, 'L');
        $pdfDash->SetFont('Arial', '', 10);
        if (isset($dashboardStats['graficoManutencao'])) {
            $manLabels = $dashboardStats['graficoManutencao']['labels'];
            $manPrev = $dashboardStats['graficoManutencao']['preventiva'];
            $manCorr = $dashboardStats['graficoManutencao']['corretiva'];
            
            $totalPrev = array_sum($manPrev);
            $totalCorr = array_sum($manCorr);
            $pdfDash->Cell(90, 6, utf8_decode("- Preventivas: $totalPrev"), 0, 1);
            $pdfDash->Cell(90, 6, utf8_decode("- Corretivas: $totalCorr"), 0, 1);
        }
        $pdfDash->Ln(10);

        // Rodapé
        $pdfDash->SetFont('Arial', 'I', 9);
        $pdfDash->Cell(0, 10, utf8_decode("Relatório gerado automaticamente em: " . date('d/m/Y H:i')), 0, 1, 'C');

        $tempPdfDash = $tempDir . '/dashboard.pdf';
        $pdfDash->Output('F', $tempPdfDash);
        $zip->addFile($tempPdfDash, '00_dashboard_resumo.pdf');

        // Criar PDFs individuais para cada tabela
        foreach ($systemData as $tableName => $dataArray) {
            if (empty($dataArray))
                continue;

            $pdf = new PDF_Export('L', 'mm', 'A4'); // Paisagem para caber colunas
            $pdf->titleText = 'Relatorio: ' . ucfirst($tableName);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 8);

            // Obter chaves ignorando sub-arrays
            $firstRow = reset($dataArray);
            $headers = [];
            foreach ($firstRow as $key => $val) {
                if (!is_array($val))
                    $headers[] = $key;
            }

            // Limitar a 8 colunas para não partir a tabela no PDF
            $headers = array_slice($headers, 0, 8);
            $colWidth = count($headers) > 0 ? (277 / count($headers)) : 0;

            foreach ($headers as $h) {
                $pdf->Cell($colWidth, 7, utf8_decode(substr(ucfirst($h), 0, 15)), 1, 0, 'C');
            }
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 7);
            foreach ($dataArray as $row) {
                foreach ($headers as $h) {
                    $val = isset($row[$h]) ? $row[$h] : '';
                    if (is_array($val))
                        $val = implode(', ', $val);
                    $valStr = utf8_decode((string) $val);
                    // Truncar para caber na celula
                    if (strlen($valStr) > 25)
                        $valStr = substr($valStr, 0, 22) . '...';
                    $pdf->Cell($colWidth, 6, $valStr, 1, 0, 'L');
                }
                $pdf->Ln();
            }

            $tempPdf = $tempDir . '/' . $tableName . '.pdf';
            $pdf->Output('F', $tempPdf);
            $zip->addFile($tempPdf, "01_tabela_{$tableName}.pdf");
        }
    }

    $zip->close();

    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="heba_export_' . date('Ymd_His') . '.zip"');
    header('Content-Length: ' . filesize($zipFile));
    readfile($zipFile);
    unlink($zipFile); // Limpar ficheiro temp após envio

    } catch (Exception $e) {
        // Capturar erro
die("Erro ao exportar dados do sistema: " . $e->getMessage());
}
