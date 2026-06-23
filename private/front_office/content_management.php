<?php
// Carregar dependências
require_once(__DIR__ . "/../../config/funcoes.php");
// Restringir acesso
redirect_if_not_logged('private/login/login.php', ['view.content.management']);
// Ligar à BD
$ligacao = connect_to_db();

class SeccaoConfig
{
    public string $prefix;
    public string $titulo;
    public string $descricao;
    public string $iconeSvg;

    public function __construct(string $prefix, string $titulo, string $descricao, string $iconeSvg)
    {
        $this->prefix = $prefix;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->iconeSvg = $iconeSvg;
    }
}

$seccoesConfig = [
    new SeccaoConfig(
        'navbar',
        'Barra de Navegação',
        'Logótipo, links e botões da barra superior.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-navigation"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>'
    ),
    new SeccaoConfig(
        'hero',
        'Secção Hero',
        'Título de impacto principal, subtítulo de apresentação e botões de chamada para ação principais.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-template"><rect width="18" height="7" x="3" y="3" rx="1"/><rect width="9" height="7" x="3" y="14" rx="1"/><rect width="5" height="7" x="16" y="14" rx="1"/></svg>'
    ),
    new SeccaoConfig(
        'features',
        'Secção de Funcionalidades',
        'Textos gerais e cartões de funcionalidades (Bento Grid) da página pública.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-grid"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>'
    ),
    new SeccaoConfig(
        'advantages',
        'Vantagens do Sistema',
        'Título, subtítulo e os itens detalhados das vantagens competitivas do HEBA.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>'
    ),
    new SeccaoConfig(
        'clients',
        'Soluções por Segmento',
        'Textos de foco e descrição das soluções destinadas a hospitais, clínicas e profissionais.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
    ),
    new SeccaoConfig(
        'cta',
        'Secção de Contacto (CTA)',
        'Títulos, rótulos e placeholders do formulário final de pedido de demonstração.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>'
    ),
    new SeccaoConfig(
        'footer',
        'Definições do Rodapé',
        'Configurações de contactos, redes sociais, copyright e informações institucionais.',
        '<!-- SVG -->
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>'
    )
];

// Inicializar variáveis
$success_message = null;
$server_error = null;

if (!empty($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['server_error'])) {
    $server_error = $_SESSION['server_error'];
    unset($_SESSION['server_error']);
}

$textos = [];
$cartoes = [];

