CREATE DATABASE IF NOT EXISTS anng15;
USE anng15;

GRANT ALL ON anng15.* TO whoever@localhost IDENTIFIED BY 'whatever';

SET NAMES utf8;

-- Drop tables if exists
DROP TABLE IF EXISTS `oophp_orderRows`;
DROP TABLE IF EXISTS `oophp_orders`;
DROP TABLE IF EXISTS `oophp_inBaskest`;
DROP TABLE IF EXISTS `oophp_inventory`;
DROP TABLE IF EXISTS `oophp_lowInventory`;
DROP TABLE IF EXISTS `oophp_shoppers`;
DROP TABLE IF EXISTS `oophp_products`;
DROP TABLE IF EXISTS `oophp_prodCategories`;


DROP VIEW IF EXISTS oophp_VAvailable;
DROP VIEW IF EXISTS oophp_VVirtualInventory;
DROP VIEW IF EXISTS oophp_VVirtualInventoryDescription;
DROP VIEW IF EXISTS oophp_VBasketDetails;
DROP VIEW IF EXISTS oophp_VOrderDetails;
DROP VIEW IF EXISTS oophp_VLowInventory;

DROP VIEW IF EXISTS oophp_VAdminInventory;

DELIMITER //

-- Sum up rows of same product for a shopperId/single basket
DROP FUNCTION IF EXISTS sumBasketProd;

CREATE FUNCTION sumBasketProd(shopperId INT, prodId INT)
RETURNS INT
BEGIN
    RETURN (SELECT SUM(amount) FROM oophp_inBaskest WHERE shopper_id = shopperId AND prod_id = prodId);
END
//

DELIMITER ;

--
-- Core tables
--

CREATE TABLE `oophp_prodCategories` (
    `id` INT AUTO_INCREMENT,
    `description` VARCHAR(256),

    PRIMARY KEY (`id`)
);

CREATE TABLE `oophp_products` (
    `id` INT AUTO_INCREMENT,
    `description` VARCHAR(256),
    `image_path` VARCHAR(256),
    `category` INT,
    `price` INT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`category`) REFERENCES `oophp_prodCategories` (`id`)
);

CREATE TABLE `oophp_inventory` (
    `id` INT AUTO_INCREMENT,
    `prod_id` INT,
    `items` INT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`prod_id`) REFERENCES `oophp_products` (`id`)
);

CREATE TABLE `oophp_lowInventory` (
    `id` INT AUTO_INCREMENT,
    `prod_id` INT,
    `time` DATETIME DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`prod_id`) REFERENCES `oophp_products` (`id`)
);

CREATE TABLE `oophp_shoppers` (
    `id` INT AUTO_INCREMENT,
    `name` VARCHAR(50),

    PRIMARY KEY (`id`)
);

CREATE TABLE `oophp_inBaskest` (
    `id` INT AUTO_INCREMENT,
    `prod_id` INT,
    `shopper_id` INT NOT NULL,
    `amount` INT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`prod_id`) REFERENCES `oophp_products` (`id`),
    FOREIGN KEY (`shopper_id`) REFERENCES `oophp_shoppers` (`id`)
);

CREATE TABLE `oophp_orders` (
	`id` INT AUTO_INCREMENT,
    `shopper_id` INT,
    `shopper_name` VARCHAR(50),
	`created` DATETIME DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY (`id`),
	FOREIGN KEY (`shopper_id`) REFERENCES `oophp_shoppers` (`id`)
);

CREATE TABLE `oophp_orderRows` (
    `id` INT AUTO_INCREMENT,
    `order` INT,
    `prod_id` INT,
	`amount` INT,

	PRIMARY KEY (`id`),
	FOREIGN KEY (`order`) REFERENCES `oophp_orders` (`id`),
	FOREIGN KEY (`prod_id`) REFERENCES `oophp_products` (`id`)
);

--
-- Some stuff to playaround with
--
INSERT INTO `oophp_prodCategories` (`description`) VALUES
("snittblommor"), ("krukväxter")
;

INSERT INTO `oophp_products` (`description`, `category`, `image_path`, `price`) VALUES
("tulpan",   1, "image/webshop/tulip.jpg", 5),
("ros",      1, "image/webshop/rose.jpg", 10),
("lilja",    1, "image/webshop/lilly.jpg", 10),
("pelargon", 2, "image/webshop/geranium.jpg", 25),
("rosmarin", 2, "image/webshop/rosemary.jpg", 50)
;

