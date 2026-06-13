-- ============================================================
-- HEBA — Dummy Data
-- ============================================================

USE `heba-db`;

-- Desativar verificações de FK durante TRUNCATE
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `NotificacaoUtilizador`;
TRUNCATE TABLE `Notificacao`;
TRUNCATE TABLE `HistoricoAuditoria`;
TRUNCATE TABLE `GarantiaContrato`;
TRUNCATE TABLE `Documento`;
TRUNCATE TABLE `Manutencao`;
TRUNCATE TABLE `FornecedorEquipamento`;
TRUNCATE TABLE `ComponenteCategoria`;
TRUNCATE TABLE `ComponenteEquipamento`;
TRUNCATE TABLE `Componente`;
TRUNCATE TABLE `Equipamento`;
TRUNCATE TABLE `Fornecedor`;
TRUNCATE TABLE `Utilizador`;
TRUNCATE TABLE `PerfilPermissao`;
TRUNCATE TABLE `Perfil`;
TRUNCATE TABLE `Permissao`;
TRUNCATE TABLE `Pessoa`;
TRUNCATE TABLE `Localizacao`;
TRUNCATE TABLE `Servico`;
TRUNCATE TABLE `Piso`;
TRUNCATE TABLE `Edificio`;
TRUNCATE TABLE `Marca`;
TRUNCATE TABLE `CategoriaEquipamento`;
TRUNCATE TABLE `PedidoDemonstracao`;
TRUNCATE TABLE `CartaoFuncionalidade`;
TRUNCATE TABLE `ConteudoFrontOffice`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- FASE 1 — Tabelas independentes
-- ============================================================

