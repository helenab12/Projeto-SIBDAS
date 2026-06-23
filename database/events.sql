-- Eventos de Notificações Automáticas

DELIMITER //

-- --------------------------------------------------------------------
-- 1. Notificar garantias a expirar em 30 dias
-- --------------------------------------------------------------------
DROP EVENT IF EXISTS evt_notificar_garantias_expirar //

CREATE EVENT evt_notificar_garantias_expirar
ON SCHEDULE EVERY 1 DAY STARTS '2026-06-21 00:00:00'
ON COMPLETION NOT PRESERVE ENABLE 
DO BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_idGarantia INT;
    DECLARE v_idEquipamento INT;
    DECLARE v_dataFim DATE;
    DECLARE v_idNotificacao INT;
    DECLARE v_codigoInterno VARCHAR(20);
    
    DECLARE cur_garantias CURSOR FOR 
        SELECT g.idGarantiaContrato, g.idEquipamento, g.dataFim, e.codigoInterno
        FROM GarantiaContrato g
        INNER JOIN Equipamento e ON g.idEquipamento = e.idEquipamento
        WHERE g.ativo = 1 AND e.ativo = 1 AND g.dataFim = CURDATE() + INTERVAL 30 DAY;
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur_garantias;
    
    read_loop: LOOP
        FETCH cur_garantias INTO v_idGarantia, v_idEquipamento, v_dataFim, v_codigoInterno;
        
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Criar a notificação
        INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia)
        VALUES (
            'Garantia', 
            'Garantia a Expirar', 
            CONCAT('A garantia do equipamento ', v_codigoInterno, ' irá expirar no dia ', DATE_FORMAT(v_dataFim, '%d/%m/%Y'), ' (daqui a 30 dias).'), 
            'GarantiaContrato', 
            v_idGarantia
        );
        
        SET v_idNotificacao = LAST_INSERT_ID();
        
        -- Distribuir a notificação por todos os utilizadores ativos
        INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao)
        SELECT v_idNotificacao, idUtilizador, 0, CURRENT_TIMESTAMP
        FROM Utilizador
        WHERE ativo = 1;
        
    END LOOP;
    
    CLOSE cur_garantias;
END //

-- --------------------------------------------------------------------
-- 2. Notificar manutenções agendadas para os próximos 7 dias
-- --------------------------------------------------------------------
DROP EVENT IF EXISTS evt_notificar_manutencao_proxima //

CREATE EVENT evt_notificar_manutencao_proxima
ON SCHEDULE EVERY 1 DAY STARTS '2026-06-21 00:00:00'
ON COMPLETION NOT PRESERVE ENABLE 
DO BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_idManutencao INT;
    DECLARE v_idEquipamento INT;
    DECLARE v_tipoManutencao VARCHAR(50);
    DECLARE v_dataInicio DATE;
    DECLARE v_idNotificacao INT;
    DECLARE v_codigoInterno VARCHAR(20);
    
    DECLARE cur_manutencoes CURSOR FOR 
        SELECT m.idManutencao, m.idEquipamento, m.tipoManutencao, m.dataInicio, e.codigoInterno
        FROM Manutencao m
        INNER JOIN Equipamento e ON m.idEquipamento = e.idEquipamento
        WHERE m.ativo = 1 
          AND m.dataInicio BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY
          AND e.ativo = 1
          AND NOT EXISTS (
              SELECT 1 FROM Notificacao n
              WHERE n.tabelaReferencia = 'Manutencao'
                AND n.idRegistoReferencia = m.idManutencao
                AND n.dataCriacao >= CURDATE() - INTERVAL 7 DAY
          );
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur_manutencoes;
    
    read_loop: LOOP
        FETCH cur_manutencoes INTO v_idManutencao, v_idEquipamento, v_tipoManutencao, v_dataInicio, v_codigoInterno;
        IF done THEN LEAVE read_loop; END IF;
        
        INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia)
        VALUES (
            'Manutenção',
            CONCAT('Manutenção ', v_tipoManutencao, ' Agendada'),
            CONCAT('A manutenção ', LOWER(v_tipoManutencao), ' do equipamento ', v_codigoInterno,
                   ' está agendada para ', DATE_FORMAT(v_dataInicio, '%d/%m/%Y'), '.'),
            'Manutencao',
            v_idManutencao
        );
        
        SET v_idNotificacao = LAST_INSERT_ID();
        
        INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao)
        SELECT v_idNotificacao, idUtilizador, 0, CURRENT_TIMESTAMP
        FROM Utilizador WHERE ativo = 1;
        
    END LOOP;
    CLOSE cur_manutencoes;
END //

-- --------------------------------------------------------------------
-- 3. Notificar stock crítico de componentes
-- --------------------------------------------------------------------
DROP EVENT IF EXISTS evt_notificar_stock_critico //

CREATE EVENT evt_notificar_stock_critico
ON SCHEDULE EVERY 1 DAY STARTS '2026-06-21 00:00:00'
ON COMPLETION NOT PRESERVE ENABLE 
DO BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_idComponente INT;
    DECLARE v_codigoInterno VARCHAR(20);
    DECLARE v_stock INT;
    DECLARE v_stockMinimo INT;
    DECLARE v_idNotificacao INT;
    
    DECLARE cur_componentes CURSOR FOR
        SELECT idComponente, codigoInterno, stock, stockMinimo
        FROM Componente
        WHERE ativo = 1 AND stock <= stockMinimo
          AND NOT EXISTS (
              SELECT 1 FROM Notificacao n
              WHERE n.tabelaReferencia = 'Componente'
                AND n.idRegistoReferencia = Componente.idComponente
                AND n.dataCriacao >= CURDATE() - INTERVAL 7 DAY
          );
        
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur_componentes;
    
    read_loop: LOOP
        FETCH cur_componentes INTO v_idComponente, v_codigoInterno, v_stock, v_stockMinimo;
        IF done THEN LEAVE read_loop; END IF;
        
        INSERT INTO Notificacao (tipo, titulo, mensagem, tabelaReferencia, idRegistoReferencia)
        VALUES (
            'Stock',
            'Aviso de Stock Crítico',
            CONCAT('O componente ', v_codigoInterno, ' atingiu o nível crítico de stock (',
                   v_stock, '/', v_stockMinimo, ').'),
            'Componente',
            v_idComponente
        );
        
        SET v_idNotificacao = LAST_INSERT_ID();
        
        INSERT INTO NotificacaoUtilizador (idNotificacao, idUtilizador, lida, dataAtualizacao)
        SELECT v_idNotificacao, idUtilizador, 0, CURRENT_TIMESTAMP
        FROM Utilizador WHERE ativo = 1;
        
    END LOOP;
    CLOSE cur_componentes;
END //

DELIMITER ;
