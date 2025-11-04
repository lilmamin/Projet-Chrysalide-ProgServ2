-- DATABASE
CREATE DATABASE IF NOT EXISTS chrysalide CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chrysalide;

-- TABLE USERS
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('reader','author') NOT NULL DEFAULT 'reader',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- TABLE STORIES
CREATE TABLE IF NOT EXISTS stories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    genre ENUM('romance','action','historical','fantasy','horror','other') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLE CHAPTERS
CREATE TABLE IF NOT EXISTS chapters (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    story_id INT UNSIGNED NOT NULL,
    number INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE,
    UNIQUE (story_id, number)
) ENGINE=InnoDB;

-- TABLE LIKES
CREATE TABLE IF NOT EXISTS likes (
    user_id INT UNSIGNED NOT NULL,
    story_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, story_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLE FOLLOWS
CREATE TABLE IF NOT EXISTS follows (
    follower_id INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (follower_id, author_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- TABLE LIBRARY
CREATE TABLE IF NOT EXISTS library (
    user_id INT UNSIGNED NOT NULL,
    story_id INT UNSIGNED NOT NULL,
    status ENUM('saved','reading','finished') NOT NULL DEFAULT 'saved',
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, story_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (story_id) REFERENCES stories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- SEEDS USERS
INSERT INTO users (username, email, password_hash, role, created_at)
VALUES
('LunaWriter', 'luna@example.com', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12', 'author', NOW()),
('NoahReader', 'noah@example.com', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12', 'reader', NOW()),
('AyaDreams', 'aya@example.com', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12', 'author', NOW()),
('MiloReads', 'milo@example.com', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890abcdef12', 'reader', NOW());

-- SEEDS STORIES
INSERT INTO stories (author_id, title, summary, cover_image, genre, created_at)
VALUES
(1, 'Éclats de Lune', 'Une jeune fille découvre un monde parallèle éclairé par des lunes multiples.', 'covers/lune.jpg', 'fantasy', NOW()),
(3, 'Les Silences d’Aya', 'Chroniques d’une autrice en quête de sa voix dans un monde bruyant.', 'covers/aya.jpg', 'romance', NOW());

-- SEEDS CHAPTERS
INSERT INTO chapters (story_id, number, title, content, created_at)
VALUES
(1, 1, 'Sous la première lune', 'Le ciel se fendit et révéla une lumière inconnue...', NOW()),
(1, 2, 'Reflets d’argent', 'Les lunes dansaient au-dessus du lac, et tout bascula.', NOW()),
(2, 1, 'Un murmure dans la foule', 'Aya sentait son cœur battre plus fort que les voix autour d’elle.', NOW());

-- SEEDS LIKES
INSERT INTO likes (user_id, story_id, created_at)
VALUES
(2, 1, NOW()),
(4, 1, NOW()),
(2, 2, NOW());

-- SEEDS FOLLOWS
INSERT INTO follows (follower_id, author_id, created_at)
VALUES
(2, 1