try {

    // Criar Cartão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar_cartao'])) {
        if (!tem_permissao('content.cards.create')) {
            throw new Exception("Não tem permissão para criar cartões.");
        }
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $titulo = trim($_POST['card-title'] ?? '');
        // Recolher dados do POST
        $descricao = trim($_POST['card-desc'] ?? '');
        // Recolher dados do POST
        $icon_type = $_POST['card-icon'] ?? '';
        // Recolher dados do POST
        $custom_icon = trim($_POST['card-custom-icon'] ?? '');
        $ativo = isset($_POST['card-status']) ? 1 : 0;

        $icone = CartaoFuncionalidade::resolverIcone($icon_type, $custom_icon);

        // Consultar registos
        $resultadoQuery = execute_query("SELECT MAX(ordem) FROM CartaoFuncionalidade", [], $ligacao);

        $maxOrdem = (int) $resultadoQuery->fetchColumn();

        $proxima_ordem = $maxOrdem + 1;

        // Validar dados
        $erros = CartaoFuncionalidade::validarDados([
            'idCartao' => 1, // ID temporário para validação na criação
            'titulo' => $titulo,
            'descricao' => $descricao,
            'icone' => $icone,
            'ordem' => $proxima_ordem
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(" ", $erros));
        }

        // Inserir registo
        execute_query(
            "INSERT INTO CartaoFuncionalidade (titulo, descricao, icone, ordem, ativo, dataCriacao, dataAtualizacao)
             VALUES (:titulo, :descricao, :icone, :ordem, :ativo, NOW(), NOW())",
            [
                'titulo' => $titulo,
                'descricao' => $descricao,
                'icone' => $icone,
                'ordem' => $proxima_ordem,
                'ativo' => $ativo
            ],
            $ligacao
        );

        $ligacao->commit();
        $_SESSION['success_message'] = "Cartão criado com sucesso!";
        // Redirecionar
        header("Location: content_management.php");
        exit;
    }

    // Editar Cartão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_cartao'])) {
        if (!tem_permissao('content.cards.edit')) {
            throw new Exception("Não tem permissão para editar cartões.");
        }
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $cardIdEncriptado = $_POST['card-id'] ?? '';
        $cardId = aes_decrypt($cardIdEncriptado);
        if ($cardId === false) {
            throw new Exception("ID de cartão inválido ou corrompido.");
        }

        // Recolher dados do POST
        $titulo = trim($_POST['card-title'] ?? '');
        // Recolher dados do POST
        $descricao = trim($_POST['card-desc'] ?? '');
        // Recolher dados do POST
        $icon_type = $_POST['card-icon'] ?? '';
        // Recolher dados do POST
        $custom_icon = trim($_POST['card-custom-icon'] ?? '');
        $ativo = isset($_POST['card-status']) ? 1 : 0;

        $icone = CartaoFuncionalidade::resolverIcone($icon_type, $custom_icon);

        // Validar dados
        $erros = CartaoFuncionalidade::validarDados([
            'idCartao' => (int) $cardId,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'icone' => $icone,
            'ordem' => 1 // Ordem temporária para validação no update
        ]);

        if (!empty($erros)) {
            throw new Exception(implode(" ", $erros));
        }

        // Atualizar registo
        execute_query(
            "UPDATE CartaoFuncionalidade 
             SET titulo = :titulo, descricao = :descricao, icone = :icone, ativo = :ativo, dataAtualizacao = NOW()
             WHERE idCartao = :idCartao",
            [
                'titulo' => $titulo,
                'descricao' => $descricao,
                'icone' => $icone,
                'ativo' => $ativo,
                'idCartao' => $cardId
            ],
            $ligacao
        );

        $ligacao->commit();
        $_SESSION['success_message'] = "Cartão atualizado com sucesso!";
        // Redirecionar
        header("Location: content_management.php");
        exit;
    }

    // Apagar / Inativar Cartão
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar_cartao'])) {
        if (!tem_permissao('content.cards.delete')) {
            throw new Exception("Não tem permissão para apagar cartões.");
        }
        $ligacao->beginTransaction();

        // Recolher dados do POST
        $cardIdEncriptado = $_POST['card-id'] ?? '';
        $cardId = aes_decrypt($cardIdEncriptado);
        if ($cardId === false) {
            throw new Exception("ID de cartão inválido ou corrompido.");
        }

        // Atualizar registo
        execute_query(
            "UPDATE CartaoFuncionalidade 
             SET ativo = 0, dataAtualizacao = NOW()
             WHERE idCartao = :idCartao",
            [
                'idCartao' => $cardId
            ],
            $ligacao
        );

        $ligacao->commit();
        $_SESSION['success_message'] = "Cartão desativado com sucesso!";
        // Redirecionar
        header("Location: content_management.php");
        exit;
    }

    $conteudoPagina = ConteudoPagina::carregarDaBaseDeDados($ligacao);
    $textos = $conteudoPagina->getTextos();

    // Consultar registos
    $stmt = execute_query(
        "SELECT idCartao, titulo, descricao, icone, ordem, ativo FROM CartaoFuncionalidade ORDER BY ordem ASC",
        [],
        $ligacao
    );
    $cartoes = $stmt->fetchAll(PDO::FETCH_OBJ);
} catch (Exception $e) {
    if ($ligacao && $ligacao->inTransaction()) {
        $ligacao->rollBack();
    }
    $server_error = "Erro no servidor: " . $e->getMessage();
}

