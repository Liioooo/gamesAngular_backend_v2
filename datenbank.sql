drop DATABASE if exists `lioGames`;
CREATE DATABASE `lioGames`;
USE `lioGames`;

create TABLE users(
    pk_userID int PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) unique,
    passwd char(40),
    picturePath varchar(40),
    description varchar(500) default 'Benutzerbeschreibung'
);

create table highscores(
    pk_fk_userID int,
    pk_gameID int,
    score int,
    PRIMARY KEY(pk_fk_userID, pk_gameID)
);

ALTER TABLE highscores
    ADD CONSTRAINT cfk_highscores
    FOREIGN KEY(pk_fk_userID)
    REFERENCES users(pk_userID);
