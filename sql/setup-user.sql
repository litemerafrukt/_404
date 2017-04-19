CREATE DATABASE IF NOT EXISTS anng15;
USE anng15;

GRANT ALL ON anng15.* TO whoever@localhost IDENTIFIED BY 'whatever';

SET NAMES utf8;

DROP TABLE IF EXISTS oophp_users;
CREATE TABLE oophp_users
(
    id        INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username  VARCHAR(100) NOT NULL,
    email     VARCHAR(100) NOT NULL,
    password  VARCHAR(255) NOT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO oophp_users
  (username, email, password)
VALUES
  ('admin', 'noone@nonexistant.io', '$2y$10$ktbmmlq56CK5ZOdBWKWO2OBmOuumoHQwJpBfJzL52.J9SCj/jRdHW'),
  ('litemerafrukt', 'litemerafrukt@gmail.com', '$2y$10$0J5Zto0Cxix1z8o1DH0SuOGTf7sPue2rCmqBPd52QkpVo/Bkgq.B.')
;

SELECT * FROM oophp_users;
