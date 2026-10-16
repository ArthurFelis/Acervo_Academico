-- Importe este arquivo no phpMyAdmin da InfinityFree (selecione seu banco antes).

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS artigos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    ano SMALLINT NULL,
    paginas SMALLINT NULL,
    autores VARCHAR(255) NOT NULL,
    arquivo VARCHAR(100) NOT NULL,
    nome_original VARCHAR(200) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FULLTEXT KEY ft_busca (titulo, descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