-- 1. CategoriaEquipamento (10)
INSERT INTO `CategoriaEquipamento` (`nome`, `descricao`, `codigoPrefix`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Imagiologia',             'Equipamentos de diagnóstico por imagem (raio-X, ecografia, TAC, RM)',      'IMG',   true, '2024-01-15 09:00:00', '2024-01-15 09:00:00'),
('Laboratório',             'Equipamentos de análises clínicas e laboratoriais',                         'LAB',   true, '2024-01-15 09:05:00', '2024-01-15 09:05:00'),
('Monitorização',           'Monitores de sinais vitais e telemetria',                                   'MON',   true, '2024-01-15 09:10:00', '2024-01-15 09:10:00'),
('Suporte de Vida',         'Ventiladores, desfibrilhadores e bombas de infusão',                        'SVD',   true, '2024-01-15 09:15:00', '2024-01-15 09:15:00'),
('Cirurgia',                'Instrumentação e equipamento de bloco operatório',                          'CIR',   true, '2024-01-15 09:20:00', '2024-01-15 09:20:00'),
('Esterilização',           'Autoclaves e equipamentos de descontaminação',                              'EST',   true, '2024-01-15 09:25:00', '2024-01-15 09:25:00'),
('Fisioterapia',            'Equipamentos de reabilitação e fisioterapia',                               'FIS',   true, '2024-01-15 09:30:00', '2024-01-15 09:30:00'),
('Oftalmologia',            'Equipamentos de diagnóstico e tratamento oftalmológico',                    'OFT',   true, '2024-01-15 09:35:00', '2024-01-15 09:35:00'),
('Neonatologia',            'Incubadoras e equipamentos de cuidados neonatais',                          'NEO',   true, '2024-01-15 09:40:00', '2024-01-15 09:40:00'),
('Infraestrutura Clínica',  'Camas articuladas, macas e mobiliário clínico especializado',               'INF',   false, '2024-01-15 09:45:00', '2024-01-15 09:45:00');

-- 2. Marca (10)
INSERT INTO `Marca` (`nome`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Siemens Healthineers',  true, '2024-01-15 10:00:00', '2024-01-15 10:00:00'),
('GE Healthcare',         true, '2024-01-15 10:05:00', '2024-01-15 10:05:00'),
('Philips Healthcare',    true, '2024-01-15 10:10:00', '2024-01-15 10:10:00'),
('Dräger',                true, '2024-01-15 10:15:00', '2024-01-15 10:15:00'),
('Mindray',               true, '2024-01-15 10:20:00', '2024-01-15 10:20:00'),
('Medtronic',             true, '2024-01-15 10:25:00', '2024-01-15 10:25:00'),
('Olympus Medical',       true, '2024-01-15 10:30:00', '2024-01-15 10:30:00'),
('Fujifilm Healthcare',   true, '2024-01-15 10:35:00', '2024-01-15 10:35:00'),
('B. Braun',              true, '2024-01-15 10:40:00', '2024-01-15 10:40:00'),
('Getinge',               false, '2024-01-15 10:45:00', '2024-01-15 10:45:00');

-- 3. Edificio (3)
INSERT INTO `Edificio` (`nome`, `ativo`) VALUES
('Edifício Principal', true),
('Edifício Norte', true),
('Edifício Sul', true);

-- 3.1 Piso (10)
INSERT INTO `Piso` (`idEdificio`, `nome`, `ativo`) VALUES
(1, 'Piso 0', true),
(1, 'Piso 1', true),
(1, 'Piso 2', true),
(1, 'Piso 3', true),
(2, 'Piso 0', true),
(2, 'Piso 1', true),
(2, 'Piso 2', true),
(3, 'Piso 0', true),
(3, 'Piso 1', true),
(3, 'Piso 2', true);

-- 3.2 Servico (10)
INSERT INTO `Servico` (`idPiso`, `nome`, `ativo`) VALUES
(1, 'Urgências', true),
(2, 'Cardiologia', true),
(3, 'Bloco Operatório', true),
(4, 'UCI', true),
(5, 'Imagiologia', true),
(6, 'Laboratório Central', true),
(7, 'Neonatologia', true),
(8, 'Fisioterapia', true),
(9, 'Esterilização', true),
(10, 'Oftalmologia', true);

-- 3.3 Localizacao (10)
INSERT INTO `Localizacao` (`idServico`, `nomeSala`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1, 'Sala 001', true, '2024-02-01 08:00:00', '2024-02-01 08:00:00'),
(2, 'Sala 101', true, '2024-02-01 08:05:00', '2024-02-01 08:05:00'),
(3, 'Sala 201', true, '2024-02-01 08:10:00', '2024-02-01 08:10:00'),
(4, 'Sala 301', true, '2024-02-01 08:15:00', '2024-02-01 08:15:00'),
(5, 'Sala 001', true, '2024-02-01 08:20:00', '2024-02-01 08:20:00'),
(6, 'Sala 101', true, '2024-02-01 08:25:00', '2024-02-01 08:25:00'),
(7, 'Sala 201', true, '2024-02-01 08:30:00', '2024-02-01 08:30:00'),
(8, 'Sala 001', true, '2024-02-01 08:35:00', '2024-02-01 08:35:00'),
(9, 'Sala 101', true, '2024-02-01 08:40:00', '2024-02-01 08:40:00'),
(10, 'Sala 201', false,'2024-02-01 08:45:00', '2024-02-01 08:45:00');

-- 4. Permissao (25 — todas as chaves de profiles.php)
INSERT INTO `Permissao` (`chave`, `descricao`, `ativo`) VALUES
('equipment.view',       'Visualizar equipamentos', true),
('equipment.create',     'Criar equipamentos', true),
('equipment.edit',       'Editar equipamentos', true),
('equipment.delete',     'Apagar equipamentos', true),
('equipment.archive',    'Arquivar/restaurar equipamentos', true),
('maintenance.view',     'Visualizar manutenções', true),
('maintenance.create',   'Registar manutenções', true),
('maintenance.edit',     'Editar manutenções', true),
('maintenance.finalize', 'Finalizar manutenções', true),
('documents.view',       'Visualizar documentos', true),
('documents.upload',     'Carregar documentos', true),
('documents.delete',     'Apagar documentos', true),
('suppliers.view',       'Visualizar fornecedores', true),
('suppliers.manage',     'Gerir fornecedores (CRUD)', true),
('people.view',          'Visualizar pessoas', true),
('people.manage',        'Gerir pessoas (CRUD)', true),
('components.view',      'Visualizar componentes/stock', true),
('components.manage',    'Gerir componentes (CRUD)', true),
('users.view',           'Visualizar utilizadores', true),
('users.manage',         'Gerir utilizadores (CRUD)', true),
('audit.view',           'Visualizar logs de auditoria', true),
('locations.view',       'Visualizar localizações', true),
('locations.manage',     'Gerir localizações (CRUD)', true),
('permissions.manage',   'Gerir permissões e perfis', true),
('reports.generate',     'Gerar relatórios e exportar dados', false);

-- 5. Perfil (5 — conforme profiles.php)
INSERT INTO `Perfil` (`nome`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Administrador',            true, '2024-01-10 08:00:00', '2024-01-10 08:00:00'),
('Engenheiro Biomédico',     true, '2024-01-10 08:05:00', '2024-01-10 08:05:00'),
('Técnico de Manutenção',    true, '2024-01-10 08:10:00', '2024-01-10 08:10:00'),
('Aprovisionamento',         true, '2024-01-10 08:15:00', '2024-01-10 08:15:00'),
('Consulta',                 false, '2024-01-10 08:20:00', '2024-01-10 08:20:00');

-- 6. ConteudoFrontOffice (~40 registos — TODO o texto de index.php)
INSERT INTO `ConteudoFrontOffice` (`chaveSecao`, `valor`, `descricao`, `dataCriacao`, `dataAtualizacao`) VALUES
-- Navbar
('navbar.brand_name',                       'HEBA',                                                                                                                      'Nome da marca visível na barra de navegação', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('navbar.link_funcionalidades',              'Funcionalidades',                                                                                                           'Link para a secção de funcionalidades', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('navbar.link_vantagens',                    'Vantagens',                                                                                                                 'Link para a secção de vantagens', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('navbar.btn_agendar_demo',                  'Agendar Demonstração',                                                                                                      'Texto do botão para agendar demonstração na barra de navegação', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Main Section (Hero)
('hero.badge',                               'A solução de liderança em Health Tech',                                                                                     'Etiqueta pequena colocada acima do título da Hero section', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('hero.title',                               'Gestão inteligente de equipamentos hospitalares',                                                                           'Título principal da secção inicial (Hero)', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('hero.subtitle',                            'Criado para a eficiência clínica, o HEBA unifica o seu inventário, manutenções e documentação num único painel inovador. Devolva o foco ao que importa: o cuidado com os doentes.', 'Subtítulo de apoio da secção inicial (Hero)', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('hero.btn_agendar',                         'Agendar Demonstração',                                                                                                      'Texto do botão principal para agendar demonstração', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('hero.btn_explorar',                        'Explorar Funcionalidades',                                                                                                  'Texto do botão secundário para explorar as funcionalidades', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Features Section
('features.title',                           'Tudo o que o seu hospital precisa',                                                                                         'Título principal da secção de funcionalidades', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('features.subtitle',                        'Uma plataforma desenhada exclusivamente para simplificar a logística, mitigar o risco clínico e garantir a mantenabilidade dos equipamentos.', 'Subtítulo de apoio da secção de funcionalidades', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Advantages Section
('advantages.badge',                         'Vantagens do Sistema',                                                                                                      'Etiqueta pequena colocada acima do título da secção de vantagens', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.title',                         'Porquê escolher o HEBA?',                                                                                                   'Título principal da secção de vantagens', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.subtitle',                      'Em vez de adaptar softwares genéricos de armazém, desenhámos a nossa ferramenta com base nas complexidades reais enfrentadas por engenheiros clínicos e gestores operacionais.', 'Subtítulo de apoio da secção de vantagens', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item1_title',                   'Segurança de Dados e Fiabilidade',                                                                                          'Título da vantagem 1: Segurança de Dados e Fiabilidade', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item1_desc',                    'A infraestrutura HEBA garante encriptação contínua e total conformidade com protocolos GDPR para plataformas de setor clínico.', 'Descrição curta da vantagem 1: Segurança de Dados', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item2_title',                   'Conformidade Regulatória',                                                                                                  'Título da vantagem 2: Conformidade Regulatória', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item2_desc',                    'Auditorias facilitadas através do histórico inalterável e das notificações sobre prazos e certificações vencíveis.',          'Descrição curta da vantagem 2: Conformidade Regulatória', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item3_title',                   'Otimização Extrema de Tempo',                                                                                               'Título da vantagem 3: Otimização Extrema de Tempo', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('advantages.item3_desc',                    'Reduza a sobrecarga operacional da equipa técnica através da automação de processos de submissão de avarias.',                'Descrição curta da vantagem 3: Otimização Extrema de Tempo', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Clients Section
('clients.title',                            'A pensar em todo o tipo de prestadores',                                                                                    'Título principal da secção de clientes/prestadores', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card1_title',                      'Inventário Centralizado',                                                                                                   'Título do tipo de cliente 1', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card1_desc',                       'Registo exaustivo e rastreio completo de toda a gama de equipamentos médicos da unidade.',                                   'Descrição curta do tipo de cliente 1', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card2_title',                      'Gestão de Manutenção (CMMS)',                                                                                               'Título do tipo de cliente 2', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card2_desc',                       'Planeamento de manutenções preventivas e ordens de trabalho automatizadas para a equipa.',                                   'Descrição curta do tipo de cliente 2', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card3_title',                      'Controlo de Fornecedores',                                                                                                  'Título do tipo de cliente 3', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('clients.card3_desc',                       'Gestão integrada de contratos, garantias e acompanhamento de técnicos externos.',                                            'Descrição curta do tipo de cliente 3', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- CTA Section
('cta.title',                                'Pronto para digitalizar?',                                                                                                  'Título da secção Call-To-Action final', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.subtitle',                             'Preencha os dados e a nossa equipa agendará uma demonstração de produto de 30 minutos sem qualquer compromisso.',             'Subtítulo da secção Call-To-Action final', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.label_nome',                           'Nome Completo',                                                                                                             'Rótulo do campo Nome no formulário', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.placeholder_nome',                     'Introduza o seu nome',                                                                                                      'Texto de ajuda/exemplo do campo Nome', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.label_email',                          'Email Profissional',                                                                                                        'Rótulo do campo Email no formulário', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.placeholder_email',                    'email@hospital.pt',                                                                                                         'Texto de ajuda/exemplo do campo Email', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.label_organizacao',                    'Organização / Unidade de Saúde',                                                                                            'Rótulo do campo Organização no formulário', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.placeholder_organizacao',              'Nome do Hospital ou Clínica',                                                                                               'Texto de ajuda/exemplo do campo Organização', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('cta.btn_submit',                           'Pedir Demonstração Gratuita',                                                                                               'Texto do botão de submissão do formulário', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Footer
('footer.brand_name',                        'HEBA',                                                                                                                      'Nome da marca exibida no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.description',                       'A nova norma em Sistemas de Informação para a Gestão de Equipamentos Clínicos. Otimize e assegure o futuro do seu parque tecnológico hospitalar.', 'Descrição institucional exibida no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.location',                          'Porto, Portugal',                                                                                                           'Localização física/sede exibida no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.email',                             'helena.b1210@gmail.com',                                                                                                    'Endereço de email de contacto no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.phone',                             '+351 912 951 772',                                                                                                          'Número de telefone de contacto no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.section_acesso_rapido',             'Acesso Rápido',                                                                                                             'Título do grupo de links Acesso Rápido', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_funcionalidades',              'Ver Funcionalidades',                                                                                                       'Link para Funcionalidades no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_vantagens',                    'Vantagens do Sistema',                                                                                                      'Link para Vantagens no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_demo',                         'Pedir Demonstração',                                                                                                        'Link para Pedir Demonstração no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.section_plataforma',                'Plataforma',                                                                                                                'Título do grupo de links Plataforma no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_backoffice',                   'Aceder ao Backoffice',                                                                                                      'Link de acesso ao Backoffice no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_termos',                       'Termos de Utilização',                                                                                                      'Link para Termos de Utilização no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.link_privacidade',                  'Privacidade (RGPD)',                                                                                                        'Link para Privacidade no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.copyright',                         '© 2026 HEBA. Todos os direitos reservados.',                                                                                'Texto de copyright no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('footer.developer',                         'Desenvolvido por Helena Barbosa.',                                                                                          'Texto de créditos do programador no rodapé', '2024-03-01 10:00:00', '2024-03-01 10:00:00');

-- 7. CartaoFuncionalidade (6 cartões de features de index.php)
INSERT INTO `CartaoFuncionalidade` (`titulo`, `descricao`, `icone`, `ordem`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Inventário Centralizado',        'Registo exaustivo e rastreio completo de toda a gama de equipamentos médicos da unidade.',                       '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" /><path d="M12 22V12" /><polyline points="3.29 7 12 12 20.71 7" /><path d="m7.5 4.27 9 5.15" />',  1, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('Gestão de Manutenção (CMMS)',    'Planeamento de manutenções preventivas e ordens de trabalho automatizadas para a equipa.',                       '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />',  2, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('Controlo de Fornecedores',       'Gestão integrada de contratos, garantias e acompanhamento de técnicos externos.',                                '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><path d="M16 3.128a4 4 0 0 1 0 7.744" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><circle cx="9" cy="7" r="4" />',  3, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('Gestão Documental',             'Armazene centralmente os certificados, relatórios de calibração e manuais técnicos.',                             '<path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" /><path d="M14 2v5a1 1 0 0 0 1 1h5" /><path d="M10 9H8" /><path d="M16 13H8" /><path d="M16 17H8" />',  4, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('Dashboard Analítico',           'Métricas vitais, relatórios customizados e estado do equipamento num ecrã central.',                              '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />',  5, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
('Assistente IA',                 'Recomendações preditivas e extração de dados através de Inteligência Artificial generativa.',                     '<path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" /><path d="M20 2v4" /><path d="M22 4h-4" /><circle cx="4" cy="20" r="2" />',  6, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00');


-- ============================================================
-- FASE 2 — Tabelas com FK de nível único
-- ============================================================

-- 8. Pessoa (11)
INSERT INTO `Pessoa` (`nome`, `email`, `contactoTelefonico`, `nif`, `funcao`, `departamento`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('João Silva',              'admin@hospital.pt',              '+351912000001', '123456789', 'Diretor',        'Direção',            true, '2024-03-01 09:00:00', '2024-03-01 09:00:00'),
('Ana Costa',               'eng.bio@hospital.pt',            '+351912000002', '234567891', 'Engenheiro',     'Eng. Biomédica',     true, '2024-03-01 09:05:00', '2024-03-01 09:05:00'),
('Miguel Santos',           'tecnico@hospital.pt',            '+351912000003', '345678912', 'Técnico',        'Manutenção',         true, '2024-03-01 09:10:00', '2024-03-01 09:10:00'),
('Maria Ferreira',          'aprovisionamento@hospital.pt',   '+351912000004', '456789123', 'Assistente',     'Aprovisionamento',   true, '2024-03-01 09:15:00', '2024-03-01 09:15:00'),
('Pedro Oliveira',          'consulta@hospital.pt',           '+351912000005', '567891234', 'Médico',         'Consultas',          true, '2024-03-01 09:20:00', '2024-03-01 09:20:00'),
('Sofia Martins',           'sofia.martins@hospital.pt',      '+351912000006', '678912345', 'Enfermeiro',     'Urgências',          true, '2024-03-01 09:25:00', '2024-03-01 09:25:00'),
('Ricardo Pereira',         'ricardo.pereira@hospital.pt',    '+351912000007', '789123456', 'Técnico',        'Manutenção',         true, '2024-03-01 09:30:00', '2024-03-01 09:30:00'),
('Teresa Rodrigues',        'teresa.rodrigues@hospital.pt',   '+351912000008', '891234567', 'Engenheiro',     'Eng. Biomédica',     true, '2024-03-01 09:35:00', '2024-03-01 09:35:00'),
('Carlos Almeida',          'carlos.almeida@hospital.pt',     '+351912000009', '912345678', 'Assistente',     'Aprovisionamento',   true, '2024-03-01 09:40:00', '2024-03-01 09:40:00'),
('Beatriz Lopes',           'beatriz.lopes@hospital.pt',      '+351912000010', '198765432', 'Médico',         'Consultas',          true, '2024-03-01 09:45:00', '2024-03-01 09:45:00'),
('Helena Teste',            'helena.teste@hospital.pt',       '+351912000011', '123123123', 'Administrador',  'Sistemas',           true, '2024-03-01 09:50:00', '2024-03-01 09:50:00');

-- 9. PerfilPermissao (5 perfis × 25 permissões = 125 registos)
-- Mapeamento exato da matriz booleana de profiles.php
INSERT INTO `PerfilPermissao` (`idPerfil`, `idPermissao`, `possui`) VALUES
-- Administrador (idPerfil=1) — TODAS true
(1,1,true),(1,2,true),(1,3,true),(1,4,true),(1,5,true),
(1,6,true),(1,7,true),(1,8,true),(1,9,true),(1,10,true),
(1,11,true),(1,12,true),(1,13,true),(1,14,true),(1,15,true),
(1,16,true),(1,17,true),(1,18,true),(1,19,true),(1,20,true),
(1,21,true),(1,22,true),(1,23,true),(1,24,true),(1,25,true),
-- Engenheiro Biomédico (idPerfil=2)
(2,1,true),(2,2,true),(2,3,true),(2,4,false),(2,5,true),
(2,6,true),(2,7,true),(2,8,true),(2,9,true),(2,10,true),
(2,11,true),(2,12,true),(2,13,true),(2,14,true),(2,15,true),
(2,16,true),(2,17,true),(2,18,true),(2,19,false),(2,20,false),
(2,21,true),(2,22,true),(2,23,true),(2,24,false),(2,25,true),
-- Técnico de Manutenção (idPerfil=3)
(3,1,true),(3,2,false),(3,3,true),(3,4,false),(3,5,false),
(3,6,true),(3,7,true),(3,8,true),(3,9,true),(3,10,true),
(3,11,true),(3,12,false),(3,13,true),(3,14,false),(3,15,true),
(3,16,false),(3,17,true),(3,18,true),(3,19,false),(3,20,false),
(3,21,false),(3,22,true),(3,23,false),(3,24,false),(3,25,false),
-- Aprovisionamento (idPerfil=4)
(4,1,true),(4,2,false),(4,3,false),(4,4,false),(4,5,false),
(4,6,false),(4,7,false),(4,8,false),(4,9,false),(4,10,true),
(4,11,true),(4,12,false),(4,13,true),(4,14,true),(4,15,true),
(4,16,true),(4,17,true),(4,18,true),(4,19,false),(4,20,false),
(4,21,false),(4,22,true),(4,23,false),(4,24,false),(4,25,true),
-- Consulta (idPerfil=5)
(5,1,true),(5,2,false),(5,3,false),(5,4,false),(5,5,false),
(5,6,true),(5,7,false),(5,8,false),(5,9,false),(5,10,true),
(5,11,false),(5,12,false),(5,13,true),(5,14,false),(5,15,true),
(5,16,false),(5,17,true),(5,18,false),(5,19,false),(5,20,false),
(5,21,false),(5,22,true),(5,23,false),(5,24,false),(5,25,false);

-- 10. Fornecedor (10)
INSERT INTO `Fornecedor` (`nome`, `nifFornecedor`, `contactoTelefonico`, `email`, `morada`, `website`, `idPessoaResponsavel`, `tipoFornecedor`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Siemens Portugal',                '501234501', '+351210100001', 'vendas@siemens.pt',        'Rua do Ouro 120, 1100-063 Lisboa',          'https://www.siemens-healthineers.com/pt', 6,  'Fabricante',              'Fornecedor principal de imagiologia',                          true, '2024-04-01 10:00:00', '2024-04-01 10:00:00'),
('Philips Ibérica',                 '501234502', '+351210100002', 'info@philips.pt',          'Av. da Liberdade 200, 1250-147 Lisboa',     'https://www.philips.pt',                  7,  'Fabricante',              'Equipamento de monitorização e ventilação',                    true, '2024-04-01 10:05:00', '2024-04-01 10:05:00'),
('MedServ Assistência Técnica',     '501234503', '+351220300003', 'suporte@medserv.pt',       'Rua de Santa Catarina 450, 4000-450 Porto', 'https://www.medserv.pt',                  8,  'Assistência Técnica',     'Assistência técnica multimarca',                               true, '2024-04-01 10:10:00', '2024-04-01 10:10:00'),
('BioEquip Distribuição',           '501234504', '+351220300004', 'geral@bioequip.pt',        'Rua dos Clérigos 80, 4050-204 Porto',       'https://www.bioequip.pt',                 9,  'Distribuidor',            'Distribuidor regional de equipamento hospitalar',              true, '2024-04-01 10:15:00', '2024-04-01 10:15:00'),
('Lab Consumíveis Lda',             '501234505', '+351210100005', 'encomendas@labconsum.pt',  'Av. Brasil 101, 1700-066 Lisboa',           'https://www.labconsum.pt',                10, 'Consumíveis',             'Fornecedor de reagentes e consumíveis laboratoriais',          true, '2024-04-01 10:20:00', '2024-04-01 10:20:00'),
('GE Healthcare Portugal',          '501234506', '+351210100006', 'vendas@gehealthcare.pt',   'Parque das Nações, 1990-073 Lisboa',        'https://www.gehealthcare.com/pt',          6,  'Fabricante',              'Equipamento de imagem e ultrassonografia',                     true, '2024-04-01 10:25:00', '2024-04-01 10:25:00'),
('Dräger Portugal',                 '501234507', '+351210100007', 'info@draeger.pt',          'Rua Engenheiro Frederico Ulrich 2, Maia',   'https://www.draeger.com/pt_pt',            7,  'Fabricante',              'Ventilação, anestesia e monitorização de gases',               true, '2024-04-01 10:30:00', '2024-04-01 10:30:00'),
('Iberdata Equipamentos Médicos',   '501234508', '+351220300008', 'info@iberdata.pt',         'Rua do Almada 340, 4050-032 Porto',         'https://www.iberdata.pt',                 8,  'Distribuidor',            'Distribuidor multi-segmento de equipamentos clínicos',         true, '2024-04-01 10:35:00', '2024-04-01 10:35:00'),
('CalibTech Metrologia',            '501234509', '+351220300009', 'calibracoes@calibtech.pt', 'Zona Industrial de Maia, Lote 15',          'https://www.calibtech.pt',                9,  'Assistência Técnica',     'Calibrações e verificações metrológicas para equipamento médico', true, '2024-04-01 10:40:00', '2024-04-01 10:40:00'),
('MedConsumables S.A.',             '501234510', '+351210100010', 'vendas@medconsumables.pt', 'Rua Augusta 250, 1100-054 Lisboa',          'https://www.medconsumables.pt',            10, 'Consumíveis',             'Consumíveis descartáveis e acessórios médicos',                true, '2024-04-01 10:45:00', '2024-04-01 10:45:00');

-- 11. Utilizador (10 — passwords encriptadas com AES_ENCRYPT)
-- Passwords em texto: password01..password10
INSERT INTO `Utilizador` (`idPessoa`, `emailAutenticacao`, `password`, `idPerfil`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'admin@hospital.pt',              AES_ENCRYPT('password01', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 1, true,  '2024-03-01 09:00:00', '2024-03-01 09:00:00'),
(2,  'eng.bio@hospital.pt',            AES_ENCRYPT('password02', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 2, true,  '2024-03-01 09:05:00', '2024-03-01 09:05:00'),
(3,  'tecnico@hospital.pt',            AES_ENCRYPT('password03', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 3, true,  '2024-03-01 09:10:00', '2024-03-01 09:10:00'),
(4,  'aprovisionamento@hospital.pt',   AES_ENCRYPT('password04', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 4, true,  '2024-03-01 09:15:00', '2024-03-01 09:15:00'),
(5,  'consulta@hospital.pt',           AES_ENCRYPT('password05', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 5, true,  '2024-03-01 09:20:00', '2024-03-01 09:20:00'),
(6,  'sofia.martins@hospital.pt',      AES_ENCRYPT('password06', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 1, true,  '2024-03-01 09:25:00', '2024-03-01 09:25:00'),
(7,  'ricardo.pereira@hospital.pt',    AES_ENCRYPT('password07', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 2, true,  '2024-03-01 09:30:00', '2024-03-01 09:30:00'),
(8,  'teresa.rodrigues@hospital.pt',   AES_ENCRYPT('password08', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 3, false, '2024-03-01 09:35:00', '2024-03-01 09:35:00'),
(9,  'carlos.almeida@hospital.pt',     AES_ENCRYPT('password09', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 4, true,  '2024-03-01 09:40:00', '2024-03-01 09:40:00'),
(10, 'beatriz.lopes@hospital.pt',      AES_ENCRYPT('password10', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 5, false, '2024-03-01 09:45:00', '2024-03-01 09:45:00');


-- ============================================================
-- FASE 3 — Tabelas com dependências multinível
-- ============================================================

-- 12. Equipamento (10)
INSERT INTO `Equipamento` (`idCategoria`, `codigoInterno`, `designacao`, `idMarca`, `modelo`, `numeroSerie`, `dataAquisicao`, `dataFabrico`, `custoAquisicao`, `tipoEntrada`, `estadoAtual`, `criticidade`, `observacoes`, `idLocalizacao`, `arquivado`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'IMG-001', 'Aparelho de Raio-X Digital Portátil',                    1,  'Mobilett Mira Max',      'SN-SIEM-20240001', '2024-03-15', '2023-11-01', 85000.00,  'Compra',      'Ativo',           'Alta',    'Equipamento principal de imagiologia portátil',       5,  false, true, '2024-03-15 10:00:00', '2024-03-15 10:00:00'),
(2,  'LAB-001', 'Analisador Hematológico Automático',                     2,  'CELL-DYN Ruby',          'SN-GE-20240002',   '2024-04-01', '2024-01-15', 45000.00,  'Compra',      'Ativo',           'Crítico', 'Análises hemograma completo — laboratório central',   6,  false, true, '2024-04-01 10:00:00', '2024-04-01 10:00:00'),
(3,  'MON-001', 'Monitor Multiparamétrico de Sinais Vitais',              3,  'IntelliVue MX800',       'SN-PHI-20240003',  '2024-05-01', '2024-02-01', 12000.00,  'Compra',      'Ativo',           'Alta',    'Monitor UCI com módulo capnografia',                  4,  false, true, '2024-05-01 10:00:00', '2024-05-01 10:00:00'),
(4,  'SVD-001', 'Ventilador de Cuidados Intensivos',                      4,  'Evita V800',             'SN-DRA-20240004',  '2024-05-15', '2024-03-01', 35000.00,  'Compra',      'Em manutenção',   'Crítico', 'Ventilação invasiva e não-invasiva UCI',              4,  false, true, '2024-05-15 10:00:00', '2025-12-01 14:00:00'),
(5,  'CIR-001', 'Bisturi Elétrico de Alta Frequência',                    6,  'ForceTriad',             'SN-MED-20240005',  '2024-06-01', '2024-01-20', 18000.00,  'Compra',      'Ativo',           'Média',   'Coagulação e corte no bloco operatório',              3,  false, true, '2024-06-01 10:00:00', '2024-06-01 10:00:00'),
(6,  'EST-001', 'Autoclave de Grande Capacidade',                         10, 'GE 6610',                'SN-GET-20240006',  '2024-06-15', '2023-12-01', 55000.00,  'Compra',      'Ativo',           'Alta',    'Esterilização central — capacidade 6 STU',            9,  false, true, '2024-06-15 10:00:00', '2024-06-15 10:00:00'),
(7,  'FIS-001', 'Aparelho de Ultrassons Terapêutico',                     5,  'US-751',                 'SN-MIN-20240007',  '2024-07-01', '2024-04-01', 3500.00,   'Doação',      'Ativo',           'Baixa',   'Fisioterapia — tratamento de lesões musculares',      8,  false, true, '2024-07-01 10:00:00', '2024-07-01 10:00:00'),
(8,  'OFT-001', 'Lâmpada de Fenda Digital',                               8,  'SL-D701',                'SN-FUJ-20240008',  '2024-07-15', '2024-05-01', 22000.00,  'Aluguer',     'Em calibração',   'Média',   'Diagnóstico oftalmológico com captura de imagem',     10, false, true, '2024-07-15 10:00:00', '2025-11-20 09:00:00'),
(9,  'NEO-001', 'Incubadora Neonatal de Cuidados Intensivos',             4,  'Babyleo TN500',          'SN-DRA-20240009',  '2024-08-01', '2024-06-01', 42000.00,  'Compra',      'Ativo',           'Crítico', 'UCI neonatal — controlo temperatura e humidade',      7,  false, true, '2024-08-01 10:00:00', '2024-08-01 10:00:00'),
(10, 'INF-001', 'Cama Articulada Elétrica com Balança',                   9,  'Dialog Plus',            'SN-BBR-20240010',  '2024-08-15', '2024-07-01', 8500.00,   'Empréstimo',  'Inativo',         'Baixa',   'Cama UCI com pesagem integrada e guardas laterais',   4,  true,  true, '2024-08-15 10:00:00', '2025-10-01 16:00:00');

-- 13. Componente (10)
INSERT INTO `Componente` (`codigoInterno`, `descricao`, `stock`, `stockMinimo`, `idLocalizacao`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('COMP-001', 'Filtro HEPA para ventilador',                    25,  5,  9,  true, '2024-05-01 10:00:00', '2024-05-01 10:00:00'),
('COMP-002', 'Sensor de SpO2 descartável adulto',              100, 20, 4,  true, '2024-05-01 10:05:00', '2024-05-01 10:05:00'),
('COMP-003', 'Braçadeira de pressão arterial reutilizável',    30,  10, 4,  true, '2024-05-01 10:10:00', '2024-05-01 10:10:00'),
('COMP-004', 'Tubo de raio-X de substituição',                 3,   1,  5,  true, '2024-05-01 10:15:00', '2024-05-01 10:15:00'),
('COMP-005', 'Kit de calibração de desfibrilhador',            8,   2,  1,  true, '2024-05-01 10:20:00', '2024-05-01 10:20:00'),
('COMP-006', 'Lâmpada halógena para lâmpada de fenda',         15,  5,  10, true, '2024-05-01 10:25:00', '2024-05-01 10:25:00'),
('COMP-007', 'Reagente hematológico (pack 500 testes)',         12,  3,  6,  true, '2024-05-01 10:30:00', '2024-05-01 10:30:00'),
('COMP-008', 'Bateria de substituição para monitor',            20,  5,  4,  true, '2024-05-01 10:35:00', '2024-05-01 10:35:00'),
('COMP-009', 'Vedante de autoclave (junta de porta)',            10,  3,  9,  true, '2024-05-01 10:40:00', '2024-05-01 10:40:00'),
('COMP-010', 'Cabo ECG de 5 derivações',                       40,  10, 2,  true, '2024-05-01 10:45:00', '2024-05-01 10:45:00');

-- 14. ComponenteEquipamento (10)
INSERT INTO `ComponenteEquipamento` (`idComponente`, `idEquipamento`, `quantidade`) VALUES
(1,  4,  2),   -- Filtro HEPA -> Ventilador
(2,  3,  3),   -- Sensor SpO2 -> Monitor
(3,  3,  1),   -- Braçadeira PA -> Monitor
(4,  1,  1),   -- Tubo raio-X -> Raio-X
(5,  5,  1),   -- Kit calibração -> Bisturi (desfibrilhador conceptual)
(6,  8,  2),   -- Lâmpada halógena -> Lâmpada de fenda
(7,  2,  1),   -- Reagente -> Analisador
(8,  3,  1),   -- Bateria -> Monitor
(9,  6,  2),   -- Vedante -> Autoclave
(10, 3,  1);   -- Cabo ECG -> Monitor

-- 15. ComponenteCategoria (10)
INSERT INTO `ComponenteCategoria` (`idComponente`, `idCategoria`) VALUES
(1,  4),   -- Filtro HEPA -> Suporte de Vida
(2,  3),   -- Sensor SpO2 -> Monitorização
(3,  3),   -- Braçadeira PA -> Monitorização
(4,  1),   -- Tubo raio-X -> Imagiologia
(5,  4),   -- Kit calibração -> Suporte de Vida
(6,  8),   -- Lâmpada halógena -> Oftalmologia
(7,  2),   -- Reagente -> Laboratório
(8,  3),   -- Bateria -> Monitorização
(9,  6),   -- Vedante -> Esterilização
(10, 3);   -- Cabo ECG -> Monitorização

-- 16. FornecedorEquipamento (10)
INSERT INTO `FornecedorEquipamento` (`idEquipamento`, `idFornecedor`, `dataAssociacao`, `ativo`) VALUES
(1,  1,  '2024-03-15 10:00:00', true),    -- Raio-X <-> Siemens
(2,  6,  '2024-04-01 10:00:00', true),    -- Analisador <-> GE Healthcare
(3,  2,  '2024-05-01 10:00:00', true),    -- Monitor <-> Philips
(4,  7,  '2024-05-15 10:00:00', true),    -- Ventilador <-> Dräger
(5,  6,  '2024-06-01 10:00:00', true),    -- Bisturi <-> GE (Medtronic via dist.)
(6,  10, '2024-06-15 10:00:00', true),    -- Autoclave <-> Getinge (via MedConsumables)
(7,  4,  '2024-07-01 10:00:00', true),    -- Ultrassons <-> BioEquip
(8,  8,  '2024-07-15 10:00:00', true),    -- Lâmpada fenda <-> Iberdata
(9,  7,  '2024-08-01 10:00:00', true),    -- Incubadora <-> Dräger
(10, 4,  '2024-08-15 10:00:00', true);    -- Cama <-> BioEquip

-- 17. Documento (10)
INSERT INTO `Documento` (`tipo`, `nome`, `caminhoFicheiro`, `dataDocumento`, `dataValidade`, `idEquipamento`, `idFornecedor`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Manual de Utilizador',              'Manual Mobilett Mira Max',                  '/docs/manuais/manual_mobilett.pdf',               '2023-11-01', NULL,         1,  1,  true, '2024-03-15 11:00:00', '2024-03-15 11:00:00'),
('Manual de Serviço',                 'Manual Serviço CELL-DYN Ruby',              '/docs/manuais/servico_celldyn.pdf',               '2024-01-15', NULL,         2,  6,  true, '2024-04-01 11:00:00', '2024-04-01 11:00:00'),
('Certificado de Calibração',         'Certificado Calibração IntelliVue MX800',   '/docs/certificados/calib_intellivue.pdf',         '2025-01-10', '2026-01-10', 3,  2,  true, '2025-01-10 11:00:00', '2025-01-10 11:00:00'),
('Contrato de Manutenção',            'Contrato Manutenção Evita V800',            '/docs/contratos/contrato_evita.pdf',              '2024-05-15', '2026-05-15', 4,  7,  true, '2024-05-15 11:00:00', '2024-05-15 11:00:00'),
('Fatura/Guia',                       'Fatura Compra ForceTriad',                  '/docs/faturas/fatura_forcetriad.pdf',             '2024-06-01', NULL,         5,  6,  true, '2024-06-01 11:00:00', '2024-06-01 11:00:00'),
('Declaração de Conformidade',        'Declaração CE Autoclave GE 6610',           '/docs/declaracoes/ce_autoclave.pdf',              '2023-12-01', NULL,         6,  10, true, '2024-06-15 11:00:00', '2024-06-15 11:00:00'),
('Relatório Técnico',                 'Relatório Inspeção US-751',                 '/docs/relatorios/inspecao_us751.pdf',             '2024-10-01', NULL,         7,  4,  true, '2024-10-01 11:00:00', '2024-10-01 11:00:00'),
('Garantia',                          'Certificado Garantia SL-D701',              '/docs/garantias/garantia_sld701.pdf',             '2024-07-15', '2027-07-15', 8,  8,  true, '2024-07-15 11:00:00', '2024-07-15 11:00:00'),
('Certificado de Calibração',         'Certificado Calibração Babyleo TN500',      '/docs/certificados/calib_babyleo.pdf',            '2025-02-01', '2026-02-01', 9,  7,  true, '2025-02-01 11:00:00', '2025-02-01 11:00:00'),
('Manual de Utilizador',              'Manual Dialog Plus',                        '/docs/manuais/manual_dialog.pdf',                 '2024-07-01', NULL,         10, 4,  true, '2024-08-15 11:00:00', '2024-08-15 11:00:00');

-- 18. GarantiaContrato (10)
INSERT INTO `GarantiaContrato` (`idEquipamento`, `idFornecedor`, `idDocumento`, `tipoRegisto`, `dataInicio`, `dataFim`, `periodicidade`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  1,  1,  'Garantia de Fábrica',       '2024-03-15', '2027-03-15', 'N/A',       'Garantia standard 3 anos Siemens',                         true, '2024-03-15 12:00:00', '2024-03-15 12:00:00'),
(2,  6,  2,  'Garantia de Fábrica',       '2024-04-01', '2026-04-01', 'N/A',       'Garantia 2 anos GE Healthcare',                            true, '2024-04-01 12:00:00', '2024-04-01 12:00:00'),
(3,  2,  3,  'Contrato de Manutenção',    '2025-01-01', '2026-01-01', 'Semestral', 'Contrato inclui calibração semestral',                      true, '2025-01-01 12:00:00', '2025-01-01 12:00:00'),
(4,  7,  4,  'Contrato de Manutenção',    '2024-05-15', '2026-05-15', 'Anual',     'Contrato manutenção preventiva anual Dräger',               true, '2024-05-15 12:00:00', '2024-05-15 12:00:00'),
(5,  6,  5,  'Garantia de Fábrica',       '2024-06-01', '2026-06-01', 'N/A',       'Garantia 2 anos Medtronic',                                 true, '2024-06-01 12:00:00', '2024-06-01 12:00:00'),
(6,  10, 6,  'Garantia de Fábrica',       '2024-06-15', '2027-06-15', 'N/A',       'Garantia 3 anos Getinge autoclave',                         true, '2024-06-15 12:00:00', '2024-06-15 12:00:00'),
(7,  4,  7,  'Garantia de Fábrica',       '2024-07-01', '2025-07-01', 'N/A',       'Garantia 1 ano Mindray (equipamento doado)',                true, '2024-07-01 12:00:00', '2024-07-01 12:00:00'),
(8,  8,  8,  'Contrato de Manutenção',    '2024-07-15', '2027-07-15', 'Mensal',    'Contrato aluguer c/ manutenção mensal incluída',            true, '2024-07-15 12:00:00', '2024-07-15 12:00:00'),
(9,  7,  9,  'Garantia de Fábrica',       '2024-08-01', '2027-08-01', 'N/A',       'Garantia 3 anos Dräger incubadora',                         true, '2024-08-01 12:00:00', '2024-08-01 12:00:00'),
(10, 4,  10, 'Contrato de Manutenção',    '2024-08-15', '2025-08-15', 'Semestral', 'Contrato empréstimo c/ revisão semestral',                  true, '2024-08-15 12:00:00', '2024-08-15 12:00:00');

-- 19. Manutencao (10)
INSERT INTO `Manutencao` (`idEquipamento`, `tipoManutencao`, `dataInicio`, `dataFim`, `idPessoaResponsavel`, `idFornecedor`, `custoManutencao`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Preventiva',   '2025-03-01', '2025-03-02', 3,  1,  1200.00,  'Manutenção preventiva anual — limpeza e calibração do tubo de raio-X',           true, '2025-03-01 08:00:00', '2025-03-02 17:00:00'),
(2,  'Calibração',   '2025-04-10', '2025-04-10', 2,  6,  800.00,   'Calibração semestral do analisador hematológico',                                true, '2025-04-10 08:00:00', '2025-04-10 16:00:00'),
(3,  'Preventiva',   '2025-07-01', '2025-07-01', 3,  2,  600.00,   'Verificação semestral de todos os módulos do monitor',                           true, '2025-07-01 08:00:00', '2025-07-01 16:00:00'),
(4,  'Corretiva',    '2025-11-15', NULL,         3,  7,  2500.00,  'Falha na válvula expiratória — substituição em curso',                            true, '2025-11-15 09:00:00', '2025-12-01 14:00:00'),
(5,  'Preventiva',   '2025-06-01', '2025-06-01', 2,  3,  450.00,   'Inspeção visual e teste de segurança elétrica',                                  true, '2025-06-01 08:00:00', '2025-06-01 14:00:00'),
(6,  'Calibração',   '2025-06-15', '2025-06-16', 3,  9,  1800.00,  'Calibração de sensores de temperatura e pressão da autoclave',                   true, '2025-06-15 08:00:00', '2025-06-16 12:00:00'),
(7,  'Corretiva',    '2025-09-10', '2025-09-12', 3,  4,  350.00,   'Substituição de cristal piezoeléctrico danificado',                               true, '2025-09-10 10:00:00', '2025-09-12 15:00:00'),
(8,  'Calibração',   '2025-11-20', NULL,         2,  9,  950.00,   'Calibração óptica da lâmpada de fenda — em progresso',                            true, '2025-11-20 09:00:00', '2025-11-20 09:00:00'),
(9,  'Preventiva',   '2025-08-01', '2025-08-02', 2,  7,  1100.00,  'Manutenção preventiva incubadora — sensores de temperatura e humidade',           true, '2025-08-01 08:00:00', '2025-08-02 17:00:00'),
(10, 'Corretiva',    '2025-10-01', '2025-10-03', 3,  4,  500.00,   'Reparação do mecanismo de elevação da cama articulada',                           true, '2025-10-01 08:00:00', '2025-10-03 14:00:00');


-- ============================================================
-- FASE 4 — Notificações, Auditoria e Pedidos de Demonstração
-- ============================================================

-- 20. Notificacao (10)
INSERT INTO `Notificacao` (`tipo`, `titulo`, `mensagem`, `tabelaReferencia`, `idRegistoReferencia`, `dataCriacao`) VALUES
('Garantia',     'Garantia a expirar',                     'A garantia do equipamento Raio-X Mobilett Mira Max expira em 90 dias.',                                         'GarantiaContrato', 1,  '2026-12-15 08:00:00'),
('Manutenção',   'Manutenção preventiva agendada',         'Manutenção preventiva do monitor IntelliVue MX800 agendada para 01/07/2025.',                                    'Manutencao',       3,  '2025-06-25 08:00:00'),
('Stock',        'Stock mínimo atingido',                  'O componente "Tubo de raio-X de substituição" atingiu o stock mínimo (3 unidades).',                              'Componente',       4,  '2025-08-01 08:00:00'),
('Calibração',   'Calibração pendente',                    'A lâmpada de fenda SL-D701 necessita de calibração óptica.',                                                     'Equipamento',      8,  '2025-11-15 08:00:00'),
('Sistema',      'Novo utilizador criado',                 'O utilizador Sofia Martins foi criado com o perfil Administrador.',                                               'Utilizador',       6,  '2024-03-01 09:25:00'),
('Garantia',     'Contrato de manutenção a renovar',       'O contrato de manutenção do ventilador Evita V800 expira em 30 dias.',                                            'GarantiaContrato', 4,  '2026-04-15 08:00:00'),
('Manutenção',   'Manutenção corretiva em curso',          'O ventilador Evita V800 encontra-se em manutenção corretiva desde 15/11/2025.',                                   'Manutencao',       4,  '2025-11-15 09:30:00'),
('Stock',        'Stock crítico — reagentes',              'O reagente hematológico (pack 500 testes) está com stock reduzido. Encomendar com urgência.',                      'Componente',       7,  '2025-10-15 08:00:00'),
('Calibração',   'Certificado de calibração expirado',     'O certificado de calibração da incubadora Babyleo TN500 expirou em 01/02/2026.',                                  'Documento',        9,  '2026-02-02 08:00:00'),
('Sistema',      'Equipamento arquivado',                  'A cama articulada Dialog Plus (INF-001) foi arquivada por inatividade prolongada.',                                'Equipamento',      10, '2025-10-01 16:00:00');

-- 21. NotificacaoUtilizador (10 — distribuídas entre utilizadores)
INSERT INTO `NotificacaoUtilizador` (`idNotificacao`, `idUtilizador`, `lida`, `dataAtualizacao`) VALUES
(1,  1,  false, '2026-12-15 08:00:00'),
(2,  3,  true,  '2025-06-26 09:00:00'),
(3,  4,  false, '2025-08-01 08:00:00'),
(4,  2,  false, '2025-11-15 08:00:00'),
(5,  1,  true,  '2024-03-01 10:00:00'),
(6,  1,  false, '2026-04-15 08:00:00'),
(7,  3,  true,  '2025-11-15 10:00:00'),
(8,  4,  false, '2025-10-15 08:00:00'),
(9,  2,  false, '2026-02-02 08:00:00'),
(10, 1,  true,  '2025-10-01 17:00:00');

-- 22. HistoricoAuditoria (10)
INSERT INTO `HistoricoAuditoria` (`idUtilizador`, `tabelaAfetada`, `idRegistoAfetado`, `acao`, `dadosAlterados`, `dataCriacao`) VALUES
(1,  'Equipamento',    1,  'Criação', '{"codigoInterno":"IMG-001","designacao":"Aparelho de Raio-X Digital Portátil"}',                        '2024-03-15 10:00:00'),
(1,  'Pessoa',         6,  'Criação', '{"nome":"Sofia Martins","email":"sofia.martins@hospital.pt"}',                                          '2024-03-01 09:25:00'),
(2,  'Manutencao',     1,  'Criação', '{"idEquipamento":1,"tipoManutencao":"Preventiva","dataInicio":"2025-03-01"}',                            '2025-03-01 08:00:00'),
(1,  'Utilizador',     8,  'Edição',  '{"campo":"estado","antigo":"Ativo","novo":"Inativo"}',                                                  '2025-06-01 14:00:00'),
(2,  'Equipamento',    4,  'Edição',  '{"campo":"estadoAtual","antigo":"Ativo","novo":"Em manutenção"}',                                       '2025-11-15 09:00:00'),
(1,  'Fornecedor',     5,  'Criação', '{"nome":"Lab Consumíveis Lda","tipoFornecedor":"Consumíveis"}',                                         '2024-04-01 10:20:00'),
(3,  'Manutencao',     7,  'Edição',  '{"campo":"dataFim","antigo":null,"novo":"2025-09-12"}',                                                 '2025-09-12 15:00:00'),
(1,  'Equipamento',    10, 'Edição',  '{"campo":"arquivado","antigo":false,"novo":true}',                                                      '2025-10-01 16:00:00'),
(2,  'Documento',      3,  'Criação', '{"tipo":"Certificado de Calibração","nome":"Certificado Calibração IntelliVue MX800"}',                  '2025-01-10 11:00:00'),
(1,  'Perfil',         5,  'Edição',  '{"campo":"nome","antigo":"Visualização","novo":"Consulta"}',                                            '2024-06-01 10:00:00');

-- 23. PedidoDemonstracao (5)
INSERT INTO `PedidoDemonstracao` (`nomeContacto`, `emailContacto`, `organizacao`, `mensagem`, `estado`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
('Dr. António Mendes',       'antonio.mendes@chup.pt',       'Centro Hospitalar Universitário do Porto',      'Gostaríamos de agendar uma demonstração do HEBA para o nosso departamento de engenharia clínica.',                 'Novo',         true, '2025-09-01 10:00:00', '2025-09-01 10:00:00'),
('Eng.ª Carla Ribeiro',      'carla.ribeiro@chleiria.pt',    'Centro Hospitalar de Leiria',                   'Estamos a avaliar soluções CMMS para substituir o nosso sistema atual. Poderiam fazer uma apresentação online?',    'Em Contacto',  true, '2025-09-15 14:00:00', '2025-09-20 10:00:00'),
('Dr.ª Inês Figueiredo',     'ines.fig@clinicadatrindade.pt','Clínica da Trindade',                           'Temos interesse em integrar o HEBA na nossa rede de clínicas. Contactem-nos para discutir preços.',                 'Fechado',      true, '2025-10-01 09:00:00', '2025-10-20 16:00:00'),
('Eng. Rui Nascimento',      'rui.nascimento@hff.min-saude.pt','Hospital Prof. Doutor Fernando Fonseca',      'Precisamos de uma solução para gestão de mais de 5000 equipamentos. Poderiam enviar um dossier técnico?',           'Em Contacto',  true, '2025-11-05 11:00:00', '2025-11-10 09:00:00'),
('Dr.ª Margarida Pinto',     'margarida.pinto@ulsm.pt',      'Unidade Local de Saúde de Matosinhos',          'Gostaríamos de saber se o HEBA se integra com o nosso ERP hospitalar Oracle.',                                      'Novo',         true, '2025-12-01 10:00:00', '2025-12-01 10:00:00');

-- ============================================================
-- FIM — Dados de teste inseridos com sucesso
-- ============================================================