INSERT INTO `oophp_inventory` (`prod_id`, `items`) VALUES
(1, 100), (2, 50), (3, 30), (4, 25), (5, 10)
;

INSERT INTO `oophp_shoppers` (`name`) VALUES
("nasse"), ("puh"), ("ior"), ("kanin")
;

INSERT INTO `oophp_inBaskest` (`prod_id`, `shopper_id`, `amount`) VALUES
(1, 1, 30), (2, 1, 3), (1, 1, 30), (1, 2, 5)
;


--
-- Virtual Inventory tables
-- the virtual Inventory is the Inventory available excluding
-- things in baskets.
--
-- Since it is a virual Inventory the actual Inventory is still
-- unaffected.
--
CREATE VIEW oophp_VAvailable AS
    SELECT
        I.prod_id AS prod_id,
        I.items AS inv,
        sum(IB.amount) AS basketsums,
        I.items - sum(IB.amount) AS available
    FROM oophp_inBaskest AS IB
        RIGHT OUTER JOIN oophp_inventory AS I
            ON I.prod_id = IB.prod_id
        GROUP BY I.prod_id;


CREATE VIEW oophp_VVirtualInventory AS
    SELECT
        prod_id,
        CASE
            WHEN available IS NULL THEN inv
            ELSE available
        END AS available
    FROM oophp_VAvailable;


CREATE VIEW oophp_VVirtualInventoryDescription AS
    SELECT
        VI.prod_id AS id,
        P.description AS description,
        VI.available AS available
    FROM oophp_VVirtualInventory AS VI
        INNER JOIN oophp_products AS P
            ON P.id = VI.prod_id;

--
-- Admin Inventory View for me-page backend
--
CREATE VIEW oophp_VAdminInventory AS
    SELECT
        P.id AS id,
        P.description AS description,
        P.image_path AS image_path,
        P.price AS price,
        P.category AS category_id,
        PC.description AS category_description,
        I.items AS inventory,
        VI.available AS virtual_inventory
    FROM oophp_products AS P
        INNER JOIN oophp_prodCategories AS PC
            ON P.category = PC.id
        INNER JOIN oophp_inventory AS I
            ON P.id = I.prod_id
        INNER JOIN oophp_VVirtualInventory AS VI
            ON P.id = VI.prod_id;

--
-- Basket views
--

CREATE VIEW oophp_VBasketDetails AS
    SELECT
        S.id AS basket,
        S.name AS shopper,
        P.description AS product,
        IB.prod_id AS prod_id,
        sumBasketProd(S.id, IB.prod_id) AS amount
    FROM oophp_inBaskest AS IB
        INNER JOIN oophp_shoppers AS S
            ON IB.shopper_id = S.id
        INNER JOIN oophp_products AS P
            ON IB.prod_id = P.id
    GROUP BY amount
    ORDER BY S.id;

--
-- Order views
--

CREATE VIEW oophp_VOrderDetails AS
    SELECT
        O.id AS order_nr,
        O.shopper_id AS shopper_id,
        O.shopper_name AS shopper_name,
        R.id AS order_row,
        R.prod_id AS prod_id,
        P.description AS product,
        R.amount AS amount
    FROM `oophp_orders` AS O
    	INNER JOIN oophp_orderRows AS R
    		ON O.id = R.order
    	INNER JOIN oophp_products AS P
    		ON R.prod_id = P.id
    ORDER BY order_row;

--
-- Low inventory report
--

CREATE VIEW oophp_VLowInventory AS
    SELECT
        I.prod_id AS 'Product id',
        P.description AS 'Product',
        I.items AS 'In actual inventory',
        VI.available AS 'In virtual inventory',
        LI.time AS 'time'
    FROM `oophp_lowInventory`AS LI
        INNER JOIN `oophp_products` AS P
            ON LI.prod_id = P.id
        INNER JOIN `oophp_inventory` AS I
            ON LI.prod_id = I.prod_id
        INNER JOIN `oophp_VVirtualInventory` AS VI
            ON LI.prod_id = VI.prod_id
    ORDER BY I.prod_id;


--
-- Some handy procedures
--

DELIMITER //

-- Put stuff in basket
DROP PROCEDURE IF EXISTS putInBasket;

