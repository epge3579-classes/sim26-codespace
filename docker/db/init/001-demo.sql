CREATE TABLE IF NOT EXISTS demo_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    label VARCHAR(100) NOT NULL UNIQUE
);

INSERT IGNORE INTO demo_items (id, label) VALUES
    (1, 'PHP container'),
    (2, 'Nginx container'),
    (3, 'MariaDB container');
