USE ranking_api;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS movement_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movement_id INT NOT NULL,
    value INT NOT NULL,
    date DATETIME NOT NULL,

    CONSTRAINT fk_user
        FOREIGN KEY (user_id) REFERENCES users(id),

    CONSTRAINT fk_movement
        FOREIGN KEY (movement_id) REFERENCES movements(id)
);

ALTER TABLE `personal_record` ADD CONSTRAINT
`personal_record_fk0` FOREIGN KEY (`user_id`) REFERENCES
`user`(`id`);
ALTER TABLE `personal_record` ADD CONSTRAINT
`personal_record_fk1` FOREIGN KEY (`movement_id`) REFERENCES
`movement`(`id`);

CREATE INDEX idx_movement_value
ON movement_records (movement_id, value);
