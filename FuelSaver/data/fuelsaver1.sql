# Host: localhost  (Version: 5.5.17-log)
# Date: 2014-05-25 08:57:21
# Generator: MySQL-Front 5.3  (Build 4.122)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "countries"
#

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Country` varchar(255) NOT NULL DEFAULT '',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Country` (`Country`),
  KEY `IsActive` (`IsActive`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Data for table "countries"
#

INSERT INTO `countries` VALUES (1,'Malaysia',1),(2,'Indonesia',1),(4,'Singapore',1),(5,'Brunei',1),(6,'China',1),(7,'Thailand',1),(9,'Vietnam',1);

#
# Structure for table "inventorycategory"
#

DROP TABLE IF EXISTS `inventorycategory`;
CREATE TABLE `inventorycategory` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `InventoryCategory` varchar(255) NOT NULL DEFAULT '',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `InventoryCategory` (`InventoryCategory`),
  KEY `IsActive` (`IsActive`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Data for table "inventorycategory"
#

INSERT INTO `inventorycategory` VALUES (1,'Car Care',1),(3,'Beauty Care',1),(4,'House Care',1),(5,'Garden Care',1),(7,'Hair Care',1);

#
# Structure for table "inventoryitem"
#

DROP TABLE IF EXISTS `inventoryitem`;
CREATE TABLE `inventoryitem` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `InventoryItem` varchar(255) NOT NULL DEFAULT '',
  `InventoryDescription` text,
  `InventoryCategory` int(11) NOT NULL DEFAULT '0',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  `unitprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `InventoryItem` (`InventoryItem`),
  KEY `IsActive` (`IsActive`),
  KEY `InventoryItemsCategories` (`InventoryCategory`),
  CONSTRAINT `InventoryItemsCategories` FOREIGN KEY (`InventoryCategory`) REFERENCES `inventorycategory` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Data for table "inventoryitem"
#

INSERT INTO `inventoryitem` VALUES (1,'Bio-Add 30ml per bottle',NULL,1,1,48.00),(2,'Bio-Add 100ml per bottle',NULL,1,1,64.00),(3,'Bio-Add 300ml bottle',NULL,1,1,72.00);

#
# Structure for table "production"
#

DROP TABLE IF EXISTS `production`;
CREATE TABLE `production` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductionDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ProductionItem` int(11) NOT NULL DEFAULT '0',
  `ProductionQty` int(11) NOT NULL DEFAULT '0',
  `ProductionRemarks` text,
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  KEY `ProductionDate` (`ProductionDate`),
  KEY `ProductionItem` (`ProductionItem`),
  KEY `IsActive` (`IsActive`),
  CONSTRAINT `ProductionItemsInventories` FOREIGN KEY (`ProductionItem`) REFERENCES `inventoryitem` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "production"
#


#
# Structure for table "states"
#

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `State` varchar(255) NOT NULL DEFAULT '',
  `Country` int(11) NOT NULL DEFAULT '0',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `StateCountry` (`State`,`Country`),
  KEY `IsActive` (`IsActive`),
  KEY `StatesCountries` (`Country`),
  CONSTRAINT `StatesCountries` FOREIGN KEY (`Country`) REFERENCES `countries` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

#
# Data for table "states"
#

INSERT INTO `states` VALUES (2,'Kedah',1,1),(3,'Pulau Pinang',1,1),(5,'Selangor',1,1),(8,'Melaka',1,1),(9,'Perak',1,1),(10,'Kelantan',1,1),(14,'Pahang',1,1),(15,'Terengganu',1,1),(17,'Johor',1,1),(18,'Sabah',1,1),(19,'Sarawak',1,1);

#
# Structure for table "userroles"
#

DROP TABLE IF EXISTS `userroles`;
CREATE TABLE `userroles` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserRole` varchar(255) NOT NULL DEFAULT '',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UserRole` (`UserRole`),
  KEY `IsActive` (`IsActive`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

#
# Data for table "userroles"
#

INSERT INTO `userroles` VALUES (1,'Customer',1),(2,'Vendor',1),(3,'Logistic Staff',1),(4,'Accounts Staff',1),(5,'Administrator',1);

#
# Structure for table "users"
#

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` varchar(255) NOT NULL DEFAULT '',
  `UserPassword` varchar(255) DEFAULT NULL,
  `UserFullName` varchar(255) NOT NULL DEFAULT '',
  `UserRole` int(11) NOT NULL DEFAULT '0',
  `UserEmail` varchar(255) DEFAULT NULL,
  `UserTelephone` varchar(255) DEFAULT NULL,
  `UserAddress1` varchar(255) DEFAULT NULL,
  `UserAddress2` varchar(255) DEFAULT NULL,
  `UserAddress3` varchar(255) DEFAULT NULL,
  `UserTown` varchar(255) DEFAULT NULL,
  `UserState` int(11) DEFAULT NULL,
  `UserCountry` int(11) DEFAULT NULL,
  `IsActive` int(11) NOT NULL DEFAULT '0',
  `postcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `UserId` (`UserId`),
  KEY `UserFullName` (`UserFullName`),
  KEY `IsActive` (`IsActive`),
  KEY `UsersRoles` (`UserRole`),
  KEY `UserStates` (`UserState`),
  CONSTRAINT `UsersRoles` FOREIGN KEY (`UserRole`) REFERENCES `userroles` (`Id`),
  CONSTRAINT `UserStates` FOREIGN KEY (`UserState`) REFERENCES `states` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

#
# Data for table "users"
#

INSERT INTO `users` VALUES (1,'admin','fuelsaver','Administrator',5,'firedancerx.flameaters.com@gmail.com','014-6353 792',NULL,NULL,NULL,NULL,NULL,NULL,1,NULL),(2,'accounts','fuelsaver','Accounts Staff',4,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(3,'logistics','fuelsaver','Logistics Staff',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(4,'vendor','fuelsaver','Vendor',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(5,'customer','fuelsaver','Customer',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL),(6,'testme','test','test me',1,'test@test.com','0123363453','12 edsfrr',NULL,NULL,'assssss',2,NULL,1,12345),(7,'Test2','test 2','test 2',1,NULL,NULL,NULL,NULL,NULL,NULL,2,1,1,NULL);

#
# Structure for table "sales"
#

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `SalesDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SalesUser` int(11) DEFAULT NULL,
  `SalesItem` int(11) NOT NULL DEFAULT '0',
  `SalesQuantity` decimal(10,0) NOT NULL DEFAULT '0',
  `SalesPrice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `SalesValue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `SalesRemarks` text,
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  KEY `SalesUser` (`SalesUser`),
  KEY `IsActive` (`IsActive`),
  KEY `salesItemsInventories` (`SalesItem`),
  KEY `SalesDateTime` (`SalesDate`),
  CONSTRAINT `salesItemsInventories` FOREIGN KEY (`SalesItem`) REFERENCES `inventoryitem` (`Id`),
  CONSTRAINT `SalesUserUsers` FOREIGN KEY (`SalesUser`) REFERENCES `users` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Data for table "sales"
#

INSERT INTO `sales` VALUES (1,'2014-05-25 00:00:00',NULL,2,5,64.00,320.00,NULL,1),(2,'2014-05-25 00:00:00',NULL,2,2,64.00,128.00,NULL,1),(3,'2014-05-25 00:00:00',NULL,1,3,48.00,144.00,NULL,1),(4,'2014-05-25 00:00:00',NULL,1,20,48.00,960.00,'Test',1),(5,'2014-05-25 08:56:04',1,2,2,64.00,128.00,NULL,1);

#
# Structure for table "receipts"
#

DROP TABLE IF EXISTS `receipts`;
CREATE TABLE `receipts` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ReceiptNo` int(11) DEFAULT NULL,
  `ReceiptDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SalesOrder` int(11) NOT NULL DEFAULT '0',
  `ReceiptAmount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ReferenceId` varchar(255) DEFAULT NULL,
  `VerifiedAmount` decimal(10,2) DEFAULT '0.00',
  `VerifiedBy` int(11) DEFAULT '0',
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `ReceiptNo` (`ReceiptNo`),
  KEY `SalesOrder` (`SalesOrder`),
  KEY `ReferenceId` (`ReferenceId`),
  KEY `VerifiedBy` (`VerifiedBy`),
  KEY `ReceiptDateTime` (`ReceiptDate`),
  CONSTRAINT `ReceiptsSales` FOREIGN KEY (`SalesOrder`) REFERENCES `sales` (`Id`),
  CONSTRAINT `ReceiptsUsers` FOREIGN KEY (`VerifiedBy`) REFERENCES `users` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "receipts"
#


#
# Structure for table "inventorydeliveries"
#

DROP TABLE IF EXISTS `inventorydeliveries`;
CREATE TABLE `inventorydeliveries` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DeliveryOrderNo` int(11) DEFAULT NULL,
  `DeliveryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DeliveryBy` int(11) NOT NULL DEFAULT '0',
  `SalesId` int(11) NOT NULL DEFAULT '0',
  `DeliveryRemarks` text,
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `DeliveryOrderNo` (`DeliveryOrderNo`),
  KEY `DeliveryBy` (`DeliveryBy`),
  KEY `SalesId` (`SalesId`),
  KEY `IsActive` (`IsActive`),
  KEY `DeliveryDateTime` (`DeliveryDate`),
  CONSTRAINT `DeliveriesByUsers` FOREIGN KEY (`DeliveryBy`) REFERENCES `users` (`Id`),
  CONSTRAINT `DeliveriesSales` FOREIGN KEY (`SalesId`) REFERENCES `sales` (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "inventorydeliveries"
#


#
# Structure for table "contactus"
#

DROP TABLE IF EXISTS `contactus`;
CREATE TABLE `contactus` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `ContactDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ContactName` varchar(255) NOT NULL DEFAULT '',
  `ContactEmail` varchar(255) DEFAULT NULL,
  `ContactPhone` varchar(255) DEFAULT NULL,
  `ContactUser` int(11) DEFAULT NULL,
  `ContactSubject` varchar(255) DEFAULT NULL,
  `ContactContent` text,
  `ContactReplyDate` timestamp NULL DEFAULT NULL,
  `ContactReplyBy` int(11) DEFAULT NULL,
  `ContactReplyContent` text,
  `IsActive` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  KEY `ContactName` (`ContactName`),
  KEY `ContactUser` (`ContactUser`),
  KEY `ContactReplyBy` (`ContactReplyBy`),
  KEY `ContactIsActive` (`IsActive`),
  KEY `ContactDateTime` (`ContactDate`),
  KEY `ContactReplydateTime` (`ContactReplyDate`),
  CONSTRAINT `ContactReplyByUsers` FOREIGN KEY (`ContactReplyBy`) REFERENCES `users` (`Id`),
  CONSTRAINT `ContactUserUsers` FOREIGN KEY (`ContactUser`) REFERENCES `users` (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

#
# Data for table "contactus"
#

INSERT INTO `contactus` VALUES (3,'2014-12-05 00:00:00','test','test@test.com','012-223 2366',1,'test',NULL,NULL,NULL,NULL,1),(4,'2014-12-05 00:00:00','test',NULL,NULL,NULL,'test',NULL,NULL,NULL,NULL,1),(5,'2014-05-07 00:00:00','test','test@test.com',NULL,NULL,'test',NULL,NULL,NULL,NULL,1),(6,'2014-05-07 00:00:00','test','test@test.com',NULL,NULL,'test 2',NULL,NULL,NULL,NULL,1),(7,'2014-05-07 00:00:00','Test','test@test1.com',NULL,NULL,'test',NULL,NULL,NULL,NULL,1),(8,'2014-05-07 00:00:00','test','test@test.com',NULL,NULL,'test',NULL,NULL,NULL,NULL,1),(9,'2014-05-09 00:00:00','test','test@test.com','012-292 2938',NULL,'test',NULL,NULL,NULL,NULL,1);

DROP VIEW IF EXISTS `productlist`;
CREATE VIEW `productlist` AS 
  select `inventoryitem`.`Id` AS `Id`,`inventoryitem`.`InventoryItem` AS `InventoryItem`,`inventoryitem`.`InventoryDescription` AS `InventoryDescription`,`inventoryitem`.`InventoryCategory` AS `InventoryCategory`,`inventorycategory`.`InventoryCategory` AS `InventoryCategoryDescription`,`inventoryitem`.`IsActive` AS `IsActive`,`inventoryitem`.`unitprice` AS `unitprice` from (`inventoryitem` join `inventorycategory` on((`inventoryitem`.`InventoryCategory` = `inventorycategory`.`Id`)));

DROP VIEW IF EXISTS `usersqry`;
CREATE VIEW `usersqry` AS 
  select `users`.`UserId` AS `UserId`,`users`.`UserFullName` AS `UserFullName`,`userroles`.`UserRole` AS `UserRole`,`users`.`UserEmail` AS `UserEmail`,`users`.`UserTelephone` AS `UserTelephone`,`users`.`UserAddress1` AS `UserAddress1`,`users`.`UserAddress2` AS `UserAddress2`,`users`.`UserAddress3` AS `UserAddress3`,`users`.`UserTown` AS `UserTown`,`states`.`State` AS `State`,`states`.`Country` AS `Country`,(case when (`users`.`UserState` <> 0) then 'yes' else 'no' end) AS `IsActive` from ((`users` join `states` on((`users`.`UserState` = `states`.`Id`))) join `userroles` on((`users`.`UserRole` = `userroles`.`Id`)));

DROP VIEW IF EXISTS `usersview`;
CREATE VIEW `usersview` AS 
  select `users`.`UserId` AS `UserId`,`users`.`UserPassword` AS `UserPassword`,`users`.`UserFullName` AS `UserFullName`,`userroles`.`UserRole` AS `UserRole`,`users`.`UserEmail` AS `UserEmail`,`users`.`UserTelephone` AS `UserTelephone`,`users`.`UserAddress1` AS `UserAddress1`,`users`.`UserAddress2` AS `UserAddress2`,`users`.`UserAddress3` AS `UserAddress3`,`users`.`UserTown` AS `UserTown`,`users`.`IsActive` AS `IsActive` from (`users` join `userroles` on((`users`.`UserRole` = `userroles`.`Id`)));
