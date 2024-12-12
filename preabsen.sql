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

 Date: 12/12/2024 11:21:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for attendances
-- ----------------------------
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `check_in` timestamp NULL DEFAULT NULL,
  `check_out` timestamp NULL DEFAULT NULL,
  `check_in_latitude` double NULL DEFAULT NULL,
  `check_in_longitude` double NULL DEFAULT NULL,
  `check_out_latitude` double NULL DEFAULT NULL,
  `check_out_longitude` double NULL DEFAULT NULL,
  `check_in_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `check_out_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `check_in_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `check_out_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('present','late','permission','sick','absent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `attendances_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendances
-- ----------------------------
INSERT INTO `attendances` VALUES (1, 2, '2024-12-12 07:53:41', '2024-12-12 17:03:34', -6.16564363, 106.82374835, -6.16564363, 106.82374835, 'attendance-photos/01JEWG6TGCA9VNM571VYYN3CM4.jpg', 'attendance-photos/01JEWGAQYJ84W1A13A1XHBWZCT.jpeg', NULL, NULL, 'present', NULL, '2024-12-12 03:57:09', '2024-12-12 03:59:17', NULL);
INSERT INTO `attendances` VALUES (2, 2, '2024-12-12 07:14:46', NULL, -6.16564363, 106.82374835, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 04:15:18', '2024-12-12 04:15:18', NULL);
INSERT INTO `attendances` VALUES (3, 3, '2024-12-12 07:20:35', NULL, -6.16564363, 106.82374835, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 04:21:02', '2024-12-12 04:21:02', NULL);

-- ----------------------------
-- Table structure for departements
-- ----------------------------
DROP TABLE IF EXISTS `departements`;
CREATE TABLE `departements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departements
-- ----------------------------
INSERT INTO `departements` VALUES (1, 'Human Resources', 'Departemen yang mengelola sumber daya manusia', '2024-12-12 02:31:49', '2024-12-12 02:31:49', NULL);
INSERT INTO `departements` VALUES (2, 'Information Technology', 'Departemen yang mengelola teknologi informasi', '2024-12-12 02:31:49', '2024-12-12 02:31:49', NULL);
INSERT INTO `departements` VALUES (3, 'Finance', 'Departemen yang mengelola keuangan', '2024-12-12 02:31:49', '2024-12-12 02:31:49', NULL);
INSERT INTO `departements` VALUES (4, 'Marketing', 'Departemen yang mengelola pemasaran', '2024-12-12 02:31:49', '2024-12-12 02:31:49', NULL);
INSERT INTO `departements` VALUES (5, 'Operations', 'Departemen yang mengelola operasional', '2024-12-12 02:31:49', '2024-12-12 02:31:49', NULL);

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for leave_requests
-- ----------------------------
DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('annual','sick','important','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `leave_requests_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `leave_requests_approved_by_foreign`(`approved_by` ASC) USING BTREE,
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `leave_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_requests
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (2, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (4, '2024_12_02_083253_create_departements_table', 1);
INSERT INTO `migrations` VALUES (5, '2024_12_02_083254_create_office_locations_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_12_02_083255_create_work_schedules_table', 1);
INSERT INTO `migrations` VALUES (7, '2024_12_02_083256_create_users_table', 1);
INSERT INTO `migrations` VALUES (8, '2024_12_03_031114_create_attendances_table', 1);
INSERT INTO `migrations` VALUES (9, '2024_12_03_031916_create_leave_requests_table', 1);
INSERT INTO `migrations` VALUES (10, '2024_12_04_091523_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (12, '2024_12_10_021014_create_permission_tables', 2);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 2);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 3);

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `notifications_notifiable_type_notifiable_id_index`(`notifiable_type` ASC, `notifiable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------

-- ----------------------------
-- Table structure for office_locations
-- ----------------------------
DROP TABLE IF EXISTS `office_locations`;
CREATE TABLE `office_locations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10, 8) NOT NULL,
  `longitude` decimal(11, 8) NOT NULL,
  `radius` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of office_locations
-- ----------------------------
INSERT INTO `office_locations` VALUES (1, 'Kantor Pusat', 'Jl. MH Thamrin No.1, Jakarta Pusat', -6.16562230, 106.82371616, 15, '2024-12-12 02:34:47', '2024-12-12 04:06:34', NULL);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'view_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (2, 'view_any_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (3, 'create_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (4, 'update_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (5, 'restore_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (6, 'restore_any_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (7, 'replicate_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (8, 'reorder_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (9, 'delete_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (10, 'delete_any_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (11, 'force_delete_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (12, 'force_delete_any_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (13, 'approve_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (14, 'reject_attendance', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (15, 'view_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (16, 'view_any_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (17, 'create_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (18, 'update_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (19, 'restore_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (20, 'restore_any_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (21, 'replicate_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (22, 'reorder_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (23, 'delete_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (24, 'delete_any_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (25, 'force_delete_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (26, 'force_delete_any_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (27, 'approve_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (28, 'reject_departement', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (29, 'view_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (30, 'view_any_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (31, 'create_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (32, 'update_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (33, 'restore_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (34, 'restore_any_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (35, 'replicate_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (36, 'reorder_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (37, 'delete_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (38, 'delete_any_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (39, 'force_delete_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (40, 'force_delete_any_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (41, 'approve_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (42, 'reject_leave::request', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (43, 'view_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (44, 'view_any_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (45, 'create_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (46, 'update_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (47, 'restore_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (48, 'restore_any_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (49, 'replicate_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (50, 'reorder_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (51, 'delete_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (52, 'delete_any_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (53, 'force_delete_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (54, 'force_delete_any_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (55, 'approve_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (56, 'reject_office::location', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (57, 'view_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (58, 'view_any_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (59, 'create_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (60, 'update_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (61, 'delete_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (62, 'delete_any_role', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (63, 'view_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (64, 'view_any_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (65, 'create_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (66, 'update_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (67, 'restore_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (68, 'restore_any_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (69, 'replicate_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (70, 'reorder_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (71, 'delete_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (72, 'delete_any_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (73, 'force_delete_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (74, 'force_delete_any_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (75, 'approve_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (76, 'reject_user', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (77, 'view_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (78, 'view_any_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (79, 'create_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (80, 'update_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (81, 'restore_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (82, 'restore_any_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (83, 'replicate_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (84, 'reorder_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (85, 'delete_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (86, 'delete_any_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (87, 'force_delete_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (88, 'force_delete_any_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (89, 'approve_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `permissions` VALUES (90, 'reject_work::schedule', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (1, 2);
INSERT INTO `role_has_permissions` VALUES (1, 3);
INSERT INTO `role_has_permissions` VALUES (2, 1);
INSERT INTO `role_has_permissions` VALUES (2, 2);
INSERT INTO `role_has_permissions` VALUES (2, 3);
INSERT INTO `role_has_permissions` VALUES (3, 1);
INSERT INTO `role_has_permissions` VALUES (3, 2);
INSERT INTO `role_has_permissions` VALUES (3, 3);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (4, 2);
INSERT INTO `role_has_permissions` VALUES (4, 3);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (7, 1);
INSERT INTO `role_has_permissions` VALUES (8, 1);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (9, 2);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (15, 2);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (16, 2);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (17, 2);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (18, 2);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (23, 2);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (26, 1);
INSERT INTO `role_has_permissions` VALUES (27, 1);
INSERT INTO `role_has_permissions` VALUES (28, 1);
INSERT INTO `role_has_permissions` VALUES (29, 1);
INSERT INTO `role_has_permissions` VALUES (29, 2);
INSERT INTO `role_has_permissions` VALUES (29, 3);
INSERT INTO `role_has_permissions` VALUES (29, 4);
INSERT INTO `role_has_permissions` VALUES (30, 1);
INSERT INTO `role_has_permissions` VALUES (30, 2);
INSERT INTO `role_has_permissions` VALUES (30, 3);
INSERT INTO `role_has_permissions` VALUES (30, 4);
INSERT INTO `role_has_permissions` VALUES (31, 1);
INSERT INTO `role_has_permissions` VALUES (31, 2);
INSERT INTO `role_has_permissions` VALUES (31, 3);
INSERT INTO `role_has_permissions` VALUES (32, 1);
INSERT INTO `role_has_permissions` VALUES (32, 2);
INSERT INTO `role_has_permissions` VALUES (32, 3);
INSERT INTO `role_has_permissions` VALUES (33, 1);
INSERT INTO `role_has_permissions` VALUES (34, 1);
INSERT INTO `role_has_permissions` VALUES (35, 1);
INSERT INTO `role_has_permissions` VALUES (36, 1);
INSERT INTO `role_has_permissions` VALUES (37, 1);
INSERT INTO `role_has_permissions` VALUES (37, 2);
INSERT INTO `role_has_permissions` VALUES (38, 1);
INSERT INTO `role_has_permissions` VALUES (38, 2);
INSERT INTO `role_has_permissions` VALUES (39, 1);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (41, 2);
INSERT INTO `role_has_permissions` VALUES (41, 4);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (42, 2);
INSERT INTO `role_has_permissions` VALUES (42, 4);
INSERT INTO `role_has_permissions` VALUES (43, 1);
INSERT INTO `role_has_permissions` VALUES (43, 2);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (44, 2);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (45, 2);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (46, 2);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (51, 2);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (54, 1);
INSERT INTO `role_has_permissions` VALUES (55, 1);
INSERT INTO `role_has_permissions` VALUES (56, 1);
INSERT INTO `role_has_permissions` VALUES (57, 1);
INSERT INTO `role_has_permissions` VALUES (57, 2);
INSERT INTO `role_has_permissions` VALUES (58, 1);
INSERT INTO `role_has_permissions` VALUES (58, 2);
INSERT INTO `role_has_permissions` VALUES (59, 1);
INSERT INTO `role_has_permissions` VALUES (59, 2);
INSERT INTO `role_has_permissions` VALUES (60, 1);
INSERT INTO `role_has_permissions` VALUES (61, 1);
INSERT INTO `role_has_permissions` VALUES (62, 1);
INSERT INTO `role_has_permissions` VALUES (63, 1);
INSERT INTO `role_has_permissions` VALUES (63, 2);
INSERT INTO `role_has_permissions` VALUES (64, 1);
INSERT INTO `role_has_permissions` VALUES (64, 2);
INSERT INTO `role_has_permissions` VALUES (65, 1);
INSERT INTO `role_has_permissions` VALUES (65, 2);
INSERT INTO `role_has_permissions` VALUES (66, 1);
INSERT INTO `role_has_permissions` VALUES (66, 2);
INSERT INTO `role_has_permissions` VALUES (67, 1);
INSERT INTO `role_has_permissions` VALUES (68, 1);
INSERT INTO `role_has_permissions` VALUES (69, 1);
INSERT INTO `role_has_permissions` VALUES (70, 1);
INSERT INTO `role_has_permissions` VALUES (71, 1);
INSERT INTO `role_has_permissions` VALUES (71, 2);
INSERT INTO `role_has_permissions` VALUES (72, 1);
INSERT INTO `role_has_permissions` VALUES (72, 2);
INSERT INTO `role_has_permissions` VALUES (73, 1);
INSERT INTO `role_has_permissions` VALUES (74, 1);
INSERT INTO `role_has_permissions` VALUES (75, 1);
INSERT INTO `role_has_permissions` VALUES (76, 1);
INSERT INTO `role_has_permissions` VALUES (77, 1);
INSERT INTO `role_has_permissions` VALUES (77, 2);
INSERT INTO `role_has_permissions` VALUES (78, 1);
INSERT INTO `role_has_permissions` VALUES (78, 2);
INSERT INTO `role_has_permissions` VALUES (79, 1);
INSERT INTO `role_has_permissions` VALUES (79, 2);
INSERT INTO `role_has_permissions` VALUES (80, 1);
INSERT INTO `role_has_permissions` VALUES (80, 2);
INSERT INTO `role_has_permissions` VALUES (81, 1);
INSERT INTO `role_has_permissions` VALUES (82, 1);
INSERT INTO `role_has_permissions` VALUES (83, 1);
INSERT INTO `role_has_permissions` VALUES (84, 1);
INSERT INTO `role_has_permissions` VALUES (85, 1);
INSERT INTO `role_has_permissions` VALUES (85, 2);
INSERT INTO `role_has_permissions` VALUES (86, 1);
INSERT INTO `role_has_permissions` VALUES (87, 1);
INSERT INTO `role_has_permissions` VALUES (88, 1);
INSERT INTO `role_has_permissions` VALUES (89, 1);
INSERT INTO `role_has_permissions` VALUES (90, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'super_admin', 'web', '2024-12-12 02:35:49', '2024-12-12 02:35:49');
INSERT INTO `roles` VALUES (2, 'Admin', 'web', '2024-12-12 02:44:46', '2024-12-12 02:44:46');
INSERT INTO `roles` VALUES (3, 'Employee', 'web', '2024-12-12 02:46:40', '2024-12-12 02:46:40');
INSERT INTO `roles` VALUES (4, 'Approval', 'web', '2024-12-12 02:47:09', '2024-12-12 02:47:09');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement_id` bigint UNSIGNED NULL DEFAULT NULL,
  `office_location_id` bigint UNSIGNED NULL DEFAULT NULL,
  `work_schedule_id` bigint UNSIGNED NULL DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `employee_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  UNIQUE INDEX `users_employee_id_unique`(`employee_id` ASC) USING BTREE,
  INDEX `users_departement_id_foreign`(`departement_id` ASC) USING BTREE,
  INDEX `users_office_location_id_foreign`(`office_location_id` ASC) USING BTREE,
  INDEX `users_work_schedule_id_foreign`(`work_schedule_id` ASC) USING BTREE,
  CONSTRAINT `users_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `users_office_location_id_foreign` FOREIGN KEY (`office_location_id`) REFERENCES `office_locations` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `users_work_schedule_id_foreign` FOREIGN KEY (`work_schedule_id`) REFERENCES `work_schedules` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Super Admin', 'superadmin@gmail.com', NULL, '$2y$12$Pb.6x94ZkqJnemhi4wV04eDPN5DiJad23ipGz7ubrmXF9rIPyqNDS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-12 02:36:23', '2024-12-12 02:36:23', NULL);
INSERT INTO `users` VALUES (2, 'Pegawai 1', 'pegawai1@gmail.com', NULL, '$2y$12$knlEJc.y/8MkBjrvzdKM6uSkiweMXN/nclcpY917VTKcnZtu3r2v.', 2, 1, 1, '089608780861', 'Bogor\nJawa barat', 'Programmer', '144856 344 5563', NULL, NULL, '2024-12-12 03:32:22', '2024-12-12 03:32:22', NULL);
INSERT INTO `users` VALUES (3, 'Pegawai 2', 'pegawai2@gmail.com', NULL, '$2y$12$UgO9KaEo47j2y4w9Hz2LWelmdFBbfVt0zFiM.M6wXwUTgJrBAfAlS', 1, 1, 1, '089608789861', 'Bogor\nJawa barat', 'Finance', '144856 344 5723', NULL, NULL, '2024-12-12 04:14:13', '2024-12-12 04:14:13', NULL);

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
