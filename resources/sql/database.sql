USE currency_db;

-- Usuários:
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Histórico de Consultas:
CREATE TABLE currency_histories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    from_currency VARCHAR(10) NOT NULL, -- Ex: USD
    to_currency VARCHAR(10) NOT NULL,   -- Ex: BRL
    bid_value DECIMAL(10, 4) NOT NULL,  -- Valor de Compra
    ask_value DECIMAL(10, 4) NOT NULL,  -- Valor de Venda
    consulted_at TIMESTAMP NOT NULL,    -- Data da consulta retornada pela API
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

SELECT * FROM currency_histories;
