/*
 Navicat Premium Data Transfer

 Source Server         : Database Mysql XAMPP
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : preabsen

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 13/12/2024 09:08:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for work_schedules
-- ----------------------------
DROP TABLE IF EXISTS `work_schedules`;
CREATE TABLE `work_schedules`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `check_in_start` time NOT NULL,
  `check_in_end` time NOT NULL,
  `check_out_start` time NOT NULL,
  `check_out_end` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of work_schedules
-- ----------------------------
INSERT INTO `work_schedules` VALUES (1, 'Jadwal Normal', '07:00:00', '08:30:00', '16:00:00', '17:30:00', '2024-12-12 02:34:47', '2024-12-12 02:34:47', NULL);
INSERT INTO `work_schedules` VALUES (2, 'Jadwal Shift Pagi', '06:00:00', '07:30:00', '14:00:00', '15:30:00', '2024-12-12 02:34:47', '2024-12-12 02:34:47', NULL);
INSERT INTO `work_schedules` VALUES (3, 'Jadwal Shift Siang', '14:00:00', '15:30:00', '22:00:00', '23:30:00', '2024-12-12 02:34:47', '2024-12-12 02:34:47', NULL);

SET FOREIGN_KEY_CHECKS = 1;
