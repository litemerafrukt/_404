CREATE DATABASE IF NOT EXISTS anng15;
USE anng15;

GRANT ALL ON anng15.* TO whoever@localhost IDENTIFIED BY 'whatever';

SET NAMES utf8;

DROP TABLE IF EXISTS oophp_users;
CREATE TABLE oophp_users
(
    id          INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username    VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    email       VARCHAR(100) NOT NULL,
    userlevel   INT NOT NULL
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO oophp_users
  (username, password, email, userlevel)
VALUES
  ('admin', '$2y$10$RCFw4V8duXyBzT2Ti5X7ae.YofcFAMyP40ZNrU3hbEhAOJE0tKhEW', 'noone@nonexistant.io', 1),
  ('doe', '$2y$10$OcmC0aLKQLCcszlnF4pd.ebFzH87oxkR2Gx7difCeT1g6UogIiUqO', 'jane@doe.io', 1),
  ('litemerafrukt', '$2y$10$0J5Zto0Cxix1z8o1DH0SuOGTf7sPue2rCmqBPd52QkpVo/Bkgq.B.', 'litemerafrukt@gmail.com', 1)
;

SELECT * FROM oophp_users;
