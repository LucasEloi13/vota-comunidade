CREATE DATABASE IF NOT EXISTS vota_comunidade;
USE vota_comunidade;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14),
    tipo ENUM('admin', 'sindico', 'morador') NOT NULL,
    status ENUM('pendente', 'ativo', 'rejeitado') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pautas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255),
    descricao TEXT,
    data_inicio DATETIME,
    data_fim DATETIME,
    id_sindico INT,
    FOREIGN KEY (id_sindico) REFERENCES usuarios(id)
);

CREATE TABLE votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pauta INT,
    id_morador INT,
    opcao_escolhida VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pauta) REFERENCES pautas(id),
    FOREIGN KEY (id_morador) REFERENCES usuarios(id)
);
