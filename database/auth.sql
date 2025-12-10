DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS users;

CREATE TABLE users
(
    id         INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE sessions
(
    id            VARCHAR(128) NOT NULL PRIMARY KEY,
    last_activity INT(11) NOT NULL,
    data          TEXT         NOT NULL,
    KEY           idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (username, email, password)
VALUES ('testuser', 'test@extracker.com', '$2y$10$wE9N6gXbL8jTz0ZzZlG1Q.W.yP1zN7E6F2jC4B5P2C0F2D1A1');