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
INSERT INTO `CategoriaEquipamento` (`idCategoria`, `nome`, `descricao`, `codigoPrefix`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Imagiologia',             'Equipamentos de diagnóstico por imagem (raio-X, ecografia, TAC, RM)',      'IMG',   '2024-01-15 09:00:00', '2024-01-15 09:00:00'),
(2,  'Laboratório',             'Equipamentos de análises clínicas e laboratoriais',                         'LAB',   '2024-01-15 09:05:00', '2024-01-15 09:05:00'),
(3,  'Monitorização',           'Monitores de sinais vitais e telemetria',                                   'MON',   '2024-01-15 09:10:00', '2024-01-15 09:10:00'),
(4,  'Suporte de Vida',         'Ventiladores, desfibrilhadores e bombas de infusão',                        'SVD',   '2024-01-15 09:15:00', '2024-01-15 09:15:00'),
(5,  'Cirurgia',                'Instrumentação e equipamento de bloco operatório',                          'CIR',   '2024-01-15 09:20:00', '2024-01-15 09:20:00'),
(6,  'Esterilização',           'Autoclaves e equipamentos de descontaminação',                              'EST',   '2024-01-15 09:25:00', '2024-01-15 09:25:00'),
(7,  'Fisioterapia',            'Equipamentos de reabilitação e fisioterapia',                               'FIS',   '2024-01-15 09:30:00', '2024-01-15 09:30:00'),
(8,  'Oftalmologia',            'Equipamentos de diagnóstico e tratamento oftalmológico',                    'OFT',   '2024-01-15 09:35:00', '2024-01-15 09:35:00'),
(9,  'Neonatologia',            'Incubadoras e equipamentos de cuidados neonatais',                          'NEO',   '2024-01-15 09:40:00', '2024-01-15 09:40:00'),
(10, 'Infraestrutura Clínica',  'Camas articuladas, macas e mobiliário clínico especializado',               'INF',   '2024-01-15 09:45:00', '2024-01-15 09:45:00');

-- 2. Marca (10)
INSERT INTO `Marca` (`idMarca`, `nome`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Siemens Healthineers',  '2024-01-15 10:00:00', '2024-01-15 10:00:00'),
(2,  'GE Healthcare',         '2024-01-15 10:05:00', '2024-01-15 10:05:00'),
(3,  'Philips Healthcare',    '2024-01-15 10:10:00', '2024-01-15 10:10:00'),
(4,  'Dräger',                '2024-01-15 10:15:00', '2024-01-15 10:15:00'),
(5,  'Mindray',               '2024-01-15 10:20:00', '2024-01-15 10:20:00'),
(6,  'Medtronic',             '2024-01-15 10:25:00', '2024-01-15 10:25:00'),
(7,  'Olympus Medical',       '2024-01-15 10:30:00', '2024-01-15 10:30:00'),
(8,  'Fujifilm Healthcare',   '2024-01-15 10:35:00', '2024-01-15 10:35:00'),
(9,  'B. Braun',              '2024-01-15 10:40:00', '2024-01-15 10:40:00'),
(10, 'Getinge',               '2024-01-15 10:45:00', '2024-01-15 10:45:00');

-- 3. Localizacao (10)
INSERT INTO `Localizacao` (`idLocalizacao`, `edificio`, `piso`, `servico`, `sala`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Edifício Principal',  'Piso 0',   'Urgências',            'Sala 001',  true, '2024-02-01 08:00:00', '2024-02-01 08:00:00'),
(2,  'Edifício Principal',  'Piso 1',   'Cardiologia',          'Sala 101',  true, '2024-02-01 08:05:00', '2024-02-01 08:05:00'),
(3,  'Edifício Principal',  'Piso 2',   'Bloco Operatório',     'Sala 201',  true, '2024-02-01 08:10:00', '2024-02-01 08:10:00'),
(4,  'Edifício Principal',  'Piso 3',   'UCI',                  'Sala 301',  true, '2024-02-01 08:15:00', '2024-02-01 08:15:00'),
(5,  'Edifício Norte',      'Piso 0',   'Imagiologia',          'Sala 001',  true, '2024-02-01 08:20:00', '2024-02-01 08:20:00'),
(6,  'Edifício Norte',      'Piso 1',   'Laboratório Central',  'Sala 101',  true, '2024-02-01 08:25:00', '2024-02-01 08:25:00'),
(7,  'Edifício Norte',      'Piso 2',   'Neonatologia',         'Sala 201',  true, '2024-02-01 08:30:00', '2024-02-01 08:30:00'),
(8,  'Edifício Sul',        'Piso 0',   'Fisioterapia',         'Sala 001',  true, '2024-02-01 08:35:00', '2024-02-01 08:35:00'),
(9,  'Edifício Sul',        'Piso 1',   'Esterilização',        'Sala 101',  true, '2024-02-01 08:40:00', '2024-02-01 08:40:00'),
(10, 'Edifício Sul',        'Piso 2',   'Oftalmologia',         'Sala 201',  false,'2024-02-01 08:45:00', '2024-02-01 08:45:00');

-- 4. Permissao (25 — todas as chaves de profiles.php)
INSERT INTO `Permissao` (`idPermissao`, `chave`, `descricao`) VALUES
(1,  'equipment.view',       'Visualizar equipamentos'),
(2,  'equipment.create',     'Criar equipamentos'),
(3,  'equipment.edit',       'Editar equipamentos'),
(4,  'equipment.delete',     'Apagar equipamentos'),
(5,  'equipment.archive',    'Arquivar/restaurar equipamentos'),
(6,  'maintenance.view',     'Visualizar manutenções'),
(7,  'maintenance.create',   'Registar manutenções'),
(8,  'maintenance.edit',     'Editar manutenções'),
(9,  'maintenance.finalize', 'Finalizar manutenções'),
(10, 'documents.view',       'Visualizar documentos'),
(11, 'documents.upload',     'Carregar documentos'),
(12, 'documents.delete',     'Apagar documentos'),
(13, 'suppliers.view',       'Visualizar fornecedores'),
(14, 'suppliers.manage',     'Gerir fornecedores (CRUD)'),
(15, 'people.view',          'Visualizar pessoas'),
(16, 'people.manage',        'Gerir pessoas (CRUD)'),
(17, 'components.view',      'Visualizar componentes/stock'),
(18, 'components.manage',    'Gerir componentes (CRUD)'),
(19, 'users.view',           'Visualizar utilizadores'),
(20, 'users.manage',         'Gerir utilizadores (CRUD)'),
(21, 'audit.view',           'Visualizar logs de auditoria'),
(22, 'locations.view',       'Visualizar localizações'),
(23, 'locations.manage',     'Gerir localizações (CRUD)'),
(24, 'permissions.manage',   'Gerir permissões e perfis'),
(25, 'reports.generate',     'Gerar relatórios e exportar dados');

-- 5. Perfil (5 — conforme profiles.php)
INSERT INTO `Perfil` (`idPerfil`, `nome`, `dataCriacao`, `dataAtualizacao`) VALUES
(1, 'Administrador',            '2024-01-10 08:00:00', '2024-01-10 08:00:00'),
(2, 'Engenheiro Biomédico',     '2024-01-10 08:05:00', '2024-01-10 08:05:00'),
(3, 'Técnico de Manutenção',    '2024-01-10 08:10:00', '2024-01-10 08:10:00'),
(4, 'Aprovisionamento',         '2024-01-10 08:15:00', '2024-01-10 08:15:00'),
(5, 'Consulta',                 '2024-01-10 08:20:00', '2024-01-10 08:20:00');

-- 6. ConteudoFrontOffice (~40 registos — TODO o texto de index.php)
INSERT INTO `ConteudoFrontOffice` (`idConteudo`, `chaveSecao`, `valor`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
-- Navbar
(1,  'navbar.brand_name',                       'HEBA',                                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(2,  'navbar.link_funcionalidades',              'Funcionalidades',                                                                                                           true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(3,  'navbar.link_vantagens',                    'Vantagens',                                                                                                                 true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(4,  'navbar.btn_agendar_demo',                  'Agendar Demonstração',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Main Section (Hero)
(5,  'hero.badge',                               'A solução de liderança em Health Tech',                                                                                     true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(6,  'hero.title',                               'Gestão inteligente de equipamentos hospitalares',                                                                           true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(7,  'hero.subtitle',                            'Criado para a eficiência clínica, o HEBA unifica o seu inventário, manutenções e documentação num único painel inovador. Devolva o foco ao que importa: o cuidado com os doentes.', true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(8,  'hero.btn_agendar',                         'Agendar Demonstração',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(9,  'hero.btn_explorar',                        'Explorar Funcionalidades',                                                                                                  true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Features Section
(10, 'features.title',                           'Tudo o que o seu hospital precisa',                                                                                         true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(11, 'features.subtitle',                        'Uma plataforma desenhada exclusivamente para simplificar a logística, mitigar o risco clínico e garantir a mantenabilidade dos equipamentos.', true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(12, 'features.card1_title',                     'Inventário Centralizado',                                                                                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(13, 'features.card1_desc',                      'Registo exaustivo e rastreio completo de toda a gama de equipamentos médicos da unidade.',                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(14, 'features.card2_title',                     'Gestão de Manutenção (CMMS)',                                                                                               true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(15, 'features.card2_desc',                      'Planeamento de manutenções preventivas e ordens de trabalho automatizadas para a equipa.',                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(16, 'features.card3_title',                     'Controlo de Fornecedores',                                                                                                  true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(17, 'features.card3_desc',                      'Gestão integrada de contratos, garantias e acompanhamento de técnicos externos.',                                            true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(18, 'features.card4_title',                     'Gestão Documental',                                                                                                         true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(19, 'features.card4_desc',                      'Armazene centralmente os certificados, relatórios de calibração e manuais técnicos.',                                        true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(20, 'features.card5_title',                     'Dashboard Analítico',                                                                                                       true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(21, 'features.card5_desc',                      'Métricas vitais, relatórios customizados e estado do equipamento num ecrã central.',                                         true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(22, 'features.card6_title',                     'Assistente IA',                                                                                                              true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(23, 'features.card6_desc',                      'Recomendações preditivas e extração de dados através de Inteligência Artificial generativa.',                                true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Advantages Section
(24, 'advantages.badge',                         'Vantagens do Sistema',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(25, 'advantages.title',                         'Porquê escolher o HEBA?',                                                                                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(26, 'advantages.subtitle',                      'Em vez de adaptar softwares genéricos de armazém, desenhámos a nossa ferramenta com base nas complexidades reais enfrentadas por engenheiros clínicos e gestores operacionais.', true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(27, 'advantages.item1_title',                   'Segurança de Dados e Fiabilidade',                                                                                          true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(28, 'advantages.item1_desc',                    'A infraestrutura HEBA garante encriptação contínua e total conformidade com protocolos GDPR para plataformas de setor clínico.', true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(29, 'advantages.item2_title',                   'Conformidade Regulatória',                                                                                                  true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(30, 'advantages.item2_desc',                    'Auditorias facilitadas através do histórico inalterável e das notificações sobre prazos e certificações vencíveis.',          true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(31, 'advantages.item3_title',                   'Otimização Extrema de Tempo',                                                                                               true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(32, 'advantages.item3_desc',                    'Reduza a sobrecarga operacional da equipa técnica através da automação de processos de submissão de avarias.',                true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Clients Section
(33, 'clients.title',                            'A pensar em todo o tipo de prestadores',                                                                                    true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(34, 'clients.card1_title',                      'Inventário Centralizado',                                                                                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(35, 'clients.card1_desc',                       'Registo exaustivo e rastreio completo de toda a gama de equipamentos médicos da unidade.',                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(36, 'clients.card2_title',                      'Gestão de Manutenção (CMMS)',                                                                                               true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(37, 'clients.card2_desc',                       'Planeamento de manutenções preventivas e ordens de trabalho automatizadas para a equipa.',                                   true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(38, 'clients.card3_title',                      'Controlo de Fornecedores',                                                                                                  true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(39, 'clients.card3_desc',                       'Gestão integrada de contratos, garantias e acompanhamento de técnicos externos.',                                            true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- CTA Section
(40, 'cta.title',                                'Pronto para digitalizar?',                                                                                                  true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(41, 'cta.subtitle',                             'Preencha os dados e a nossa equipa agendará uma demonstração de produto de 30 minutos sem qualquer compromisso.',             true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(42, 'cta.label_nome',                           'Nome Completo',                                                                                                             true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(43, 'cta.placeholder_nome',                     'Introduza o seu nome',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(44, 'cta.label_email',                          'Email Profissional',                                                                                                        true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(45, 'cta.placeholder_email',                    'email@hospital.pt',                                                                                                         true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(46, 'cta.label_organizacao',                    'Organização / Unidade de Saúde',                                                                                            true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(47, 'cta.placeholder_organizacao',              'Nome do Hospital ou Clínica',                                                                                               true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(48, 'cta.btn_submit',                           'Pedir Demonstração Gratuita',                                                                                               true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
-- Footer
(49, 'footer.brand_name',                        'HEBA',                                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(50, 'footer.description',                       'A nova norma em Sistemas de Informação para a Gestão de Equipamentos Clínicos. Otimize e assegure o futuro do seu parque tecnológico hospitalar.', true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(51, 'footer.location',                          'Porto, Portugal',                                                                                                           true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(52, 'footer.email',                             'helena.b1210@gmail.com',                                                                                                    true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(53, 'footer.phone',                             '+351 912 951 772',                                                                                                          true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(54, 'footer.section_acesso_rapido',             'Acesso Rápido',                                                                                                             true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(55, 'footer.link_funcionalidades',              'Ver Funcionalidades',                                                                                                       true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(56, 'footer.link_vantagens',                    'Vantagens do Sistema',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(57, 'footer.link_demo',                         'Pedir Demonstração',                                                                                                        true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(58, 'footer.section_plataforma',                'Plataforma',                                                                                                                true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(59, 'footer.link_backoffice',                   'Aceder ao Backoffice',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(60, 'footer.link_termos',                       'Termos de Utilização',                                                                                                      true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(61, 'footer.link_privacidade',                  'Privacidade (RGPD)',                                                                                                        true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(62, 'footer.copyright',                         '© 2026 HEBA. Todos os direitos reservados.',                                                                                true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(63, 'footer.developer',                         'Desenvolvido por Helena Barbosa.',                                                                                          true, '2024-03-01 10:00:00', '2024-03-01 10:00:00');

-- 7. CartaoFuncionalidade (6 cartões de features de index.php)
INSERT INTO `CartaoFuncionalidade` (`idCartao`, `titulo`, `descricao`, `icone`, `ordem`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1, 'Inventário Centralizado',        'Registo exaustivo e rastreio completo de toda a gama de equipamentos médicos da unidade.',                       'package',            1, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(2, 'Gestão de Manutenção (CMMS)',    'Planeamento de manutenções preventivas e ordens de trabalho automatizadas para a equipa.',                       'wrench',             2, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(3, 'Controlo de Fornecedores',       'Gestão integrada de contratos, garantias e acompanhamento de técnicos externos.',                                'users',              3, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(4, 'Gestão Documental',             'Armazene centralmente os certificados, relatórios de calibração e manuais técnicos.',                             'file-text',          4, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(5, 'Dashboard Analítico',           'Métricas vitais, relatórios customizados e estado do equipamento num ecrã central.',                              'layout-dashboard',   5, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00'),
(6, 'Assistente IA',                 'Recomendações preditivas e extração de dados através de Inteligência Artificial generativa.',                     'sparkles',           6, true, '2024-03-01 10:00:00', '2024-03-01 10:00:00');


-- ============================================================
-- FASE 2 — Tabelas com FK de nível único
-- ============================================================

-- 8. Pessoa (10)
INSERT INTO `Pessoa` (`idPessoa`, `nome`, `email`, `contactoTelefonico`, `nif`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'João Silva',              'admin@hospital.pt',              '+351912000001', '123456789', true, '2024-03-01 09:00:00', '2024-03-01 09:00:00'),
(2,  'Ana Costa',               'eng.bio@hospital.pt',            '+351912000002', '234567891', true, '2024-03-01 09:05:00', '2024-03-01 09:05:00'),
(3,  'Miguel Santos',           'tecnico@hospital.pt',            '+351912000003', '345678912', true, '2024-03-01 09:10:00', '2024-03-01 09:10:00'),
(4,  'Maria Ferreira',          'aprovisionamento@hospital.pt',   '+351912000004', '456789123', true, '2024-03-01 09:15:00', '2024-03-01 09:15:00'),
(5,  'Pedro Oliveira',          'consulta@hospital.pt',           '+351912000005', '567891234', true, '2024-03-01 09:20:00', '2024-03-01 09:20:00'),
(6,  'Sofia Martins',           'sofia.martins@hospital.pt',      '+351912000006', '678912345', true, '2024-03-01 09:25:00', '2024-03-01 09:25:00'),
(7,  'Ricardo Pereira',         'ricardo.pereira@hospital.pt',    '+351912000007', '789123456', true, '2024-03-01 09:30:00', '2024-03-01 09:30:00'),
(8,  'Teresa Rodrigues',        'teresa.rodrigues@hospital.pt',   '+351912000008', '891234567', true, '2024-03-01 09:35:00', '2024-03-01 09:35:00'),
(9,  'Carlos Almeida',          'carlos.almeida@hospital.pt',     '+351912000009', '912345678', true, '2024-03-01 09:40:00', '2024-03-01 09:40:00'),
(10, 'Beatriz Lopes',           'beatriz.lopes@hospital.pt',      '+351912000010', '198765432', true, '2024-03-01 09:45:00', '2024-03-01 09:45:00');

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
INSERT INTO `Fornecedor` (`idFornecedor`, `nome`, `nifFornecedor`, `contactoTelefonico`, `email`, `morada`, `website`, `idPessoaResponsavel`, `tipoFornecedor`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Siemens Portugal',                '501234501', '+351210100001', 'vendas@siemens.pt',        'Rua do Ouro 120, 1100-063 Lisboa',          'https://www.siemens-healthineers.com/pt', 6,  'Fabricante',              'Fornecedor principal de imagiologia',                          true, '2024-04-01 10:00:00', '2024-04-01 10:00:00'),
(2,  'Philips Ibérica',                 '501234502', '+351210100002', 'info@philips.pt',          'Av. da Liberdade 200, 1250-147 Lisboa',     'https://www.philips.pt',                  7,  'Fabricante',              'Equipamento de monitorização e ventilação',                    true, '2024-04-01 10:05:00', '2024-04-01 10:05:00'),
(3,  'MedServ Assistência Técnica',     '501234503', '+351220300003', 'suporte@medserv.pt',       'Rua de Santa Catarina 450, 4000-450 Porto', 'https://www.medserv.pt',                  8,  'Assistência Técnica',     'Assistência técnica multimarca',                               true, '2024-04-01 10:10:00', '2024-04-01 10:10:00'),
(4,  'BioEquip Distribuição',           '501234504', '+351220300004', 'geral@bioequip.pt',        'Rua dos Clérigos 80, 4050-204 Porto',       'https://www.bioequip.pt',                 9,  'Distribuidor',            'Distribuidor regional de equipamento hospitalar',              true, '2024-04-01 10:15:00', '2024-04-01 10:15:00'),
(5,  'Lab Consumíveis Lda',             '501234505', '+351210100005', 'encomendas@labconsum.pt',  'Av. Brasil 101, 1700-066 Lisboa',           'https://www.labconsum.pt',                10, 'Consumíveis',             'Fornecedor de reagentes e consumíveis laboratoriais',          true, '2024-04-01 10:20:00', '2024-04-01 10:20:00'),
(6,  'GE Healthcare Portugal',          '501234506', '+351210100006', 'vendas@gehealthcare.pt',   'Parque das Nações, 1990-073 Lisboa',        'https://www.gehealthcare.com/pt',          6,  'Fabricante',              'Equipamento de imagem e ultrassonografia',                     true, '2024-04-01 10:25:00', '2024-04-01 10:25:00'),
(7,  'Dräger Portugal',                 '501234507', '+351210100007', 'info@draeger.pt',          'Rua Engenheiro Frederico Ulrich 2, Maia',   'https://www.draeger.com/pt_pt',            7,  'Fabricante',              'Ventilação, anestesia e monitorização de gases',               true, '2024-04-01 10:30:00', '2024-04-01 10:30:00'),
(8,  'Iberdata Equipamentos Médicos',   '501234508', '+351220300008', 'info@iberdata.pt',         'Rua do Almada 340, 4050-032 Porto',         'https://www.iberdata.pt',                 8,  'Distribuidor',            'Distribuidor multi-segmento de equipamentos clínicos',         true, '2024-04-01 10:35:00', '2024-04-01 10:35:00'),
(9,  'CalibTech Metrologia',            '501234509', '+351220300009', 'calibracoes@calibtech.pt', 'Zona Industrial de Maia, Lote 15',          'https://www.calibtech.pt',                9,  'Assistência Técnica',     'Calibrações e verificações metrológicas para equipamento médico', true, '2024-04-01 10:40:00', '2024-04-01 10:40:00'),
(10, 'MedConsumables S.A.',             '501234510', '+351210100010', 'vendas@medconsumables.pt', 'Rua Augusta 250, 1100-054 Lisboa',          'https://www.medconsumables.pt',            10, 'Consumíveis',             'Consumíveis descartáveis e acessórios médicos',                true, '2024-04-01 10:45:00', '2024-04-01 10:45:00');

-- 11. Utilizador (10 — passwords encriptadas com AES_ENCRYPT)
-- Passwords em texto: password01..password10
INSERT INTO `Utilizador` (`idUtilizador`, `idPessoa`, `password`, `idPerfil`, `estado`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  1,  AES_ENCRYPT('password01', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 1, 'Ativo',   true, '2024-03-01 09:00:00', '2024-03-01 09:00:00'),
(2,  2,  AES_ENCRYPT('password02', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 2, 'Ativo',   true, '2024-03-01 09:05:00', '2024-03-01 09:05:00'),
(3,  3,  AES_ENCRYPT('password03', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 3, 'Ativo',   true, '2024-03-01 09:10:00', '2024-03-01 09:10:00'),
(4,  4,  AES_ENCRYPT('password04', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 4, 'Ativo',   true, '2024-03-01 09:15:00', '2024-03-01 09:15:00'),
(5,  5,  AES_ENCRYPT('password05', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 5, 'Ativo',   true, '2024-03-01 09:20:00', '2024-03-01 09:20:00'),
(6,  6,  AES_ENCRYPT('password06', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 1, 'Ativo',   true, '2024-03-01 09:25:00', '2024-03-01 09:25:00'),
(7,  7,  AES_ENCRYPT('password07', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 2, 'Ativo',   true, '2024-03-01 09:30:00', '2024-03-01 09:30:00'),
(8,  8,  AES_ENCRYPT('password08', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 3, 'Inativo', true, '2024-03-01 09:35:00', '2024-03-01 09:35:00'),
(9,  9,  AES_ENCRYPT('password09', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 4, 'Ativo',   true, '2024-03-01 09:40:00', '2024-03-01 09:40:00'),
(10, 10, AES_ENCRYPT('password10', 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD'), 5, 'Inativo', true, '2024-03-01 09:45:00', '2024-03-01 09:45:00');


-- ============================================================
-- FASE 3 — Tabelas com dependências multinível
-- ============================================================

-- 12. Equipamento (10)
INSERT INTO `Equipamento` (`idEquipamento`, `idCategoria`, `codigoInterno`, `designacao`, `idMarca`, `modelo`, `numeroSerie`, `dataAquisicao`, `dataFabrico`, `custoAquisicao`, `tipoEntrada`, `estadoAtual`, `criticidade`, `observacoes`, `idLocalizacao`, `arquivado`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  1,  'IMG-001', 'Aparelho de Raio-X Digital Portátil',                    1,  'Mobilett Mira Max',      'SN-SIEM-20240001', '2024-03-15', '2023-11-01', 85000.00,  'Compra',      'Ativo',           'Alta',    'Equipamento principal de imagiologia portátil',       5,  false, true, '2024-03-15 10:00:00', '2024-03-15 10:00:00'),
(2,  2,  'LAB-001', 'Analisador Hematológico Automático',                     2,  'CELL-DYN Ruby',          'SN-GE-20240002',   '2024-04-01', '2024-01-15', 45000.00,  'Compra',      'Ativo',           'Crítico', 'Análises hemograma completo — laboratório central',   6,  false, true, '2024-04-01 10:00:00', '2024-04-01 10:00:00'),
(3,  3,  'MON-001', 'Monitor Multiparamétrico de Sinais Vitais',              3,  'IntelliVue MX800',       'SN-PHI-20240003',  '2024-05-01', '2024-02-01', 12000.00,  'Compra',      'Ativo',           'Alta',    'Monitor UCI com módulo capnografia',                  4,  false, true, '2024-05-01 10:00:00', '2024-05-01 10:00:00'),
(4,  4,  'SVD-001', 'Ventilador de Cuidados Intensivos',                      4,  'Evita V800',             'SN-DRA-20240004',  '2024-05-15', '2024-03-01', 35000.00,  'Compra',      'Em manutenção',   'Crítico', 'Ventilação invasiva e não-invasiva UCI',              4,  false, true, '2024-05-15 10:00:00', '2025-12-01 14:00:00'),
(5,  5,  'CIR-001', 'Bisturi Elétrico de Alta Frequência',                    6,  'ForceTriad',             'SN-MED-20240005',  '2024-06-01', '2024-01-20', 18000.00,  'Compra',      'Ativo',           'Média',   'Coagulação e corte no bloco operatório',              3,  false, true, '2024-06-01 10:00:00', '2024-06-01 10:00:00'),
(6,  6,  'EST-001', 'Autoclave de Grande Capacidade',                         10, 'GE 6610',                'SN-GET-20240006',  '2024-06-15', '2023-12-01', 55000.00,  'Compra',      'Ativo',           'Alta',    'Esterilização central — capacidade 6 STU',            9,  false, true, '2024-06-15 10:00:00', '2024-06-15 10:00:00'),
(7,  7,  'FIS-001', 'Aparelho de Ultrassons Terapêutico',                     5,  'US-751',                 'SN-MIN-20240007',  '2024-07-01', '2024-04-01', 3500.00,   'Doação',      'Ativo',           'Baixa',   'Fisioterapia — tratamento de lesões musculares',      8,  false, true, '2024-07-01 10:00:00', '2024-07-01 10:00:00'),
(8,  8,  'OFT-001', 'Lâmpada de Fenda Digital',                               8,  'SL-D701',                'SN-FUJ-20240008',  '2024-07-15', '2024-05-01', 22000.00,  'Aluguer',     'Em calibração',   'Média',   'Diagnóstico oftalmológico com captura de imagem',     10, false, true, '2024-07-15 10:00:00', '2025-11-20 09:00:00'),
(9,  9,  'NEO-001', 'Incubadora Neonatal de Cuidados Intensivos',             4,  'Babyleo TN500',          'SN-DRA-20240009',  '2024-08-01', '2024-06-01', 42000.00,  'Compra',      'Ativo',           'Crítico', 'UCI neonatal — controlo temperatura e humidade',      7,  false, true, '2024-08-01 10:00:00', '2024-08-01 10:00:00'),
(10, 10, 'INF-001', 'Cama Articulada Elétrica com Balança',                   9,  'Dialog Plus',            'SN-BBR-20240010',  '2024-08-15', '2024-07-01', 8500.00,   'Empréstimo',  'Inativo',         'Baixa',   'Cama UCI com pesagem integrada e guardas laterais',   4,  true,  true, '2024-08-15 10:00:00', '2025-10-01 16:00:00');

-- 13. Componente (10)
INSERT INTO `Componente` (`idComponente`, `codigoInterno`, `descricao`, `stock`, `stockMinimo`, `idLocalizacao`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'COMP-001', 'Filtro HEPA para ventilador',                    25,  5,  9,  true, '2024-05-01 10:00:00', '2024-05-01 10:00:00'),
(2,  'COMP-002', 'Sensor de SpO2 descartável adulto',              100, 20, 4,  true, '2024-05-01 10:05:00', '2024-05-01 10:05:00'),
(3,  'COMP-003', 'Braçadeira de pressão arterial reutilizável',    30,  10, 4,  true, '2024-05-01 10:10:00', '2024-05-01 10:10:00'),
(4,  'COMP-004', 'Tubo de raio-X de substituição',                 3,   1,  5,  true, '2024-05-01 10:15:00', '2024-05-01 10:15:00'),
(5,  'COMP-005', 'Kit de calibração de desfibrilhador',            8,   2,  1,  true, '2024-05-01 10:20:00', '2024-05-01 10:20:00'),
(6,  'COMP-006', 'Lâmpada halógena para lâmpada de fenda',         15,  5,  10, true, '2024-05-01 10:25:00', '2024-05-01 10:25:00'),
(7,  'COMP-007', 'Reagente hematológico (pack 500 testes)',         12,  3,  6,  true, '2024-05-01 10:30:00', '2024-05-01 10:30:00'),
(8,  'COMP-008', 'Bateria de substituição para monitor',            20,  5,  4,  true, '2024-05-01 10:35:00', '2024-05-01 10:35:00'),
(9,  'COMP-009', 'Vedante de autoclave (junta de porta)',            10,  3,  9,  true, '2024-05-01 10:40:00', '2024-05-01 10:40:00'),
(10, 'COMP-010', 'Cabo ECG de 5 derivações',                       40,  10, 2,  true, '2024-05-01 10:45:00', '2024-05-01 10:45:00');

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
INSERT INTO `Documento` (`idDocumento`, `tipo`, `nome`, `caminhoFicheiro`, `dataDocumento`, `dataValidade`, `idEquipamento`, `idFornecedor`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  'Manual de Utilizador',              'Manual Mobilett Mira Max',                  '/docs/manuais/manual_mobilett.pdf',               '2023-11-01', NULL,         1,  1,  true, '2024-03-15 11:00:00', '2024-03-15 11:00:00'),
(2,  'Manual de Serviço',                 'Manual Serviço CELL-DYN Ruby',              '/docs/manuais/servico_celldyn.pdf',               '2024-01-15', NULL,         2,  6,  true, '2024-04-01 11:00:00', '2024-04-01 11:00:00'),
(3,  'Certificado de Calibração',         'Certificado Calibração IntelliVue MX800',   '/docs/certificados/calib_intellivue.pdf',         '2025-01-10', '2026-01-10', 3,  2,  true, '2025-01-10 11:00:00', '2025-01-10 11:00:00'),
(4,  'Contrato de Manutenção',            'Contrato Manutenção Evita V800',            '/docs/contratos/contrato_evita.pdf',              '2024-05-15', '2026-05-15', 4,  7,  true, '2024-05-15 11:00:00', '2024-05-15 11:00:00'),
(5,  'Fatura/Guia',                       'Fatura Compra ForceTriad',                  '/docs/faturas/fatura_forcetriad.pdf',             '2024-06-01', NULL,         5,  6,  true, '2024-06-01 11:00:00', '2024-06-01 11:00:00'),
(6,  'Declaração de Conformidade',        'Declaração CE Autoclave GE 6610',           '/docs/declaracoes/ce_autoclave.pdf',              '2023-12-01', NULL,         6,  10, true, '2024-06-15 11:00:00', '2024-06-15 11:00:00'),
(7,  'Relatório Técnico',                 'Relatório Inspeção US-751',                 '/docs/relatorios/inspecao_us751.pdf',             '2024-10-01', NULL,         7,  4,  true, '2024-10-01 11:00:00', '2024-10-01 11:00:00'),
(8,  'Garantia',                          'Certificado Garantia SL-D701',              '/docs/garantias/garantia_sld701.pdf',             '2024-07-15', '2027-07-15', 8,  8,  true, '2024-07-15 11:00:00', '2024-07-15 11:00:00'),
(9,  'Certificado de Calibração',         'Certificado Calibração Babyleo TN500',      '/docs/certificados/calib_babyleo.pdf',            '2025-02-01', '2026-02-01', 9,  7,  true, '2025-02-01 11:00:00', '2025-02-01 11:00:00'),
(10, 'Manual de Utilizador',              'Manual Dialog Plus',                        '/docs/manuais/manual_dialog.pdf',                 '2024-07-01', NULL,         10, 4,  true, '2024-08-15 11:00:00', '2024-08-15 11:00:00');

-- 18. GarantiaContrato (10)
INSERT INTO `GarantiaContrato` (`idGarantiaContrato`, `idEquipamento`, `idFornecedor`, `idDocumento`, `tipoRegisto`, `dataInicio`, `dataFim`, `periodicidade`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  1,  1,  1,  'Garantia de Fábrica',       '2024-03-15', '2027-03-15', 'N/A',       'Garantia standard 3 anos Siemens',                         true, '2024-03-15 12:00:00', '2024-03-15 12:00:00'),
(2,  2,  6,  2,  'Garantia de Fábrica',       '2024-04-01', '2026-04-01', 'N/A',       'Garantia 2 anos GE Healthcare',                            true, '2024-04-01 12:00:00', '2024-04-01 12:00:00'),
(3,  3,  2,  3,  'Contrato de Manutenção',    '2025-01-01', '2026-01-01', 'Semestral', 'Contrato inclui calibração semestral',                      true, '2025-01-01 12:00:00', '2025-01-01 12:00:00'),
(4,  4,  7,  4,  'Contrato de Manutenção',    '2024-05-15', '2026-05-15', 'Anual',     'Contrato manutenção preventiva anual Dräger',               true, '2024-05-15 12:00:00', '2024-05-15 12:00:00'),
(5,  5,  6,  5,  'Garantia de Fábrica',       '2024-06-01', '2026-06-01', 'N/A',       'Garantia 2 anos Medtronic',                                 true, '2024-06-01 12:00:00', '2024-06-01 12:00:00'),
(6,  6,  10, 6,  'Garantia de Fábrica',       '2024-06-15', '2027-06-15', 'N/A',       'Garantia 3 anos Getinge autoclave',                         true, '2024-06-15 12:00:00', '2024-06-15 12:00:00'),
(7,  7,  4,  7,  'Garantia de Fábrica',       '2024-07-01', '2025-07-01', 'N/A',       'Garantia 1 ano Mindray (equipamento doado)',                true, '2024-07-01 12:00:00', '2024-07-01 12:00:00'),
(8,  8,  8,  8,  'Contrato de Manutenção',    '2024-07-15', '2027-07-15', 'Mensal',    'Contrato aluguer c/ manutenção mensal incluída',            true, '2024-07-15 12:00:00', '2024-07-15 12:00:00'),
(9,  9,  7,  9,  'Garantia de Fábrica',       '2024-08-01', '2027-08-01', 'N/A',       'Garantia 3 anos Dräger incubadora',                         true, '2024-08-01 12:00:00', '2024-08-01 12:00:00'),
(10, 10, 4,  10, 'Contrato de Manutenção',    '2024-08-15', '2025-08-15', 'Semestral', 'Contrato empréstimo c/ revisão semestral',                  true, '2024-08-15 12:00:00', '2024-08-15 12:00:00');

-- 19. Manutencao (10)
INSERT INTO `Manutencao` (`idManutencao`, `idEquipamento`, `tipoManutencao`, `dataInicio`, `dataFim`, `idPessoaResponsavel`, `idFornecedor`, `custoManutencao`, `observacoes`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1,  1,  'Preventiva',   '2025-03-01', '2025-03-02', 3,  1,  1200.00,  'Manutenção preventiva anual — limpeza e calibração do tubo de raio-X',           true, '2025-03-01 08:00:00', '2025-03-02 17:00:00'),
(2,  2,  'Calibração',   '2025-04-10', '2025-04-10', 2,  6,  800.00,   'Calibração semestral do analisador hematológico',                                true, '2025-04-10 08:00:00', '2025-04-10 16:00:00'),
(3,  3,  'Preventiva',   '2025-07-01', '2025-07-01', 3,  2,  600.00,   'Verificação semestral de todos os módulos do monitor',                           true, '2025-07-01 08:00:00', '2025-07-01 16:00:00'),
(4,  4,  'Corretiva',    '2025-11-15', NULL,         3,  7,  2500.00,  'Falha na válvula expiratória — substituição em curso',                            true, '2025-11-15 09:00:00', '2025-12-01 14:00:00'),
(5,  5,  'Preventiva',   '2025-06-01', '2025-06-01', 2,  3,  450.00,   'Inspeção visual e teste de segurança elétrica',                                  true, '2025-06-01 08:00:00', '2025-06-01 14:00:00'),
(6,  6,  'Calibração',   '2025-06-15', '2025-06-16', 3,  9,  1800.00,  'Calibração de sensores de temperatura e pressão da autoclave',                   true, '2025-06-15 08:00:00', '2025-06-16 12:00:00'),
(7,  7,  'Corretiva',    '2025-09-10', '2025-09-12', 3,  4,  350.00,   'Substituição de cristal piezoeléctrico danificado',                               true, '2025-09-10 10:00:00', '2025-09-12 15:00:00'),
(8,  8,  'Calibração',   '2025-11-20', NULL,         2,  9,  950.00,   'Calibração óptica da lâmpada de fenda — em progresso',                            true, '2025-11-20 09:00:00', '2025-11-20 09:00:00'),
(9,  9,  'Preventiva',   '2025-08-01', '2025-08-02', 2,  7,  1100.00,  'Manutenção preventiva incubadora — sensores de temperatura e humidade',           true, '2025-08-01 08:00:00', '2025-08-02 17:00:00'),
(10, 10, 'Corretiva',    '2025-10-01', '2025-10-03', 3,  4,  500.00,   'Reparação do mecanismo de elevação da cama articulada',                           true, '2025-10-01 08:00:00', '2025-10-03 14:00:00');


-- ============================================================
-- FASE 4 — Notificações, Auditoria e Pedidos de Demonstração
-- ============================================================

-- 20. Notificacao (10)
INSERT INTO `Notificacao` (`idNotificacao`, `tipo`, `titulo`, `mensagem`, `tabelaReferencia`, `idRegistoReferencia`, `dataCriacao`) VALUES
(1,  'Garantia',     'Garantia a expirar',                     'A garantia do equipamento Raio-X Mobilett Mira Max expira em 90 dias.',                                         'GarantiaContrato', 1,  '2026-12-15 08:00:00'),
(2,  'Manutenção',   'Manutenção preventiva agendada',         'Manutenção preventiva do monitor IntelliVue MX800 agendada para 01/07/2025.',                                    'Manutencao',       3,  '2025-06-25 08:00:00'),
(3,  'Stock',        'Stock mínimo atingido',                  'O componente "Tubo de raio-X de substituição" atingiu o stock mínimo (3 unidades).',                              'Componente',       4,  '2025-08-01 08:00:00'),
(4,  'Calibração',   'Calibração pendente',                    'A lâmpada de fenda SL-D701 necessita de calibração óptica.',                                                     'Equipamento',      8,  '2025-11-15 08:00:00'),
(5,  'Sistema',      'Novo utilizador criado',                 'O utilizador Sofia Martins foi criado com o perfil Administrador.',                                               'Utilizador',       6,  '2024-03-01 09:25:00'),
(6,  'Garantia',     'Contrato de manutenção a renovar',       'O contrato de manutenção do ventilador Evita V800 expira em 30 dias.',                                            'GarantiaContrato', 4,  '2026-04-15 08:00:00'),
(7,  'Manutenção',   'Manutenção corretiva em curso',          'O ventilador Evita V800 encontra-se em manutenção corretiva desde 15/11/2025.',                                   'Manutencao',       4,  '2025-11-15 09:30:00'),
(8,  'Stock',        'Stock crítico — reagentes',              'O reagente hematológico (pack 500 testes) está com stock reduzido. Encomendar com urgência.',                      'Componente',       7,  '2025-10-15 08:00:00'),
(9,  'Calibração',   'Certificado de calibração expirado',     'O certificado de calibração da incubadora Babyleo TN500 expirou em 01/02/2026.',                                  'Documento',        9,  '2026-02-02 08:00:00'),
(10, 'Sistema',      'Equipamento arquivado',                  'A cama articulada Dialog Plus (INF-001) foi arquivada por inatividade prolongada.',                                'Equipamento',      10, '2025-10-01 16:00:00');

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
INSERT INTO `HistoricoAuditoria` (`idAuditoria`, `idUtilizador`, `tabelaAfetada`, `idRegistoAfetado`, `acao`, `dadosAlterados`, `dataCriacao`) VALUES
(1,  1,  'Equipamento',    1,  'Criação', '{"codigoInterno":"IMG-001","designacao":"Aparelho de Raio-X Digital Portátil"}',                        '2024-03-15 10:00:00'),
(2,  1,  'Pessoa',         6,  'Criação', '{"nome":"Sofia Martins","email":"sofia.martins@hospital.pt"}',                                          '2024-03-01 09:25:00'),
(3,  2,  'Manutencao',     1,  'Criação', '{"idEquipamento":1,"tipoManutencao":"Preventiva","dataInicio":"2025-03-01"}',                            '2025-03-01 08:00:00'),
(4,  1,  'Utilizador',     8,  'Edição',  '{"campo":"estado","antigo":"Ativo","novo":"Inativo"}',                                                  '2025-06-01 14:00:00'),
(5,  2,  'Equipamento',    4,  'Edição',  '{"campo":"estadoAtual","antigo":"Ativo","novo":"Em manutenção"}',                                       '2025-11-15 09:00:00'),
(6,  1,  'Fornecedor',     5,  'Criação', '{"nome":"Lab Consumíveis Lda","tipoFornecedor":"Consumíveis"}',                                         '2024-04-01 10:20:00'),
(7,  3,  'Manutencao',     7,  'Edição',  '{"campo":"dataFim","antigo":null,"novo":"2025-09-12"}',                                                 '2025-09-12 15:00:00'),
(8,  1,  'Equipamento',    10, 'Edição',  '{"campo":"arquivado","antigo":false,"novo":true}',                                                      '2025-10-01 16:00:00'),
(9,  2,  'Documento',      3,  'Criação', '{"tipo":"Certificado de Calibração","nome":"Certificado Calibração IntelliVue MX800"}',                  '2025-01-10 11:00:00'),
(10, 1,  'Perfil',         5,  'Edição',  '{"campo":"nome","antigo":"Visualização","novo":"Consulta"}',                                            '2024-06-01 10:00:00');

-- 23. PedidoDemonstracao (5)
INSERT INTO `PedidoDemonstracao` (`idPedido`, `nomeContacto`, `emailContacto`, `organizacao`, `mensagem`, `estado`, `ativo`, `dataCriacao`, `dataAtualizacao`) VALUES
(1, 'Dr. António Mendes',       'antonio.mendes@chup.pt',       'Centro Hospitalar Universitário do Porto',      'Gostaríamos de agendar uma demonstração do HEBA para o nosso departamento de engenharia clínica.',                 'Novo',         true, '2025-09-01 10:00:00', '2025-09-01 10:00:00'),
(2, 'Eng.ª Carla Ribeiro',      'carla.ribeiro@chleiria.pt',    'Centro Hospitalar de Leiria',                   'Estamos a avaliar soluções CMMS para substituir o nosso sistema atual. Poderiam fazer uma apresentação online?',    'Em Contacto',  true, '2025-09-15 14:00:00', '2025-09-20 10:00:00'),
(3, 'Dr.ª Inês Figueiredo',     'ines.fig@clinicadatrindade.pt','Clínica da Trindade',                           'Temos interesse em integrar o HEBA na nossa rede de clínicas. Contactem-nos para discutir preços.',                 'Fechado',      true, '2025-10-01 09:00:00', '2025-10-20 16:00:00'),
(4, 'Eng. Rui Nascimento',      'rui.nascimento@hff.min-saude.pt','Hospital Prof. Doutor Fernando Fonseca',      'Precisamos de uma solução para gestão de mais de 5000 equipamentos. Poderiam enviar um dossier técnico?',           'Em Contacto',  true, '2025-11-05 11:00:00', '2025-11-10 09:00:00'),
(5, 'Dr.ª Margarida Pinto',     'margarida.pinto@ulsm.pt',      'Unidade Local de Saúde de Matosinhos',          'Gostaríamos de saber se o HEBA se integra com o nosso ERP hospitalar Oracle.',                                      'Novo',         true, '2025-12-01 10:00:00', '2025-12-01 10:00:00');

-- ============================================================
-- FIM — Dados de teste inseridos com sucesso
-- ============================================================