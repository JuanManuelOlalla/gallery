create database gallery default character set utf8 collate utf8_unicode_ci;
grant all on gallery.* to admin@localhost identified by 'admin';
flush privileges;

use gallery;

CREATE TABLE IF NOT EXISTS `user` (
  `email` varchar(80) unique primary key not null,
  `clave` varchar(40) not null,
  `alias` varchar(40) unique not null,
  `fechalta` date not null,
  `activo` tinyint(1)  DEFAULT 0,
  `administrador` tinyint(1) DEFAULT 0,
  `personal` tinyint(1) DEFAULT 0,
  `avatar` varchar(40) DEFAULT 'default-avatar.jpg',
  `descripcion` varchar(200),
  `privado` tinyint(1) DEFAULT 0,
  `plantilla` tinyint(1) DEFAULT 1
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `imagen` (
    `nombre` varchar(80) NOT NULL primary key,
    `ruta` varchar(40) NOT NULL,
    `autor` varchar(30) NOT NULL,
     constraint foreign key(autor) references user(email) on update cascade on delete cascade
) ENGINE=InnoDB;