// Agrupar textos por prefixo
$seccoesAgrupadas = [];
foreach ($seccoesConfig as $config) {
    $seccoesAgrupadas[$config->prefix] = [];
}
foreach ($textos as $chave => $textoObj) {
    $parts = explode('.', $chave);
    $prefix = $parts[0];
    if (isset($seccoesAgrupadas[$prefix])) {
        $seccoesAgrupadas[$prefix][] = $textoObj;
    }
}

// Carregar dependências
include_once BASE_PATH . 'private/includes/head.php';
include_once BASE_PATH . 'private/includes/sidebar-desktop.php';

?>

<div class="d-flex flex-column flex-grow-1 overflow-x-hidden mw-0">

    <?php include_once BASE_PATH . 'private/includes/headers.php'; ?>

    <!-- Conteúdo -->
    <section class="gap-6 d-flex  flex-column padding-6 content-management flex-grow-1 p-0">
        <div class="d-flex flex-column padding-6 gap-6 flex-grow-1">
            <!-- Título -->
            <div
                class="d-flex justify-content-between align-items-center w-100 dashboard-title flex-column flex-md-row">
                <div class="d-flex flex-column gap-1">
                    <!-- Título -->
                    <h1>Gestão de Conteúdos</h1>
                    <p class="text-secondary fw-400">Página única de edição. Expanda os módulos para editar os textos do
                        website público.</p>
                </div>
            </div>

            <!-- Formulário de Edição -->
            <form id="update-texts-form" method="POST" action="content_management-crud/update-content-management.php"
                class="w-100" novalidate>
                <!-- Wrapper Dropdowns -->
                <div class="d-flex flex-column gap-4 w-100">
                    <?php foreach ($seccoesConfig as $config): ?>
                        <div class="d-flex flex-column gap-3 w-100">
                            <div class="card bento-card d-flex align-items-start overflow-hidden">
                                <!-- Botão -->
                                <button
                                    class="btn btn-link text-decoration-none mw-0 d-flex w-100 accordion-button bg-transparent  top-level padding-6 collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $config->prefix ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $config->prefix ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex gap-3 align-items-center w-100">
                                            <div
                                                class="table-icon-wrapper padding-2 d-flex align-items-center justify-content-center flex-shrink-0 content-icon-wrapper">
                                                <?= $config->iconeSvg ?>
                                            </div>
                                            <div class="d-flex flex-column gap-half text-primary">
                                                <!-- Título -->
                                                <h3 class="fw-700 text-decoration-none">
                                                    <?= htmlspecialchars($config->titulo) ?>
                                                </h3>
                                                <p class="text-secondary text-decoration-none">
                                                    <?= htmlspecialchars($config->descricao) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <!-- SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-chevron-down text-muted">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </div>
                                </button>

                                <div id="collapse-<?= $config->prefix ?>" class="collapse w-100"
                                    aria-labelledby="heading-<?= $config->prefix ?>">
                                    <div class="card-body collapse-inner-level d-flex flex-column gap-4 padding-6 w-100">

                                        <?php foreach ($seccoesAgrupadas[$config->prefix] as $textoObj): ?>
                                            <?php $chaveEncriptada = aes_encrypt($textoObj->getChaveSecao()); ?>
                                            <div class="d-flex flex-column form-item w-100 mb-2">
                                                <!-- Label -->
                                                <label for="textos-<?= htmlspecialchars($chaveEncriptada) ?>" class="fw-700">
                                                    <?= htmlspecialchars($textoObj->getChaveSecao()) ?>
                                                </label>
                                                <p class="text-secondary fw-400 mb-2">
                                                    <?= htmlspecialchars($textoObj->getDescricao()) ?>
                                                </p>
                                                <!-- Textarea -->
                                                <textarea id="textos-<?= htmlspecialchars($chaveEncriptada) ?>"
                                                    name="textos[<?= htmlspecialchars($chaveEncriptada) ?>]" rows="2"
                                                    class="form-control" <?= tem_permissao('content.edit') ? '' : 'readonly' ?>
                                                    required><?= htmlspecialchars($textoObj->getValor()) ?></textarea>
                                            </div>
                                        <?php endforeach; ?>

                                        <?php if ($config->prefix === 'features'): ?>
                                            <hr class="my-4 w-100" style="border-top: 1px solid var(--border-light);">

                                            <div class="d-flex flex-column gap-3 w-100">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="d-flex flex-column">
                                                        <!-- Subtítulo -->
                                                        <h4 class="fw-700 m-0">Cartões de Funcionalidades</h4>
                                                        <p class="text-secondary m-0">Visualize os cartões dinâmicos que compõem
                                                            a
                                                            Bento Grid pública.</p>
                                                    </div>
                                                    <?php if (tem_permissao('content.cards.create')): ?>
                                                        <!-- Botão -->
                                                        <button type="button" class="btn btn-ghost d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal" data-bs-target="#card-creation-modal">
                                                            <!-- SVG -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-plus">
                                                                <path d="M5 12h14" />
                                                                <path d="M12 5v14" />
                                                            </svg>
                                                            Adicionar Cartão
                                                        </button>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Tabela -->
                                                <div class="bento-card w-100 p-0 overflow-hidden mt-3">
                                                    <!-- Tabela -->
                                                    <table id="featuresTable" class="heba-table w-100 display border-0">
                                                        <thead>
                                                            <tr>
                                                                <th>ESTADO</th>
                                                                <th>ÍCONE</th>
                                                                <th>TÍTULO</th>
                                                                <th>DESCRIÇÃO</th>
                                                                <?php if (tem_permissao('content.cards.edit') || tem_permissao('content.cards.delete')): ?>
                                                                    <th class="text-end">AÇÕES</th>
                                                                <?php endif; ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($cartoes as $cartao):
                                                                $encryptedCardId = aes_encrypt($cartao->idCartao);
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php if ($cartao->ativo): ?>
                                                                            <span
                                                                                class="text-success d-flex align-items-center gap-1 fw-500">
                                                                                <!-- SVG -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                                    class="lucide lucide-eye">
                                                                                    <path
                                                                                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                                                    <circle cx="12" cy="12" r="3" />
                                                                                </svg>
                                                                                Ativo
                                                                            </span>
                                                                        <?php else: ?>
                                                                            <span
                                                                                class="text-danger d-flex align-items-center gap-1 fw-500">
                                                                                <!-- SVG -->
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                                    height="16" viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                                    class="lucide lucide-eye-off">
                                                                                    <path
                                                                                        d="M9.88 9.88a3 3 0 1 0 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61M2 2l20 20" />
                                                                                </svg>
                                                                                Inativo
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <!-- SVG -->
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                                            height="18" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="lucide">
                                                                            <?= $cartao->icone ?>
                                                                        </svg>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="fw-700"><?= htmlspecialchars($cartao->titulo) ?></span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="text-secondary fw-400"><?= htmlspecialchars($cartao->descricao) ?></span>
                                                                    </td>
                                                                    <?php if (tem_permissao('content.cards.edit') || tem_permissao('content.cards.delete')): ?>
                                                                        <td class="text-end">
                                                                            <div
                                                                                class="d-flex justify-content-end gap-3 align-items-center">
                                                                                <?php if (tem_permissao('content.cards.edit')): ?>
                                                                                    <!-- Botão -->
                                                                                    <button
                                                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                                                        type="button" data-bs-toggle="modal"
                                                                                        data-bs-target="#card-edit-modal-<?= htmlspecialchars($encryptedCardId) ?>">
                                                                                        <!-- SVG -->
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                                                            stroke="currentColor" stroke-width="2"
                                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                                            class="lucide lucide-pencil">
                                                                                            <path
                                                                                                d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                                                                            <path d="m15 5 4 4" />
                                                                                        </svg>
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                                <?php if (tem_permissao('content.cards.delete')): ?>
                                                                                    <!-- Botão -->
                                                                                    <button
                                                                                        class="btn opacity-50 hover-opacity-100 p-0 m-0 bg-transparent border-0 text-secondary"
                                                                                        type="button" data-bs-toggle="modal"
                                                                                        data-bs-target="#delete-confirm-modal-<?= htmlspecialchars($encryptedCardId) ?>">
                                                                                        <!-- SVG -->
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                                                            stroke="currentColor" stroke-width="2"
                                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                                            class="lucide lucide-trash-2 text-secondary">
                                                                                            <path d="M3 6h18" />
                                                                                            <path
                                                                                                d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                                                        </svg>
                                                                                    </button>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        </div>
        </form>
        <?php if (tem_permissao('content.edit')): ?>
            <!-- Wrapper Alterações -->
            <div class="inbox-changes-container position-sticky w-100  justify-content-between align-items-center padding-6"
                style="display: none;">
                <p class="text-muted m-0">Existem alterações pendentes</p>
                <!-- Botão -->
                <button form="update-texts-form" type="submit" name="guardar_textos"
                    class="btn btn-primary btn-glowing d-flex align-items-center gap-2">
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-save-icon lucide-save">
                        <path
                            d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" />
                        <path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7" />
                        <path d="M7 3v4a1 1 0 0 0 1 1h7" />
                    </svg>
                    Guardar alterações
                </button>
            </div>
        <?php endif; ?>

    </section>
