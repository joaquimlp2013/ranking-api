USE ranking_api;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS movement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS personal_record (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movement_id INT NOT NULL,
    value INT NOT NULL,
    date DATETIME NOT NULL,

    CONSTRAINT fk_user
        FOREIGN KEY (user_id) REFERENCES user(id),

    CONSTRAINT fk_movement
        FOREIGN KEY (movement_id) REFERENCES movement(id)
);

CREATE INDEX idx_movement_value
ON personal_record (movement_id, value);
