DROP TABLE IF EXISTS Users;

CREATE TABLE Users
(
    user_id  INT          NOT NULL AUTO_INCREMENT,
    username VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id)
);


INSERT INTO users (username, email, password)
VALUES ('testuser', 'test@extracker.com', '$2y$10$wE9N6gXbL8jTz0ZzZlG1Q.W.yP1zN7E6F2jC4B5P2C0F2D1A1');