</div>

<?php
// Carregar dependências
include_once BASE_PATH . 'private/includes/sidebar-mobile.php';
?>

<?php if (tem_permissao('content.cards.create')): ?>
    <!-- Modal -->
    <div class="modal fade" id="card-creation-modal" tabindex="-1" aria-labelledby="cardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
            <div class="modal-content custom-modal-content d-flex flex-column">
                <!-- Título -->
                <div
                    class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                    <div class="d-flex flex-column">
                        <!-- Título -->
                        <h2 class="equipment-creation-modal-title modal-title" id="cardModalLabel">Novo Cartão</h2>
                        <span class="text-secondary fw-400">Configura o módulo a embutir na landing page.</span>
                    </div>

                    <!-- Botão -->
                    <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                        data-bs-dismiss="modal" aria-label="Close">
                        <!-- SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x stroke-secondary">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Wrapper Modal -->
                <div class="modal-body p-0">
                    <!-- Formulário -->
                    <form method="POST" action="content_management.php" id="card-creation-form"
                        class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">

                        <!-- Wrapper Linha -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <!-- Label -->
                            <label for="card-title">Título do Funcionalidade</label>
                            <!-- Input -->
                            <input type="text" id="card-title" name="card-title" required>
                        </div>

                        <!-- Wrapper Linha -->
                        <div class="d-flex flex-column form-item w-100 mw-0">
                            <!-- Label -->
                            <label for="card-desc">Descrição Curta</label>
                            <!-- Textarea -->
                            <textarea id="card-desc" name="card-desc" rows="3" class="form-control" required></textarea>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-4 w-100">
                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <label for="card-icon">Ícone Símbolo</label>
                                <!-- Input Select -->
                                <select id="card-icon" name="card-icon" class="form-select w-100 card-icon-select">
                                    <?php foreach (CartaoFuncionalidade::$icon_map as $key => $iconData): ?>
                                        <option value="<?= $key ?>" data-svg="<?= htmlspecialchars($iconData['svg']) ?>">
                                            <?= htmlspecialchars($iconData['label']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="other">Outro (Personalizado)</option>
                                </select>
                            </div>

                            <div class="d-flex flex-column form-item w-100 w-md-50">
                                <!-- Label -->
                                <label>Estado na Página</label>
                                <div class="d-flex align-items-center gap-2 switch-wrapper">
                                    <div class="form-check form-switch p-0 m-0 d-flex align-items-center gap-3">
                                        <!-- Input -->
                                        <input class="form-check-input m-0 switch-input cursor-pointer" type="checkbox"
                                            id="card-status" name="card-status" checked>
                                        <!-- Label -->
                                        <label class="form-check-label m-0 fw-500 text-secondary"
                                            for="card-status">Visível</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Wrapper -->
                        <div class="d-flex flex-column form-item w-100 custom-icon-wrapper"
                            id="creation-custom-icon-wrapper">
                            <div class="d-flex flex-column gap-2 custom-textarea-container d-none"
                                id="creation-custom-textarea-container">
                                <!-- Label -->
                                <label for="creation-card-custom-icon">Código do Ícone (SVG inner tags)</label>
                                <!-- Textarea -->
                                <textarea id="creation-card-custom-icon" name="card-custom-icon" rows="2"
                                    class="form-control card-custom-icon-textarea"
                                    placeholder="&lt;path d='...' /&gt;"></textarea>
                                <div class="form-text text-warning mt-1">⚠️ Aviso: Certifique-se de introduzir código
                                    SVG/HTML
                                    válido e fechado, caso contrário o layout da página poderá quebrar.</div>
                            </div>
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <span class="text-secondary fw-500">Visualização:</span>
                                <div class="border rounded p-2 d-flex align-items-center justify-content-center bg-light"
                                    style="width: 42px; height: 42px; color: var(--text-primary);">
                                    <!-- SVG -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide icon-preview-svg" id="creation-icon-preview">
                                        <?php
                                        reset(CartaoFuncionalidade::$icon_map);
                                        $firstKey = key(CartaoFuncionalidade::$icon_map);
                                        echo CartaoFuncionalidade::$icon_map[$firstKey]['svg'] ?? '';
                                        ?>
                                    </svg>
                                </div>
                                <span id="creation-icon-error" class="text-danger fw-500 icon-error-msg d-none">Código XML
                                    mal-formado</span>
                            </div>
                        </div>

                        <!-- Rodapé Formulário -->
                        <div
                            class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 align-items-center">
                            <!-- Botão -->
                            <button type="button" class="btn btn-link text-secondary text-decoration-none p-0"
                                data-bs-dismiss="modal">Cancelar</button>
                            <!-- Botão -->
                            <button type="submit" name="criar_cartao" id="btn-submit-modal"
                                class="btn btn-primary btn-glowing">
                                Guardar Card
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3 mt-4" style="z-index: 9999;">
    <?php if (!empty($success_message)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-success w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($success_message) ?>
                </div>
                <!-- Botão -->
                <button type="button" class="text-success border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($server_error)): ?>
        <div class="toast align-items-center border-0 shadow-sm toast-error w-auto padding-4 show" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="d-flex align-items-center gap-2">
                <div class="toast-body fw-500 p-0">
                    <?= htmlspecialchars($server_error) ?>
                </div>
                <!-- Botão -->
                <button type="button" class="text-error border-0 p-0 bg-transparent ms-auto" data-bs-dismiss="toast"
                    aria-label="Close">
                    <!-- SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php foreach ($cartoes as $cartao):
    $encryptedCardId = aes_encrypt($cartao->idCartao);

    // Inicializar variáveis
    $matchedKey = null;
    foreach (CartaoFuncionalidade::$icon_map as $key => $val) {
        if (preg_replace('/\s+/', '', $val['svg']) === preg_replace('/\s+/', '', $cartao->icone)) {
            $matchedKey = $key;
            break;
        }
    }
    $is_custom = ($matchedKey === null);
    ?>
    <?php if (tem_permissao('content.cards.edit')): ?>
        <!-- Modal -->
        <div class="modal fade" id="card-edit-modal-<?= htmlspecialchars($encryptedCardId) ?>" tabindex="-1"
            aria-labelledby="cardEditModalLabel-<?= htmlspecialchars($encryptedCardId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Título -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <!-- Título -->
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="cardEditModalLabel-<?= htmlspecialchars($encryptedCardId) ?>">
                                Editar Cartão</h2>
                            <span class="text-secondary fw-400">Configura o módulo a embutir
                                na landing page.</span>
                        </div>

                        <!-- Botão -->
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <!-- SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Wrapper Modal -->
                    <div class="modal-body p-0">
                        <!-- Formulário -->
                        <form method="POST" action="content_management.php"
                            class="equipment-creation-modal-content padding-6 gap-5 d-flex flex-column">
                            <!-- Input -->
                            <input type="hidden" name="card-id" value="<?= htmlspecialchars($encryptedCardId) ?>">

                            <!-- Wrapper Linha -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <!-- Label -->
                                <label for="edit-card-title-<?= htmlspecialchars($encryptedCardId) ?>">Título
                                    do Funcionalidade</label>
                                <!-- Input -->
                                <input type="text" id="edit-card-title-<?= htmlspecialchars($encryptedCardId) ?>"
                                    name="card-title" value="<?= htmlspecialchars($cartao->titulo) ?>" required>
                            </div>

                            <!-- Wrapper Linha -->
                            <div class="d-flex flex-column form-item w-100 mw-0">
                                <!-- Label -->
                                <label for="edit-card-desc-<?= htmlspecialchars($encryptedCardId) ?>">Descrição
                                    Curta</label>
                                <!-- Textarea -->
                                <textarea id="edit-card-desc-<?= htmlspecialchars($encryptedCardId) ?>" name="card-desc"
                                    rows="3" class="form-control"
                                    required><?= htmlspecialchars($cartao->descricao) ?></textarea>
                            </div>

                            <div class="d-flex flex-column flex-md-row gap-4 w-100">
                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <!-- Label -->
                                    <label for="edit-card-icon-<?= htmlspecialchars($encryptedCardId) ?>">Ícone
                                        Símbolo</label>
                                    <!-- Input Select -->
                                    <select id="edit-card-icon-<?= htmlspecialchars($encryptedCardId) ?>" name="card-icon"
                                        class="form-select w-100 card-icon-select">
                                        <?php foreach (CartaoFuncionalidade::$icon_map as $key => $iconData): ?>
                                            <option value="<?= $key ?>" data-svg="<?= htmlspecialchars($iconData['svg']) ?>"
                                                <?= $matchedKey === $key ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($iconData['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="other" <?= $is_custom ? 'selected' : '' ?>>Outro (Personalizado)</option>
                                    </select>
                                </div>

                                <div class="d-flex flex-column form-item w-100 w-md-50">
                                    <!-- Label -->
                                    <label>Estado na Página</label>
                                    <div class="d-flex align-items-center gap-2 switch-wrapper">
                                        <div class="form-check form-switch p-0 m-0 d-flex align-items-center gap-3">
                                            <!-- Input -->
                                            <input class="form-check-input m-0 switch-input cursor-pointer" type="checkbox"
                                                id="edit-card-status-<?= htmlspecialchars($encryptedCardId) ?>"
                                                name="card-status" <?= $cartao->ativo ? 'checked' : '' ?>>
                                            <!-- Label -->
                                            <label class="form-check-label m-0 fw-500 text-secondary"
                                                for="edit-card-status-<?= htmlspecialchars($encryptedCardId) ?>">Visível</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Wrapper -->
                            <div class="d-flex flex-column form-item w-100 custom-icon-wrapper">
                                <div
                                    class="d-flex flex-column gap-2 custom-textarea-container <?= $is_custom ? '' : 'd-none' ?>">
                                    <!-- Label -->
                                    <label for="edit-card-custom-icon-<?= htmlspecialchars($encryptedCardId) ?>">Código
                                        do Ícone (SVG inner tags)</label>
                                    <!-- Textarea -->
                                    <textarea id="edit-card-custom-icon-<?= htmlspecialchars($encryptedCardId) ?>"
                                        name="card-custom-icon" rows="2" class="form-control card-custom-icon-textarea"
                                        placeholder="&lt;path d='...' /&gt;"><?= $is_custom ? htmlspecialchars($cartao->icone) : '' ?></textarea>
                                    <div class="form-text text-warning mt-1">⚠️ Aviso:
                                        Certifique-se de introduzir código SVG/HTML válido e
                                        fechado, caso contrário o layout da página poderá
                                        quebrar.</div>
                                </div>
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <span class="text-secondary fw-500">Visualização:</span>
                                    <div class="border rounded p-2 d-flex align-items-center justify-content-center bg-light"
                                        style="width: 42px; height: 42px; color: var(--text-primary);">
                                        <!-- SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide icon-preview-svg">
                                            <?= $cartao->icone ?>
                                        </svg>
                                    </div>
                                    <span class="text-danger fw-500 icon-error-msg d-none">Código
                                        XML mal-formado</span>
                                </div>
                            </div>

                            <!-- Rodapé Formulário -->
                            <div
                                class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row  mt-4 align-items-center">
                                <!-- Botão -->
                                <button type="button" class="btn btn-link text-secondary text-decoration-none p-0"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <!-- Botão -->
                                <button type="submit" name="editar_cartao" class="btn btn-primary btn-glowing">
                                    Guardar Card
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (tem_permissao('content.cards.delete')): ?>
        <!-- Modal -->
        <div class="modal fade" id="delete-confirm-modal-<?= htmlspecialchars($encryptedCardId) ?>" tabindex="-1"
            aria-labelledby="deleteModalLabel-<?= htmlspecialchars($encryptedCardId) ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable equipment-creation-modal-dialog">
                <div class="modal-content custom-modal-content d-flex flex-column">
                    <!-- Título -->
                    <div
                        class="d-flex flex-row justify-content-between align-items-center equipment-creation-modal-title-section padding-6 border-0">
                        <div class="d-flex flex-column">
                            <!-- Título -->
                            <h2 class="equipment-creation-modal-title modal-title"
                                id="deleteModalLabel-<?= htmlspecialchars($encryptedCardId) ?>">
                                Eliminar Cartão</h2>
                            <span class="text-secondary fw-400">O cartão será movido para a reciclagem.</span>
                        </div>

                        <!-- Botão -->
                        <button class="equipment-creation-modal-close-btn btn p-0 border-0 bg-transparent"
                            data-bs-dismiss="modal" aria-label="Close">
                            <!-- SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-x-icon lucide-x stroke-secondary">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Wrapper Modal -->
                    <div class="modal-body p-0">
                        <!-- Formulário -->
                        <form method="POST" action="content_management.php">
                            <!-- Input -->
                            <input type="hidden" name="card-id" value="<?= htmlspecialchars($encryptedCardId) ?>">
                            <div
                                class="equipment-creation-modal-content padding-6 d-flex flex-column justify-content-center align-items-center gap-6">

                                <div class="d-flex flex-column align-items-center gap-4">
                                    <div class="d-flex padding-3 danger-icon">
                                        <!-- SVG -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-triangle-alert">
                                            <path
                                                d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                            <path d="M12 9v4" />
                                            <path d="M12 17h.01" />
                                        </svg>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center gap-3">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center gap-2 text-center">
                                            <p class="text-secondary">
                                                Tem a certeza que deseja apagar o cartão?
                                            </p>
                                            <!-- Título -->
                                            <h2 class="fw-700">
                                                "<?= htmlspecialchars($cartao->titulo) ?>"
                                            </h2>
                                            <span class="text-muted">Tipo: Cartão de
                                                Funcionalidade</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão -->
                                <div class="d-flex w-100 justify-content-end gap-4 button-row flex-column flex-md-row ">
                                    <!-- Botão -->
                                    <button type="button" class="btn btn-ghost equipment-creation-modal-cancel-btn"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <!-- Botão -->
                                    <button type="submit" name="apagar_cartao" class="btn btn-danger btn-glowing text-white">
                                        Sim, Apagar.
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php
// Carregar dependências
include_once BASE_PATH . 'private/includes/footer.php';
?>