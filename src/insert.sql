DROP DATABASE IF EXISTS my_todo;

CREATE DATABASE my_todo;

USE my_todo;

DROP TABLE IF EXISTS session;
DROP TABLE IF EXISTS todo;
DROP TABLE IF EXISTS user;

CREATE TABLE session
(
    id       integer auto_increment primary key not null,
    username varchar(100)                       NOT NULL UNIQUE,
    token    varchar(250)                       NOT NULL UNIQUE
);

CREATE TABLE todo
(
    id         integer auto_increment primary key   not null,
    title      text                                 NOT NULL,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP() NULL,
    user_id    integer                              NULL,
    struc      integer                              NOT NULL,
    finished   boolean
);

CREATE TABLE user
(
    id       INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username varchar(50)                        NOT NULL UNIQUE,
    password varchar(100)                       NOT NULL
);

INSERT INTO todo(title, user_id, struc, finished)
VALUES ('Todo afmaken', 1, 1, false),
       ('Auto schoonmaken', 1, 2, false),
       ('School', 1, 3, true)