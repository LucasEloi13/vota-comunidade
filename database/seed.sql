USE vota_comunidade;

-- Usuários iniciais
INSERT INTO usuarios (nome, email, senha, cpf, tipo, status) VALUES
('Admin Geral', 'admin@vota.com', SHA2('admin123', 256), '000.000.000-00', 'admin', 'ativo'),
('João Síndico', 'joao@vota.com', SHA2('sindico123', 256), '111.111.111-11', 'sindico', 'ativo'),
('Maria Moradora', 'maria@vota.com', SHA2('morador123', 256), '222.222.222-22', 'morador', 'ativo');

-- Pauta de exemplo
INSERT INTO pautas (titulo, descricao, data_inicio, data_fim, id_sindico)
VALUES ('Trocar portão do prédio?', 'Decidir sobre a troca do portão frontal.', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 2);
