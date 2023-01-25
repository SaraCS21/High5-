CREATE DATABASE IF NOT EXISTS Foro;
USE Foro;

CREATE TABLE IF NOT EXISTS person (
    id INT AUTO_INCREMENT,
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(70) NOT NULL,
    age INT NOT NULL,
    type ENUM("user", "admin") NOT NULL,
    block ENUM("block", "unblock") NOT NULL,

    PRIMARY KEY(id, email)
);

CREATE TABLE IF NOT EXISTS post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content VARCHAR(500) NOT NULL,
    title VARCHAR(40) NOT NULL,
    theme ENUM("Ocio", "Videojuegos", "Eventos", "Vida diaria", "Familias", "Viajes") NOT NULL,
    publicationDate DATE NOT NULL,
    idUser INT NOT NULL,

    FOREIGN KEY (idUser) REFERENCES person(id) ON DELETE CASCADE ON UPDATE CASCADE 
);

CREATE TABLE IF NOT EXISTS coment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT NOT NULL,
    idPost INT NOT NULL,
    content VARCHAR(200) NOT NULL,
    publicationDate DATE NOT NULL,

    FOREIGN KEY (idUser) REFERENCES person(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idPost) REFERENCES post(id) ON DELETE CASCADE ON UPDATE CASCADE
);
