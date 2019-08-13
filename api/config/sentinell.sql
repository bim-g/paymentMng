
CREATE DATABASE IF NOT EXISTS sentinel;
USE sentinel;
-- create table 'typeUser'

CREATE TABLE IF NOT EXISTS employee (
    idemployee int(12) PRIMARY KEY AUTO_INCREMENT,
    Fname VARCHAR(20),
    Lname VARCHAR(20),
    birthday VARCHAR(10),
    sexe VARCHAR(8),
    email VARCHAR(30),
    phone VARCHAR(13),
    maretalStatus VARCHAR(6),
    datecreate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into employee 
INSERT INTO `employee` (`idemployee`, `Fname`, `Lname`, `birthday`, `sexe`, `email`, `phone`, `maretalStatus`, `datecreate`) VALUES
(1, 'ibrahim', 'mussa', '17/04/1990', 'male', 'ibmussa@mail.com', '+243123456789', 'maried', '2019-02-10 14:44:05'),
(2, 'aziza', 'bushiri', '02/05/1998', 'female', 'aziza@mail.com', '+243123456789', 'maried', '2019-02-10 14:44:05'),
(3, 'provi', 'muhoza', '23/4/1995', 'female', 'provi@mail.com', '+255123456789', 'single', '2019-02-10 14:44:56');

-- create table typeAccess 
CREATE TABLE IF NOT EXISTS typeAccess (
    idtypeAccess int(12) PRIMARY KEY AUTO_INCREMENT,
    typeName VARCHAR(250),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into typeAcess
INSERT INTO `typeaccess` (`idtypeAccess`, `typeName`, `datecreate`) VALUES
(1, 'root', '2019-02-10 14:47:08'),
(2, 'manager', '2019-02-10 14:47:08'),
(3, 'user', '2019-02-10 14:47:28');
-- create table users 
CREATE TABLE IF NOT EXISTS users (
    idusers int(12) PRIMARY KEY AUTO_INCREMENT,
    idemployee int(12),
    username VARCHAR(30),
    passwd VARCHAR(250),
    idtypeAccess int(12),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idemployee) REFERENCES employee(idemployee),
    FOREIGN KEY (idtypeAccess) REFERENCES typeAccess(idtypeAccess)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into users
INSERT INTO `users` (`idusers`, `idemployee`,`username`, `passwd`, `idtypeAccess`, `datecreate`) VALUES
(1, 1,201720644 ,'f1ef83aaae953da9cd7a9c69d5a0d0d8', 1, '2019-02-10 14:48:53'),
(2, 2,201720655 ,'e6e86032090eb09f5717e33f343cd678', 2, '2019-02-10 14:48:53'),
(3, 3,201720904 ,'2e3b9690cc1c58dc03eb5ab030b31fae', 3, '2019-02-10 14:49:18');

-- create table departement 
CREATE TABLE IF NOT EXISTS departement (
    iddepart int(12) PRIMARY KEY AUTO_INCREMENT,
    departName VARCHAR(20),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into departement
INSERT INTO `departement` (`iddepart`, `departName`, `datecreate`) VALUES
(1, 'finance', '2019-02-10 14:50:17'),
(2, 'registration', '2019-02-10 14:50:17');

-- create table services 
CREATE TABLE IF NOT EXISTS services (
    idservice int(12) PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(20),
    iddepart int(12),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (iddepart) REFERENCES departement(iddepart)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into services
INSERT INTO `services` (`idservice`, `serviceName`, `iddepart`, `datecreate`) VALUES
(1, 'comptability', 1, '2019-02-10 14:51:11'),
(2, 'management', 1, '2019-02-10 14:51:11'),
(3, 'registration', 2, '2019-02-10 14:51:24');

-- create table grade 
CREATE TABLE IF NOT EXISTS employeeprime (
    idprime int(12) PRIMARY KEY AUTO_INCREMENT,
    typeprime VARCHAR(20),    
    mountprime VARCHAR(10),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into employee prime
INSERT INTO `employeeprime` (`idprime`, `typeprime`, `mountprime`, `datecreate`) VALUES
(1, 'maried', '120000', '2019-02-10 14:54:22'),
(2, 'single', '40000', '2019-02-10 14:54:22');

-- create table grade 
CREATE TABLE IF NOT EXISTS grade (
    idgrade int(12) PRIMARY KEY AUTO_INCREMENT,
    gradeName VARCHAR(20),    
    netsalary VARCHAR(10),
    childprime VARCHAR(10), 
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into grade
INSERT INTO `grade` (`idgrade`, `gradeName`, `netsalary`, `childprime`, `datecreate`) VALUES 
(1, 'director', '1200000', '80000', CURRENT_TIMESTAMP), (2, 'manager', '800000', '40000', CURRENT_TIMESTAMP);

-- create table primeOngrade 
CREATE TABLE IF NOT EXISTS primeongrade (
    id int(12) PRIMARY KEY AUTO_INCREMENT,
    idgrade int(12), 
    idprime int(12), 
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idprime) REFERENCES employeeprime(idprime),
    FOREIGN KEY (idgrade) REFERENCES grade(idgrade)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into primeongrade
INSERT INTO `primeongrade` (`id`, `idgrade`, `idprime`, `datecreate`) VALUES 
(1, '1', '1', CURRENT_TIMESTAMP), 
(2, '1', '2', CURRENT_TIMESTAMP);

-- create table workon 
CREATE TABLE IF NOT EXISTS workOnAs (
    id int(12) PRIMARY KEY AUTO_INCREMENT,
    idemployee int(12),
    idservice int(12),
    idgrade int(12),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idemployee) REFERENCES employee(idemployee),
    FOREIGN KEY (idservice) REFERENCES services(idservice),
    FOREIGN KEY (idgrade) REFERENCES grade(idgrade)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into table workOnAs
INSERT INTO `workonas` (`id`, `idemployee`, `idservice`, `idgrade`, `datecreate`) VALUES 
(1, '1', '1', '2', CURRENT_TIMESTAMP), 
(2, '2', '3', '1', CURRENT_TIMESTAMP);

-- create table workon 
CREATE TABLE IF NOT EXISTS empDependancy (
    iddep int(12) PRIMARY KEY AUTO_INCREMENT,
    idemployee int(20),
    dependName VARCHAR(20),
    dependStatus VARCHAR(10),
    birthday VARCHAR (10),
    sexe VARCHAR (10),
    datecreate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (idemployee) REFERENCES employee(idemployee)
)ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- insert into table empDepency
INSERT INTO `empdependancy` (`iddep`, `idemployee`, `dependName`, `dependStatus`, `birthday`, `sexe`, `datecreate`) VALUES 
(1, '1', 'maya njulu', 'wife', '23/4/2000', 'female', CURRENT_TIMESTAMP), 
(2, '1', 'rushan', 'child', '02/05/2025', 'male', CURRENT_TIMESTAMP);

-- 201620904
-- 201720644
-- 201720655