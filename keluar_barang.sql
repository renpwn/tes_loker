/*
 Navicat Premium Dump SQL

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 80039 (8.0.39)
 Source Host           : localhost:3306
 Source Schema         : keluar_barang

 Target Server Type    : MySQL
 Target Server Version : 80039 (8.0.39)
 File Encoding         : 65001

 Date: 05/08/2026 10:08:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for items
-- ----------------------------
DROP TABLE IF EXISTS `items`;
CREATE TABLE `items`  (
  `id_barang` int NOT NULL,
  `nama_barang` varchar(240) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jumlah` float NULL DEFAULT NULL,
  `satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `harga_satuan` float NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_barang`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items
-- ----------------------------
INSERT INTO `items` VALUES (122, 'Barang B', 19, 'KG', 1000000, '1');
INSERT INTO `items` VALUES (123, 'Barang A', 55, 'EA', 200000, '1');

-- ----------------------------
-- Table structure for items_transaction
-- ----------------------------
DROP TABLE IF EXISTS `items_transaction`;
CREATE TABLE `items_transaction`  (
  `id` int NOT NULL,
  `jumlah` float NULL DEFAULT NULL,
  `satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `harga_satuan` float NULL DEFAULT NULL,
  `total` float NULL DEFAULT NULL,
  `id_barang` int NULL DEFAULT NULL,
  `id_transaksi` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `brg`(`id_barang` ASC) USING BTREE,
  INDEX `trx`(`id_transaksi` ASC) USING BTREE,
  CONSTRAINT `brg` FOREIGN KEY (`id_barang`) REFERENCES `items` (`id_barang`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `trx` FOREIGN KEY (`id_transaksi`) REFERENCES `transaction` (`id_transaksi`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_transaction
-- ----------------------------
INSERT INTO `items_transaction` VALUES (1, 1, 'KG', 1000000, 1000000, 122, 1);
INSERT INTO `items_transaction` VALUES (2, 5, 'EA', 200000, 1000000, 123, 1);

-- ----------------------------
-- Table structure for transaction
-- ----------------------------
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction`  (
  `id_transaksi` int NOT NULL,
  `nomor_transaksi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_transaksi` date NULL DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transaction
-- ----------------------------
INSERT INTO `transaction` VALUES (1, 'OUT/00001', '2026-08-05');

-- ----------------------------
-- View structure for dt_trans
-- ----------------------------
DROP VIEW IF EXISTS `dt_trans`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `dt_trans` AS select `t`.`nomor_transaksi` AS `No. Transaksi`,`t`.`tanggal_transaksi` AS `Tanggal`,`i`.`nama_barang` AS `Nama Barang`,`it`.`jumlah` AS `Jumlah`,`i`.`satuan` AS `Satuan`,`it`.`harga_satuan` AS `Harga Satuan`,`it`.`total` AS `Total` from ((`transaction` `t` join `items_transaction` `it` on((`t`.`id_transaksi` = `it`.`id_transaksi`))) join `items` `i` on((`i`.`id_barang` = `it`.`id_barang`)));

SET FOREIGN_KEY_CHECKS = 1;
