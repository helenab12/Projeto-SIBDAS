DROP DATABASE IF EXISTS `heba-db`;
CREATE DATABASE IF NOT EXISTS `heba-db`;
USE `heba-db`;

CREATE TABLE `CategoriaEquipamento` (
  `idCategoria` integer PRIMARY KEY,
  `nome` varchar(255),
  `descricao` text,
  `codigoPrefix` varchar(5),
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Marca` (
  `idMarca` integer PRIMARY KEY,
  `nome` varchar(255),
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Localizacao` (
  `idLocalizacao` integer PRIMARY KEY,
  `edificio` varchar(50),
  `piso` varchar(20),
  `servico` varchar(50),
  `sala` varchar(20),
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Equipamento` (
  `idEquipamento` integer PRIMARY KEY,
  `idCategoria` integer,
  `codigoInterno` varchar(20),
  `designacao` text,
  `idMarca` integer,
  `modelo` varchar(255),
  `numeroSerie` varchar(255),
  `dataAquisicao` date,
  `dataFabrico` date,
  `custoAquisicao` decimal(10,2),
  `tipoEntrada` ENUM ('Compra', 'Doação', 'Aluguer', 'Empréstimo'),
  `estadoAtual` ENUM ('Ativo', 'Em manutenção', 'Inativo', 'Em calibração', 'Em Quarentena', 'Abatido'),
  `criticidade` ENUM ('Baixa', 'Média', 'Alta', 'Crítico'),
  `observacoes` text,
  `idLocalizacao` integer,
  `arquivado` boolean DEFAULT false,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `FornecedorEquipamento` (
  `idEquipamento` integer,
  `idFornecedor` integer,
  `dataAssociacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `ativo` boolean DEFAULT true,
  PRIMARY KEY (`idEquipamento`, `idFornecedor`)
);

CREATE TABLE `Manutencao` (
  `idManutencao` integer PRIMARY KEY,
  `idEquipamento` integer,
  `tipoManutencao` ENUM ('Preventiva', 'Corretiva', 'Calibração'),
  `dataInicio` date,
  `dataFim` date,
  `idPessoaResponsavel` integer,
  `idFornecedor` integer,
  `custoManutencao` decimal(10,2),
  `observacoes` text,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Componente` (
  `idComponente` integer PRIMARY KEY,
  `codigoInterno` varchar(20),
  `descricao` text,
  `stock` integer,
  `stockMinimo` integer DEFAULT 0,
  `idLocalizacao` integer,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `ComponenteEquipamento` (
  `idComponente` integer,
  `idEquipamento` integer,
  `quantidade` integer,
  PRIMARY KEY (`idComponente`, `idEquipamento`)
);

CREATE TABLE `ComponenteCategoria` (
  `idComponente` integer,
  `idCategoria` integer,
  PRIMARY KEY (`idComponente`, `idCategoria`)
);

CREATE TABLE `Permissao` (
  `idPermissao` integer PRIMARY KEY,
  `chave` varchar(100) UNIQUE,
  `descricao` text
);

CREATE TABLE `Perfil` (
  `idPerfil` integer PRIMARY KEY,
  `nome` varchar(255),
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `PerfilPermissao` (
  `idPerfil` integer,
  `idPermissao` integer,
  `possui` boolean DEFAULT false,
  PRIMARY KEY (`idPerfil`, `idPermissao`)
);

CREATE TABLE `Pessoa` (
  `idPessoa` integer PRIMARY KEY,
  `nome` varchar(255),
  `email` varchar(100) UNIQUE,
  `contactoTelefonico` varchar(15),
  `nif` varchar(9),
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Utilizador` (
  `idUtilizador` integer PRIMARY KEY,
  `idPessoa` integer,
  `password` varbinary(255),
  `idPerfil` integer,
  `estado` ENUM ('Ativo', 'Inativo'),
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Fornecedor` (
  `idFornecedor` integer PRIMARY KEY,
  `nome` varchar(255),
  `nifFornecedor` varchar(9),
  `contactoTelefonico` varchar(15),
  `email` varchar(100),
  `morada` text,
  `website` varchar(255),
  `idPessoaResponsavel` integer,
  `tipoFornecedor` ENUM ('Fabricante', 'Distribuidor', 'Assistência Técnica', 'Consumíveis'),
  `observacoes` text,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `Documento` (
  `idDocumento` integer PRIMARY KEY,
  `tipo` ENUM ('Manual de Utilizador', 'Manual de Serviço', 'Certificado de Calibração', 'Contrato de Manutenção', 'Fatura/Guia', 'Declaração de Conformidade', 'Relatório Técnico', 'Garantia'),
  `nome` varchar(255),
  `caminhoFicheiro` varchar(255),
  `dataDocumento` date,
  `dataValidade` date,
  `idEquipamento` integer,
  `idFornecedor` integer,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `GarantiaContrato` (
  `idGarantiaContrato` integer PRIMARY KEY,
  `idEquipamento` integer,
  `idFornecedor` integer,
  `idDocumento` integer,
  `tipoRegisto` ENUM ('Garantia de Fábrica', 'Contrato de Manutenção'),
  `dataInicio` date,
  `dataFim` date,
  `periodicidade` ENUM ('Mensal', 'Semestral', 'Anual', 'N/A'),
  `observacoes` text,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `HistoricoAuditoria` (
  `idAuditoria` integer PRIMARY KEY,
  `idUtilizador` integer,
  `tabelaAfetada` varchar(50),
  `idRegistoAfetado` integer,
  `acao` ENUM ('Criação', 'Edição', 'Remoção'),
  `dadosAlterados` json,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `Notificacao` (
  `idNotificacao` integer PRIMARY KEY,
  `tipo` ENUM ('Garantia', 'Manutenção', 'Stock', 'Calibração', 'Sistema'),
  `titulo` varchar(255),
  `mensagem` text,
  `tabelaReferencia` varchar(50),
  `idRegistoReferencia` integer,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `NotificacaoUtilizador` (
  `idNotificacao` integer,
  `idUtilizador` integer,
  `lida` boolean DEFAULT false,
  `dataAtualizacao` timestamp,
  PRIMARY KEY (`idNotificacao`, `idUtilizador`)
);

CREATE TABLE `ConteudoFrontOffice` (
  `idConteudo` integer PRIMARY KEY,
  `chaveSecao` varchar(100) UNIQUE,
  `valor` text,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `CartaoFuncionalidade` (
  `idCartao` integer PRIMARY KEY,
  `titulo` varchar(255),
  `descricao` text,
  `icone` text,
  `ordem` integer,
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE TABLE `PedidoDemonstracao` (
  `idPedido` integer PRIMARY KEY,
  `nomeContacto` varchar(255),
  `emailContacto` varchar(100),
  `organizacao` varchar(255),
  `mensagem` text,
  `estado` ENUM ('Novo', 'Em Contacto', 'Fechado') DEFAULT 'Novo',
  `ativo` boolean DEFAULT true,
  `dataCriacao` timestamp DEFAULT (CURRENT_TIMESTAMP),
  `dataAtualizacao` timestamp
);

CREATE UNIQUE INDEX `Localizacao_index_0` ON `Localizacao` (`edificio`, `piso`, `servico`, `sala`);

CREATE UNIQUE INDEX `Equipamento_index_1` ON `Equipamento` (`numeroSerie`, `idMarca`, `modelo`, `designacao`);

ALTER TABLE `Equipamento` ADD FOREIGN KEY (`idCategoria`) REFERENCES `CategoriaEquipamento` (`idCategoria`);

ALTER TABLE `Equipamento` ADD FOREIGN KEY (`idMarca`) REFERENCES `Marca` (`idMarca`);

ALTER TABLE `Equipamento` ADD FOREIGN KEY (`idLocalizacao`) REFERENCES `Localizacao` (`idLocalizacao`);

ALTER TABLE `Componente` ADD FOREIGN KEY (`idLocalizacao`) REFERENCES `Localizacao` (`idLocalizacao`);

ALTER TABLE `ComponenteEquipamento` ADD FOREIGN KEY (`idComponente`) REFERENCES `Componente` (`idComponente`);

ALTER TABLE `ComponenteEquipamento` ADD FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamento` (`idEquipamento`);

ALTER TABLE `ComponenteCategoria` ADD FOREIGN KEY (`idComponente`) REFERENCES `Componente` (`idComponente`);

ALTER TABLE `ComponenteCategoria` ADD FOREIGN KEY (`idCategoria`) REFERENCES `CategoriaEquipamento` (`idCategoria`);

ALTER TABLE `Manutencao` ADD FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamento` (`idEquipamento`);

ALTER TABLE `Manutencao` ADD FOREIGN KEY (`idPessoaResponsavel`) REFERENCES `Pessoa` (`idPessoa`);

ALTER TABLE `Manutencao` ADD FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedor` (`idFornecedor`);

ALTER TABLE `Utilizador` ADD FOREIGN KEY (`idPessoa`) REFERENCES `Pessoa` (`idPessoa`);

ALTER TABLE `Utilizador` ADD FOREIGN KEY (`idPerfil`) REFERENCES `Perfil` (`idPerfil`);

ALTER TABLE `PerfilPermissao` ADD FOREIGN KEY (`idPerfil`) REFERENCES `Perfil` (`idPerfil`);

ALTER TABLE `PerfilPermissao` ADD FOREIGN KEY (`idPermissao`) REFERENCES `Permissao` (`idPermissao`);

ALTER TABLE `FornecedorEquipamento` ADD FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamento` (`idEquipamento`);

ALTER TABLE `FornecedorEquipamento` ADD FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedor` (`idFornecedor`);

ALTER TABLE `Fornecedor` ADD FOREIGN KEY (`idPessoaResponsavel`) REFERENCES `Pessoa` (`idPessoa`);

ALTER TABLE `Documento` ADD FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamento` (`idEquipamento`);

ALTER TABLE `Documento` ADD FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedor` (`idFornecedor`);

ALTER TABLE `GarantiaContrato` ADD FOREIGN KEY (`idEquipamento`) REFERENCES `Equipamento` (`idEquipamento`);

ALTER TABLE `GarantiaContrato` ADD FOREIGN KEY (`idFornecedor`) REFERENCES `Fornecedor` (`idFornecedor`);

ALTER TABLE `GarantiaContrato` ADD FOREIGN KEY (`idDocumento`) REFERENCES `Documento` (`idDocumento`);

ALTER TABLE `HistoricoAuditoria` ADD FOREIGN KEY (`idUtilizador`) REFERENCES `Utilizador` (`idUtilizador`);

ALTER TABLE `NotificacaoUtilizador` ADD FOREIGN KEY (`idNotificacao`) REFERENCES `Notificacao` (`idNotificacao`);

ALTER TABLE `NotificacaoUtilizador` ADD FOREIGN KEY (`idUtilizador`) REFERENCES `Utilizador` (`idUtilizador`);