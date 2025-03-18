-- Création de la base de données
CREATE DATABASE construction;

-- Utilisation de la base de données
USE construction;

-- Création de la table
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image_url VARCHAR(255),
    category VARCHAR(100),
    title VARCHAR(255),
    project_date DATE,
    description TEXT
);

-- Insertion des 3 lignes de données
INSERT INTO projects (id, image_url, category, title, project_date, description) VALUES
(46, 'uploads/67d86158d71c5.jpeg', 'Construction Of Home', 'en attente', '2025-03-29', 'Construction of a two-story house with columns, fo...');

INSERT INTO projects (id, image_url, category, title, project_date, description) VALUES
(50, 'uploads/67d8615cb22b0.jpeg', 'Maintenance & Repair', 'terminé', '2025-03-12', 'Construction of a two-story house with columns, fo...');

INSERT INTO projects (id, image_url, category, title, project_date, description) VALUES
(53, 'uploads/67d80bc361163.jpg', 'House construction', 'terminé', '2025-03-05', 'Construction of a two-story house with columns, fo...');