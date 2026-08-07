/*
 Navicat Premium Dump SQL

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 80039 (8.0.39)
 Source Host           : localhost:3306
 Source Schema         : inventory

 Target Server Type    : MySQL
 Target Server Version : 80039 (8.0.39)
 File Encoding         : 65001

 Date: 12/12/2025 11:24:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for master_barang
-- ----------------------------
DROP TABLE IF EXISTS `master_barang`;
CREATE TABLE `master_barang`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl` date NOT NULL,
  `kategori` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ada_stock` tinyint(1) NULL DEFAULT 0,
  `stock` int NULL DEFAULT 0,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_kategori`(`kategori` ASC) USING BTREE,
  INDEX `idx_satuan`(`satuan` ASC) USING BTREE,
  CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori`) REFERENCES `master_kategori` (`kode_kategori`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_barang_satuan` FOREIGN KEY (`satuan`) REFERENCES `master_satuan` (`kode_satuan`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_barang
-- ----------------------------
INSERT INTO `master_barang` VALUES (1, 'BRG001', 'Baut 12mm', '2025-01-01', 'KTG01', 'PCS', 1, 120, '');
INSERT INTO `master_barang` VALUES (2, 'BRG002', 'Obeng Plus', '2025-01-03', 'KTG01', 'PCS', 1, 40, 'Handle karet');
INSERT INTO `master_barang` VALUES (3, 'BRG003', 'Kabel NYA 2.5mm', '2025-01-05', 'KTG02', 'MTR', 1, 300, '');
INSERT INTO `master_barang` VALUES (4, 'BRG004', 'Kertas A4', '2025-01-10', 'KTG03', 'RIM', 1, 20, '');
INSERT INTO `master_barang` VALUES (5, 'BRG005', 'Paku 5cm', '2025-01-12', 'KTG04', 'KG', 1, 75, '');
INSERT INTO `master_barang` VALUES (6, 'BRG006', 'Lakban Hitam', '2025-01-15', 'KTG03', 'PCS', 0, 0, '');
INSERT INTO `master_barang` VALUES (7, 'BRG007', 'Solder 40W', '2025-01-18', 'KTG02', 'PCS', 1, 15, '');
INSERT INTO `master_barang` VALUES (8, 'BRG008', 'Cat Tembok Putih', '2025-01-20', 'KTG04', 'KLG', 1, 8, '');
INSERT INTO `master_barang` VALUES (9, 'BRG009', 'Kabel HDMI', '2025-01-22', 'KTG02', 'PCS', 0, 0, '');
INSERT INTO `master_barang` VALUES (10, 'BRG010', 'Spidol Permanent', '2025-01-25', 'KTG03', 'PCS', 1, 50, '');
INSERT INTO `master_barang` VALUES (11, 'BRG011', 'Isi Staples Kecil', '2025-02-01', 'KTG03', 'PK', 1, 32, '');
INSERT INTO `master_barang` VALUES (12, 'BRG012', 'Stop Kontak 4 Lubang', '2025-02-02', 'KTG02', 'PCS', 1, 14, '');
INSERT INTO `master_barang` VALUES (13, 'BRG013', 'Palu Besi', '2025-02-03', 'KTG01', 'PCS', 1, 22, '');
INSERT INTO `master_barang` VALUES (14, 'BRG014', 'Cat Kayu Brown', '2025-02-04', 'KTG04', 'KLG', 0, 0, '');
INSERT INTO `master_barang` VALUES (15, 'BRG015', 'Kertas Folio', '2025-02-05', 'KTG03', 'PK', 1, 17, '');
INSERT INTO `master_barang` VALUES (16, 'BRG016', 'Kabel LAN 10m', '2025-02-06', 'KTG02', 'PCS', 1, 9, '');
INSERT INTO `master_barang` VALUES (17, 'BRG017', 'Tang Potong', '2025-02-07', 'KTG01', 'PCS', 1, 11, '');
INSERT INTO `master_barang` VALUES (18, 'BRG018', 'Semen 40kg', '2025-02-08', 'KTG04', 'KG', 1, 55, '');
INSERT INTO `master_barang` VALUES (19, 'BRG019', 'Notes Tempel', '2025-02-09', 'KTG03', 'PK', 1, 40, '');
INSERT INTO `master_barang` VALUES (20, 'BRG020', 'Terminal Listrik', '2025-02-10', 'KTG02', 'PCS', 1, 13, '');
INSERT INTO `master_barang` VALUES (21, 'BRG021', 'Obeng Minus', '2025-02-11', 'KTG01', 'PCS', 1, 28, '');
INSERT INTO `master_barang` VALUES (22, 'BRG022', 'Triplek 3mm', '2025-02-12', 'KTG04', 'BTG', 1, 16, '');
INSERT INTO `master_barang` VALUES (23, 'BRG023', 'Binder Clip 19mm', '2025-02-13', 'KTG03', 'PK', 1, 26, '');
INSERT INTO `master_barang` VALUES (24, 'BRG024', 'Lampu LED 12W', '2025-02-14', 'KTG02', 'PCS', 1, 37, '');
INSERT INTO `master_barang` VALUES (25, 'BRG025', 'Gergaji Besi', '2025-02-15', 'KTG01', 'PCS', 0, 0, '');
INSERT INTO `master_barang` VALUES (26, 'BRG026', 'Pasir Halus', '2025-02-16', 'KTG04', 'KG', 1, 44, '');
INSERT INTO `master_barang` VALUES (27, 'BRG027', 'Penghapus Karet', '2025-02-17', 'KTG03', 'PCS', 1, 60, '');
INSERT INTO `master_barang` VALUES (28, 'BRG028', 'Adaptor 12V', '2025-02-18', 'KTG02', 'PCS', 1, 5, '');
INSERT INTO `master_barang` VALUES (29, 'BRG029', 'Kunci Inggris', '2025-02-19', 'KTG01', 'PCS', 1, 12, '');
INSERT INTO `master_barang` VALUES (30, 'BRG030', 'Cat Tembok Kuning', '2025-02-20', 'KTG04', 'KLG', 1, 6, '');
INSERT INTO `master_barang` VALUES (31, 'BRG031', 'HVS A3', '2025-02-21', 'KTG03', 'PK', 1, 18, '');
INSERT INTO `master_barang` VALUES (32, 'BRG032', 'Fuse Listrik 5A', '2025-02-22', 'KTG02', 'PCS', 1, 27, '');
INSERT INTO `master_barang` VALUES (33, 'BRG033', 'Obeng Set 6in1', '2025-02-23', 'KTG01', 'SET', 1, 8, '');
INSERT INTO `master_barang` VALUES (34, 'BRG034', 'Siku Besi', '2025-02-24', 'KTG04', 'PCS', 1, 21, '');
INSERT INTO `master_barang` VALUES (35, 'BRG035', 'Penggaris 30cm', '2025-02-25', 'KTG03', 'PCS', 1, 33, '');
INSERT INTO `master_barang` VALUES (36, 'BRG036', 'Steker Listrik', '2025-02-26', 'KTG02', 'PCS', 1, 19, '');
INSERT INTO `master_barang` VALUES (37, 'BRG037', 'Bor Tangan', '2025-02-27', 'KTG01', 'PCS', 1, 4, '');
INSERT INTO `master_barang` VALUES (38, 'BRG038', 'Batu Bata', '2025-02-28', 'KTG04', 'PCS', 1, 500, '');
INSERT INTO `master_barang` VALUES (39, 'BRG039', 'Pulpen Hitam', '2025-03-01', 'KTG03', 'PCS', 1, 120, '');
INSERT INTO `master_barang` VALUES (40, 'BRG040', 'Switch Listrik', '2025-03-02', 'KTG02', 'PCS', 1, 42, '');
INSERT INTO `master_barang` VALUES (41, 'BRG041', 'Gantungan Kunci Baut', '2025-03-03', 'KTG01', 'PCS', 0, 0, '');
INSERT INTO `master_barang` VALUES (42, 'BRG042', 'Besi Hollow', '2025-03-04', 'KTG04', 'BTG', 1, 32, '');
INSERT INTO `master_barang` VALUES (43, 'BRG043', 'Map Plastik', '2025-03-05', 'KTG03', 'PCS', 1, 87, '');
INSERT INTO `master_barang` VALUES (44, 'BRG044', 'Kabel Roll 10m', '2025-03-06', 'KTG02', 'PCS', 1, 10, '');
INSERT INTO `master_barang` VALUES (45, 'BRG045', 'Kunci Pas Set', '2025-03-07', 'KTG01', 'SET', 1, 6, '');
INSERT INTO `master_barang` VALUES (46, 'BRG046', 'Papan Gypsum', '2025-03-08', 'KTG04', 'BTG', 1, 14, '');
INSERT INTO `master_barang` VALUES (47, 'BRG047', 'Buku Tulis', '2025-03-09', 'KTG03', 'PCS', 1, 76, '');
INSERT INTO `master_barang` VALUES (48, 'BRG048', 'Modul Power Supply', '2025-03-10', 'KTG02', 'PCS', 1, 3, '');
INSERT INTO `master_barang` VALUES (49, 'BRG049', 'Gerinda Tangan', '2025-03-11', 'KTG01', 'PCS', 1, 2, '');
INSERT INTO `master_barang` VALUES (50, 'BRG050', 'Cat Kayu Merah', '2025-03-12', 'KTG04', 'KLG', 1, 9, '');
INSERT INTO `master_barang` VALUES (51, 'BRG051', 'Sticky Note Warna', '2025-03-13', 'KTG03', 'PK', 1, 48, '');
INSERT INTO `master_barang` VALUES (52, 'BRG052', 'Kabel Speaker', '2025-03-14', 'KTG02', 'MTR', 1, 60, '');
INSERT INTO `master_barang` VALUES (53, 'BRG053', 'Pisau Cutter', '2025-03-15', 'KTG01', 'PCS', 1, 35, '');
INSERT INTO `master_barang` VALUES (54, 'BRG054', 'Keramik Lantai', '2025-03-16', 'KTG04', 'KLG', 1, 22, '');
INSERT INTO `master_barang` VALUES (55, 'BRG055', 'Amplop Coklat', '2025-03-17', 'KTG03', 'PK', 1, 34, '');
INSERT INTO `master_barang` VALUES (56, 'BRG056', 'Relay 12V', '2025-03-18', 'KTG02', 'PCS', 1, 11, '');
INSERT INTO `master_barang` VALUES (57, 'BRG057', 'Kunci L Set', '2025-03-19', 'KTG01', 'SET', 1, 7, '');
INSERT INTO `master_barang` VALUES (58, 'BRG058', 'Besi Beton 8mm', '2025-03-20', 'KTG04', 'BTG', 1, 41, '');
INSERT INTO `master_barang` VALUES (59, 'BRG059', 'Stabilo Warna', '2025-03-21', 'KTG03', 'PCS', 1, 54, '');
INSERT INTO `master_barang` VALUES (60, 'BRG060', 'Kabel USB', '2025-03-22', 'KTG02', 'PCS', 1, 25, '');

-- ----------------------------
-- Table structure for master_kategori
-- ----------------------------
DROP TABLE IF EXISTS `master_kategori`;
CREATE TABLE `master_kategori`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_kategori`(`kode_kategori` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_kategori
-- ----------------------------
INSERT INTO `master_kategori` VALUES (1, 'KTG01', 'Perkakas', 'Alat kerja umum');
INSERT INTO `master_kategori` VALUES (2, 'KTG02', 'Elektronik', 'Komponen listrik');
INSERT INTO `master_kategori` VALUES (3, 'KTG03', 'ATK', 'Alat tulis kantor');
INSERT INTO `master_kategori` VALUES (4, 'KTG04', 'Bangunan', 'Material konstruksi');

-- ----------------------------
-- Table structure for master_satuan
-- ----------------------------
DROP TABLE IF EXISTS `master_satuan`;
CREATE TABLE `master_satuan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_satuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_satuan`(`kode_satuan` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_satuan
-- ----------------------------
INSERT INTO `master_satuan` VALUES (1, 'PCS', 'Pieces');
INSERT INTO `master_satuan` VALUES (2, 'MTR', 'Meter');
INSERT INTO `master_satuan` VALUES (3, 'RIM', 'Rim');
INSERT INTO `master_satuan` VALUES (4, 'KG', 'Kilogram');
INSERT INTO `master_satuan` VALUES (5, 'SET', 'Set');
INSERT INTO `master_satuan` VALUES (6, 'BTG', 'Batang');
INSERT INTO `master_satuan` VALUES (7, 'KLG', 'Kaleng');
INSERT INTO `master_satuan` VALUES (8, 'PK', 'Pack');

-- ----------------------------
-- View structure for vmaster_barang
-- ----------------------------
DROP VIEW IF EXISTS `vmaster_barang`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `vmaster_barang` AS select `mb`.`id` AS `id`,`mb`.`kode` AS `kode`,`mb`.`nama` AS `nama`,`mb`.`tgl` AS `tgl`,`mb`.`ada_stock` AS `ada_stock`,`mb`.`stock` AS `stock`,`mb`.`keterangan` AS `keterangan`,`mk`.`nama_kategori` AS `kategori`,`ms`.`nama_satuan` AS `satuan` from ((`master_barang` `mb` left join `master_kategori` `mk` on((`mb`.`kategori` = `mk`.`kode_kategori`))) left join `master_satuan` `ms` on((`mb`.`satuan` = `ms`.`kode_satuan`)));

SET FOREIGN_KEY_CHECKS = 1;