CREATE PROCEDURE putInBasket(shopperId INT, prodId INT, prodAmount INT)
BEGIN
    -- Check if there is enough of it
    DECLARE prodAvailable INT;

    SET prodAvailable = (SELECT available FROM oophp_VVirtualInventory WHERE prod_id = prodId);

    -- Insert to oophp_inBaskest
    IF prodAmount <= prodAvailable THEN
        INSERT INTO `oophp_inBaskest` (`prod_id`, `shopper_id`, `amount`) VALUES (prodId, shopperId, prodAmount);
    END IF;
END
//

-- Show a basket
DROP PROCEDURE IF EXISTS getBasket;

CREATE PROCEDURE getBasket(shopperId INT)
BEGIN
    SELECT
        prod_id,
        SUM(amount) AS amount
    FROM
        oophp_inBaskest
    WHERE shopper_id = shopperId
    GROUP BY prod_id;
END
//

-- Empty a basket
DROP PROCEDURE IF EXISTS emptyBasket;

CREATE PROCEDURE emptyBasket(shopperId INT)
BEGIN
    DELETE FROM oophp_inBaskest WHERE shopper_id=shopperId;
    -- SELECT "Basket emptied.";
END
//

-- Place an order.
--
-- Move stuff from basket to order.
-- Rollback if not enough items in Inventory.
--
-- Note that checks for available items is
-- made both here and earlier when things were put
-- in the basket.
DROP PROCEDURE IF EXISTS placeOrder;

CREATE PROCEDURE placeOrder(shopperId INT)
BEGIN

    DECLARE leastInventoryAmount INT;

    START TRANSACTION;

        INSERT INTO `oophp_orders` (`shopper_id`, `shopper_name`)
            SELECT
                id,
                name
            FROM oophp_shoppers
            WHERE id = shopperId;

        INSERT INTO `oophp_orderRows` (`order`, `prod_id`, `amount`)
        SELECT
        	O.id AS order_nr,
            VBD.prod_id AS prod_id,
            VBD.amount AS amount
        FROM `oophp_orders` AS O
        	INNER JOIN oophp_VBasketDetails AS VBD
        		ON O.shopper_id = VBD.basket;

        UPDATE oophp_inventory AS I
            INNER JOIN oophp_VBasketDetails AS VBD
                ON I.prod_id = VBD.prod_id
            SET I.items = I.items - VBD.amount
            WHERE VBD.basket = shopperId;

        -- Check that nothing in the inventory went below 0, if so rollback everything.
        SET leastInventoryAmount = (SELECT MIN(items) FROM oophp_inventory);
        IF leastInventoryAmount < 0 THEN
            ROLLBACK;
            -- SELECT "Not enough inventory to make order.";
        ELSE
            CALL emptyBasket(shopperId);
            COMMIT;
            -- SELECT "Order placed";
        END IF;
END
//

-- Show a single order
DROP PROCEDURE IF EXISTS showOrder;

CREATE PROCEDURE showOrder(orderNr INT)
BEGIN
    SELECT * FROM oophp_VOrderDetails WHERE order_nr = orderNr;
END
//

-- Delete an order
--
-- Stuff get back into inventory.
-- Stuff do not get back into basket.
DROP PROCEDURE IF EXISTS deleteOrder;

CREATE PROCEDURE deleteOrder(orderNr INT)
BEGIN
    UPDATE oophp_inventory AS I
        INNER JOIN oophp_VOrderDetails AS VOD
            ON I.prod_id = VOD.prod_id
        SET I.items = I.items + VOD.amount
        WHERE VOD.order_nr = orderNr;

    DELETE FROM `oophp_orderRows` WHERE `order`=orderNr;
    DELETE FROM `oophp_orders` WHERE `id`=orderNr;
END
//
DELIMITER ;

--
-- Trigger
--

DELIMITER //

DROP TRIGGER IF EXISTS logLowInventory;

CREATE TRIGGER logLowInventory
AFTER UPDATE
ON oophp_inventory
FOR EACH ROW
BEGIN
    IF NEW.items < 5 THEN
        INSERT INTO oophp_lowInventory (`prod_id`) VALUES (NEW.prod_id);
    END IF;
END
//

DELIMITER ;
--
-- end handy procedures
--

--
-- Output some stuff
--
select "";
select * from `oophp_VAdminInventory`;
