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

 Date: 11/12/2024 16:13:14
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
  `check_in_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `check_out_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `check_in_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `check_out_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('present','late','permission','sick','absent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `check_in_latitude` double NULL DEFAULT NULL,
  `check_in_longitude` double NULL DEFAULT NULL,
  `check_out_latitude` double NULL DEFAULT NULL,
  `check_out_longitude` double NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `attendances_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attendances
-- ----------------------------
INSERT INTO `attendances` VALUES (1, 4, '2024-12-11 07:58:10', '2024-12-11 17:02:56', 'attendance-photos/01JETBVVQPZC7EWV81DYFG42DK.png', 'attendance-photos/01JETBXJHNKZJMY454Y2CHX521.png', NULL, NULL, 'present', NULL, '2024-12-11 08:02:46', '2024-12-11 08:03:42', NULL, -6.16564896, 106.82376981, -6.16564896, 106.82376981);

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
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departements
-- ----------------------------
INSERT INTO `departements` VALUES (1, 'Human Resources', 'Departemen yang mengelola sumber daya manusia', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `departements` VALUES (2, 'Information Technology', 'Departemen yang mengelola teknologi informasi', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `departements` VALUES (3, 'Finance', 'Departemen yang mengelola keuangan', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `departements` VALUES (4, 'Marketing', 'Departemen yang mengelola pemasaran', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `departements` VALUES (5, 'Operations', 'Departemen yang mengelola operasional', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);

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
  `type` enum('sick','permission','annual','important','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of leave_requests
-- ----------------------------
INSERT INTO `leave_requests` VALUES (1, 3, 'annual', '2024-12-11', '2024-12-11', 'Izin Cutii', NULL, 'approved', 1, '2024-12-11 03:03:13', '2024-12-11 02:19:22', '2024-12-11 03:03:13', NULL);
INSERT INTO `leave_requests` VALUES (2, 1, 'important', '2024-12-11', '2024-12-11', 'Izin Keluarga', NULL, 'pending', NULL, NULL, '2024-12-11 02:27:11', '2024-12-11 02:32:05', '2024-12-11 02:32:05');
INSERT INTO `leave_requests` VALUES (3, 1, 'important', '2024-12-11', '2024-12-11', 'Izin Keluarga', NULL, 'pending', NULL, NULL, '2024-12-11 02:27:38', '2024-12-11 02:32:01', '2024-12-11 02:32:01');
INSERT INTO `leave_requests` VALUES (4, 3, 'important', '2024-12-11', '2024-12-11', 'ixin', NULL, 'pending', NULL, NULL, '2024-12-11 02:32:21', '2024-12-11 02:33:50', '2024-12-11 02:33:50');
INSERT INTO `leave_requests` VALUES (5, 3, 'important', '2024-12-11', '2024-12-11', 'izin', NULL, 'approved', 1, '2024-12-11 02:34:59', '2024-12-11 02:34:39', '2024-12-11 02:34:59', NULL);
INSERT INTO `leave_requests` VALUES (6, 3, 'sick', '2024-12-11', '2024-12-11', 'Itit GIGIas', NULL, 'pending', 1, '2024-12-11 03:05:43', '2024-12-11 03:04:31', '2024-12-11 06:52:36', '2024-12-11 06:52:36');
INSERT INTO `leave_requests` VALUES (7, 3, 'important', '2024-12-11', '2024-12-11', 'Ixin pentingg', NULL, 'rejected', 2, '2024-12-11 07:42:38', '2024-12-11 06:54:14', '2024-12-11 07:42:38', NULL);
INSERT INTO `leave_requests` VALUES (8, 4, 'sick', '2024-12-19', '2024-12-19', 'Izin sakit diare', 'leave-attachments/01JETAB1Z8EAHCHDQ88RF9BCG0.jpg', 'approved', 2, '2024-12-11 07:42:36', '2024-12-11 07:36:07', '2024-12-11 07:42:36', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` VALUES (2, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (4, '2024_12_02_083253_create_departements_table', 1);
INSERT INTO `migrations` VALUES (5, '2024_12_02_083258_create_users_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_12_03_031114_create_attendances_table', 1);
INSERT INTO `migrations` VALUES (7, '2024_12_03_031643_create_work_schedules_table', 1);
INSERT INTO `migrations` VALUES (8, '2024_12_03_031805_create_office_locations_table', 1);
INSERT INTO `migrations` VALUES (9, '2024_12_03_031916_create_leave_requests_table', 1);
INSERT INTO `migrations` VALUES (10, '2024_12_04_091523_create_notifications_table', 1);
INSERT INTO `migrations` VALUES (13, '2024_12_10_021014_create_permission_tables', 2);

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
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 3);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 4);
INSERT INTO `model_has_roles` VALUES (3, 'App\\Models\\User', 2);

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
INSERT INTO `notifications` VALUES ('4d86a904-db58-43c8-b9dd-7d3926ea7e32', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 2, '{\"actions\":[{\"name\":\"view\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Lihat Pengajuan\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/admin\\/leave-requests\\/\",\"view\":\"filament-actions::link-action\"}],\"body\":\"Pengajuan cuti baru dari: Ilham\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-clipboard-document-check\",\"iconColor\":null,\"status\":null,\"title\":\"Pengajuan Cuti Baru\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', '2024-12-11 07:42:45', '2024-12-11 07:36:07', '2024-12-11 07:42:45');
INSERT INTO `notifications` VALUES ('ebb04a90-ec74-42ab-8e10-7c4b012350bf', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', 3, '{\"actions\":[{\"name\":\"view\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Lihat Detail\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/admin\\/leave-requests\",\"view\":\"filament-actions::link-action\"}],\"body\":\"Maaf, pengajuan cuti ditolak.\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-x-circle\",\"iconColor\":null,\"status\":null,\"title\":\"Pengajuan Cuti Ditolak\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2024-12-11 07:42:38', '2024-12-11 07:42:38');

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
INSERT INTO `office_locations` VALUES (1, 'Kantor Pusat', 'Jl. MH Thamrin No.1, Jakarta Pusat', -6.16559563, 106.82376444, 15, '2024-12-10 04:51:57', '2024-12-11 09:06:50', NULL);

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
INSERT INTO `permissions` VALUES (1, 'view_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (2, 'view_any_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (3, 'create_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (4, 'update_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (5, 'restore_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (6, 'restore_any_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (7, 'replicate_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (8, 'reorder_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (9, 'delete_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (10, 'delete_any_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (11, 'force_delete_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (12, 'force_delete_any_attendance', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (13, 'view_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (14, 'view_any_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (15, 'create_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (16, 'update_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (17, 'restore_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (18, 'restore_any_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (19, 'replicate_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (20, 'reorder_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (21, 'delete_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (22, 'delete_any_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (23, 'force_delete_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (24, 'force_delete_any_departement', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (25, 'view_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (26, 'view_any_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (27, 'create_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (28, 'update_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (29, 'restore_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (30, 'restore_any_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (31, 'replicate_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (32, 'reorder_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (33, 'delete_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (34, 'delete_any_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (35, 'force_delete_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (36, 'force_delete_any_leave::request', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (37, 'view_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (38, 'view_any_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (39, 'create_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (40, 'update_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (41, 'restore_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (42, 'restore_any_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (43, 'replicate_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (44, 'reorder_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (45, 'delete_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (46, 'delete_any_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (47, 'force_delete_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (48, 'force_delete_any_office::location', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (49, 'view_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (50, 'view_any_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (51, 'create_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (52, 'update_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (53, 'delete_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (54, 'delete_any_role', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (55, 'view_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (56, 'view_any_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (57, 'create_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (58, 'update_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (59, 'restore_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (60, 'restore_any_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (61, 'replicate_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (62, 'reorder_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (63, 'delete_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (64, 'delete_any_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (65, 'force_delete_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (66, 'force_delete_any_user', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `permissions` VALUES (67, 'view_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (68, 'view_any_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (69, 'create_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (70, 'update_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (71, 'restore_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (72, 'restore_any_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (73, 'replicate_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (74, 'reorder_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (75, 'delete_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (76, 'delete_any_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (77, 'force_delete_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (78, 'force_delete_any_work::schedule', 'web', '2024-12-11 03:27:17', '2024-12-11 03:27:17');
INSERT INTO `permissions` VALUES (79, 'approve_leave::request', 'web', '2024-12-11 04:52:50', '2024-12-11 04:52:50');
INSERT INTO `permissions` VALUES (80, 'reject_leave::request', 'web', '2024-12-11 04:52:50', '2024-12-11 04:52:50');
INSERT INTO `permissions` VALUES (81, 'approve_work::schedule', 'web', '2024-12-11 06:32:23', '2024-12-11 06:32:23');
INSERT INTO `permissions` VALUES (82, 'reject_work::schedule', 'web', '2024-12-11 06:32:23', '2024-12-11 06:32:23');
INSERT INTO `permissions` VALUES (83, 'approve_attendance', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (84, 'reject_attendance', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (85, 'approve_departement', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (86, 'reject_departement', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (87, 'approve_office::location', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (88, 'reject_office::location', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (89, 'approve_user', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');
INSERT INTO `permissions` VALUES (90, 'reject_user', 'web', '2024-12-11 06:43:30', '2024-12-11 06:43:30');

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
INSERT INTO `role_has_permissions` VALUES (9, 3);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (13, 2);
INSERT INTO `role_has_permissions` VALUES (13, 3);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (14, 2);
INSERT INTO `role_has_permissions` VALUES (14, 3);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (15, 3);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (16, 3);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (21, 3);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (25, 2);
INSERT INTO `role_has_permissions` VALUES (25, 3);
INSERT INTO `role_has_permissions` VALUES (26, 1);
INSERT INTO `role_has_permissions` VALUES (26, 2);
INSERT INTO `role_has_permissions` VALUES (26, 3);
INSERT INTO `role_has_permissions` VALUES (27, 1);
INSERT INTO `role_has_permissions` VALUES (27, 2);
INSERT INTO `role_has_permissions` VALUES (27, 3);
INSERT INTO `role_has_permissions` VALUES (28, 1);
INSERT INTO `role_has_permissions` VALUES (28, 2);
INSERT INTO `role_has_permissions` VALUES (28, 3);
INSERT INTO `role_has_permissions` VALUES (29, 1);
INSERT INTO `role_has_permissions` VALUES (30, 1);
INSERT INTO `role_has_permissions` VALUES (31, 1);
INSERT INTO `role_has_permissions` VALUES (32, 1);
INSERT INTO `role_has_permissions` VALUES (33, 1);
INSERT INTO `role_has_permissions` VALUES (33, 2);
INSERT INTO `role_has_permissions` VALUES (33, 3);
INSERT INTO `role_has_permissions` VALUES (34, 1);
INSERT INTO `role_has_permissions` VALUES (35, 1);
INSERT INTO `role_has_permissions` VALUES (36, 1);
INSERT INTO `role_has_permissions` VALUES (37, 1);
INSERT INTO `role_has_permissions` VALUES (37, 2);
INSERT INTO `role_has_permissions` VALUES (37, 3);
INSERT INTO `role_has_permissions` VALUES (38, 1);
INSERT INTO `role_has_permissions` VALUES (38, 2);
INSERT INTO `role_has_permissions` VALUES (38, 3);
INSERT INTO `role_has_permissions` VALUES (39, 1);
INSERT INTO `role_has_permissions` VALUES (39, 3);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (40, 3);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (43, 1);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (45, 3);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (49, 3);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (50, 3);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (54, 1);
INSERT INTO `role_has_permissions` VALUES (55, 1);
INSERT INTO `role_has_permissions` VALUES (55, 3);
INSERT INTO `role_has_permissions` VALUES (56, 1);
INSERT INTO `role_has_permissions` VALUES (56, 3);
INSERT INTO `role_has_permissions` VALUES (57, 1);
INSERT INTO `role_has_permissions` VALUES (57, 3);
INSERT INTO `role_has_permissions` VALUES (58, 1);
INSERT INTO `role_has_permissions` VALUES (58, 3);
INSERT INTO `role_has_permissions` VALUES (59, 1);
INSERT INTO `role_has_permissions` VALUES (60, 1);
INSERT INTO `role_has_permissions` VALUES (61, 1);
INSERT INTO `role_has_permissions` VALUES (62, 1);
INSERT INTO `role_has_permissions` VALUES (63, 1);
INSERT INTO `role_has_permissions` VALUES (63, 3);
INSERT INTO `role_has_permissions` VALUES (64, 1);
INSERT INTO `role_has_permissions` VALUES (65, 1);
INSERT INTO `role_has_permissions` VALUES (66, 1);
INSERT INTO `role_has_permissions` VALUES (67, 1);
INSERT INTO `role_has_permissions` VALUES (67, 2);
INSERT INTO `role_has_permissions` VALUES (67, 3);
INSERT INTO `role_has_permissions` VALUES (68, 1);
INSERT INTO `role_has_permissions` VALUES (68, 2);
INSERT INTO `role_has_permissions` VALUES (68, 3);
INSERT INTO `role_has_permissions` VALUES (69, 1);
INSERT INTO `role_has_permissions` VALUES (69, 3);
INSERT INTO `role_has_permissions` VALUES (70, 1);
INSERT INTO `role_has_permissions` VALUES (70, 3);
INSERT INTO `role_has_permissions` VALUES (71, 1);
INSERT INTO `role_has_permissions` VALUES (71, 3);
INSERT INTO `role_has_permissions` VALUES (72, 1);
INSERT INTO `role_has_permissions` VALUES (73, 1);
INSERT INTO `role_has_permissions` VALUES (73, 3);
INSERT INTO `role_has_permissions` VALUES (74, 1);
INSERT INTO `role_has_permissions` VALUES (75, 1);
INSERT INTO `role_has_permissions` VALUES (75, 3);
INSERT INTO `role_has_permissions` VALUES (76, 1);
INSERT INTO `role_has_permissions` VALUES (77, 1);
INSERT INTO `role_has_permissions` VALUES (77, 3);
INSERT INTO `role_has_permissions` VALUES (78, 1);
INSERT INTO `role_has_permissions` VALUES (79, 1);
INSERT INTO `role_has_permissions` VALUES (79, 3);
INSERT INTO `role_has_permissions` VALUES (80, 1);
INSERT INTO `role_has_permissions` VALUES (80, 3);
INSERT INTO `role_has_permissions` VALUES (81, 1);
INSERT INTO `role_has_permissions` VALUES (82, 1);
INSERT INTO `role_has_permissions` VALUES (83, 1);
INSERT INTO `role_has_permissions` VALUES (84, 1);
INSERT INTO `role_has_permissions` VALUES (85, 1);
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
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'super_admin', 'web', '2024-12-11 03:27:16', '2024-12-11 03:27:16');
INSERT INTO `roles` VALUES (2, 'Employee', 'web', '2024-12-11 03:29:02', '2024-12-11 03:29:02');
INSERT INTO `roles` VALUES (3, 'admin', 'web', '2024-12-11 03:29:25', '2024-12-11 03:29:25');

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
  CONSTRAINT `users_departement_id_foreign` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Iqbal', 'iqbal@gmail.com', NULL, '$2y$12$Yy3Ak743TMYV0YVd3ji6EuR2V2mWjqK1oOybCMsT03zg2xU6N8TWS', NULL, '089608780861', 'Bogor\nJawa barat', 'HR', '144856 344 4563', NULL, NULL, '2024-12-10 04:49:15', '2024-12-10 04:57:45', NULL);
INSERT INTO `users` VALUES (2, 'Nisa', 'nisa@gmail.com', NULL, '$2y$12$gegXKgSAwj5tGmUC8JbaK.kcN7UCLpzKaSTt5yC110v1x3JzZZvUa', 1, '089608780861', 'Bogor\nJawa barat', 'Programmer', '144856 344 5723', NULL, NULL, '2024-12-10 04:56:16', '2024-12-10 04:56:16', NULL);
INSERT INTO `users` VALUES (3, 'Lukman', 'lukman@gmail.com', NULL, '$2y$12$tGlUDlyEOM8k28kVsRwVc.rh8Uq7ff0hE2vwhI/xfsZr92tUEbPH.', 3, '089608780861', 'Bogor\nJawa barat', 'Finance', '144856 344 5563', NULL, NULL, '2024-12-10 04:57:18', '2024-12-10 04:57:18', NULL);
INSERT INTO `users` VALUES (4, 'Ilham', 'ilham@gmail.com', NULL, '$2y$12$qQ40T2FHDelDe445.dP6UO8Z4O3BmGM9knLbZcz7/7CfaULU2VW6K', 4, '089608780861', 'Bogor\nJawa barat', 'HR Junior', '144856 344 45263', 'user-photos/01JET9S12FR4XQ4S6W2SXZMYRE.jpg', NULL, '2024-12-11 07:26:17', '2024-12-11 07:26:17', NULL);

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
INSERT INTO `work_schedules` VALUES (1, 'Jadwal Normal', '07:00:00', '08:30:00', '16:00:00', '17:30:00', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `work_schedules` VALUES (2, 'Jadwal Shift Pagi', '06:00:00', '07:30:00', '14:00:00', '15:30:00', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);
INSERT INTO `work_schedules` VALUES (3, 'Jadwal Shift Siang', '14:00:00', '15:30:00', '22:00:00', '23:30:00', '2024-12-10 04:51:57', '2024-12-10 04:51:57', NULL);

SET FOREIGN_KEY_CHECKS = 1;
