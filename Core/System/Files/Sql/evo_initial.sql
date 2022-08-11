-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2022 at 03:45 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evo`
--

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `hex_value` char(6) DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`id`, `name`, `hex_value`, `is_core`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'red', 'ef4444', 1, NULL, '2022-03-25 10:23:04', '2022-06-02 14:24:31'),
(2, 'yellow', 'eab308', 1, NULL, '2022-03-25 10:23:44', '2022-06-02 14:24:31'),
(3, 'orange', 'f97316', 1, NULL, '2022-03-25 10:24:19', '2022-06-02 14:24:31'),
(4, 'green', '22c55e', 1, NULL, '2022-03-25 10:24:34', '2022-06-02 14:24:31'),
(5, 'blue', '3b82f6', 1, NULL, '2022-04-11 11:53:40', '2022-06-02 14:31:30'),
(6, 'slate', '64748b', 1, NULL, '2022-06-02 14:24:31', NULL),
(7, 'gray', '6b7280', 1, NULL, '2022-06-02 14:24:31', NULL),
(8, 'zinc', '71717a', 1, NULL, '2022-06-02 14:24:31', NULL),
(9, 'neutral', '737373', 1, NULL, '2022-06-02 14:24:31', NULL),
(10, 'stone', '78716c', 1, NULL, '2022-06-02 14:24:31', NULL),
(11, 'amber', 'f59e0b', 1, NULL, '2022-06-02 14:24:31', NULL),
(12, 'lime', '84cc16', 1, NULL, '2022-06-02 14:24:31', NULL),
(13, 'emerald', '10b981', 1, NULL, '2022-06-02 14:24:31', NULL),
(14, 'teal', '14b8a6', 1, NULL, '2022-06-02 14:24:31', NULL),
(15, 'cyan', '06b6d4', 1, NULL, '2022-06-02 14:24:31', NULL),
(16, 'sky', '0ea5e9', 1, NULL, '2022-06-02 14:24:31', NULL),
(17, 'blue', '3b82f6', 1, NULL, '2022-06-02 14:24:31', NULL),
(18, 'indigo', '6366f1', 1, NULL, '2022-06-02 14:24:31', NULL),
(19, 'violet', '8b5cf6', 1, NULL, '2022-06-02 14:24:31', NULL),
(20, 'purple', 'a855f7', 1, NULL, '2022-06-02 14:24:31', NULL),
(21, 'fuchsia', 'd946ef', 1, NULL, '2022-06-02 14:24:31', NULL),
(22, 'pink', 'ec4899', 1, NULL, '2022-06-02 14:24:31', NULL),
(23, 'rose', 'f43f5e', 1, NULL, '2022-06-02 14:24:31', NULL),
(24, 'slate', '64748b', 1, NULL, '2022-06-02 14:24:31', NULL),
(25, 'gray', '6b7280', 1, NULL, '2022-06-02 14:24:31', NULL),
(26, 'zinc', '71717a', 1, NULL, '2022-06-02 14:24:31', NULL),
(27, 'neutral', '737373', 1, NULL, '2022-06-02 14:24:31', NULL),
(28, 'stone', '78716c', 1, NULL, '2022-06-02 14:24:31', NULL),
(29, 'amber', 'f59e0b', 1, NULL, '2022-06-02 14:24:31', NULL),
(30, 'lime', '84cc16', 1, NULL, '2022-06-02 14:24:31', NULL),
(31, 'emerald', '10b981', 1, NULL, '2022-06-02 14:24:31', NULL),
(32, 'teal', '14b8a6', 1, NULL, '2022-06-02 14:24:31', NULL),
(33, 'cyan', '06b6d4', 1, NULL, '2022-06-02 14:24:31', NULL),
(34, 'sky', '0ea5e9', 1, NULL, '2022-06-02 14:24:31', NULL),
(36, 'indigo', '6366f1', 1, NULL, '2022-06-02 14:24:31', NULL),
(37, 'violet', '8b5cf6', 1, NULL, '2022-06-02 14:24:31', NULL),
(38, 'purple', 'a855f7', 1, NULL, '2022-06-02 14:24:31', NULL),
(39, 'fuchsia', 'd946ef', 1, NULL, '2022-06-02 14:24:31', NULL),
(40, 'pink', 'ec4899', 1, NULL, '2022-06-02 14:24:31', NULL),
(41, 'rose', 'f43f5e', 1, NULL, '2022-06-02 14:24:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `section_id` int(10) UNSIGNED DEFAULT NULL,
  `position` int(10) NOT NULL,
  `link` varchar(100) NOT NULL,
  `title_tag` varchar(50) NOT NULL,
  `icon` varchar(20) DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 0,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `section_id`, `position`, `link`, `title_tag`, `icon`, `is_core`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'Sections', 'These are major divisions that group menus with similar tasks or menus of the same app.', 2, 1, '/section/index', 'Sections', NULL, 1, 1, 52, '2022-03-25 09:13:59', '2022-07-24 16:31:43'),
(2, 'Menus', 'All menus in the system and app. Each menu will appear below a section.', 2, 2, '/menu/index', 'Menus', NULL, 1, 1, 52, '2022-03-25 09:14:38', '2022-07-24 16:31:43'),
(3, 'Roles', 'These are roles that must be assigned to every user in the system, so as to control who has access to what.', 1, 1, '/role/index', 'Roles', NULL, 1, 1, 52, '2022-03-25 09:15:09', '2022-07-24 16:31:43'),
(4, 'Permissions', 'All permissions available in the system.', 1, 2, '/permission/index', 'Permissions', NULL, 1, 1, 52, '2022-03-25 09:15:46', '2022-07-24 16:31:43'),
(5, 'Users', 'All users with accounts in the system.', 1, 3, '/user/index', 'Users', NULL, 1, 1, 52, '2022-03-25 09:16:42', '2022-07-24 16:31:43'),
(6, 'Modules', 'All modules, meaning a set of MVCE.', 2, 3, '/module/index', 'Modules', NULL, 1, 1, 52, '2022-03-25 09:18:40', '2022-07-24 16:31:43'),
(7, 'Colors', 'All colours that correspond to a particular status.', 1, 4, '/color/index', 'Colors', NULL, 1, 1, 52, '2022-03-25 09:19:36', '2022-07-24 16:31:43'),
(8, 'Statuses', 'The basic statuses used by the system. Any status for a specific app will be extended from here.', 1, 5, '/status/index', 'Statuses', NULL, 1, 1, 52, '2022-03-25 09:20:17', '2022-07-24 16:31:43'),
(9, 'State', 'A new module for activating and suspending stuff in the system.', 1, 6, '/state/index', 'States', NULL, 1, 1, 52, '2022-04-05 10:17:00', '2022-07-24 16:31:44'),
(15, 'Test', 'Sample.', 3, 2, '/test/index', 'Test', NULL, 1, 1, 17, '2022-04-06 09:58:57', '2022-07-24 16:31:43'),
(16, 'Sample', 'Sample.', 3, 1, '/sample/index', 'Samplezzz', NULL, 1, 1, 17, '2022-04-06 09:59:25', '2022-07-24 16:31:44'),
(18, 'User Role', 'Sample.', 1, 3, '/user_role/index', 'User - Role', NULL, 0, 1, 17, '2022-04-13 14:58:22', '2022-07-24 16:31:44'),
(19, 'Role Permission', 'Sample.', 1, 1, '/role_permission/index', 'Role - Permission', NULL, 0, 1, 17, '2022-04-13 15:57:40', '2022-07-24 16:31:44'),
(22, 'Division', 'Sample', 5, 1, '/division/index', 'Church Levels: Division', NULL, 0, 1, 17, '2022-06-24 09:24:51', '2022-07-24 16:31:44'),
(23, 'Church Unions', 'Sample', 5, 2, '/church_union/index', 'Levels: Unions', NULL, 0, 1, 17, '2022-06-24 13:22:46', '2022-07-24 16:31:44'),
(24, 'Union Types', 'Sample', 6, 1, '/union_type/index', 'Accessories: Union Type', NULL, 0, 1, 17, '2022-06-24 13:32:58', '2022-07-24 16:31:44'),
(25, 'Conferences', 'Sample', 5, 3, '/conference/index', 'Sda: Conferences', NULL, 0, 1, 17, '2022-07-08 14:58:59', '2022-07-24 16:31:43'),
(26, 'Districts', 'Sample', 5, 4, '/district/index', 'SDA: Districts', NULL, 0, 1, 17, '2022-07-08 15:06:49', '2022-07-24 16:31:43'),
(27, 'Churches', 'Sample', 5, 5, '/church/index', 'SDA: Churches', NULL, 0, 1, 17, '2022-07-08 15:13:18', '2022-07-24 16:31:43');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `foreign_keys` text NOT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 0,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `foreign_keys`, `is_core`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(3, 'Menu', 'Management of the Menu module.', 'section', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:13'),
(4, 'Permission', 'Management of the Permission module.', 'section', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:13'),
(5, 'Role', 'Management of the Role module.', '', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:13'),
(6, 'Section', 'Management of the Section module.', '', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:13'),
(7, 'State', 'Management of the State module.', 'color', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:13'),
(8, 'Status', 'Management of the Status module.', 'color', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:14'),
(9, 'User', 'Management of the User module.', 'status', 1, 1, 17, '2022-04-05 16:34:42', '2022-04-05 16:40:14'),
(10, 'Test', 'Management of the Test module.', '', 1, 1, 17, '2022-04-12 09:54:01', '2022-06-20 14:51:42'),
(11, 'Sample', 'Management of the Sample module.', 'color', 1, 1, 17, '2022-04-12 14:13:05', '2022-06-20 14:51:45'),
(13, 'User Role', 'Management of the User Role module.', 'user, role', 1, 1, 17, '2022-04-13 15:52:41', '2022-04-14 09:29:55'),
(15, 'Role Permission', 'Management of the Role Permission module.', 'role, permission', 1, 1, 17, '2022-04-13 16:18:39', '2022-04-14 09:29:59'),
(23, 'Division', 'Management of the Division module.', 'color', 0, 1, 17, '2022-06-24 12:52:51', NULL),
(24, 'Union Type', 'Management of the Union Type module.', 'color', 0, 1, 17, '2022-06-24 13:33:25', NULL),
(25, 'Church Union', 'Management of the Church Union module.', 'union_type,color', 0, 1, 17, '2022-06-24 13:35:08', NULL),
(26, 'Conference', 'Management of the Conference module.', 'church_union', 0, 1, 17, '2022-07-08 15:02:28', NULL),
(27, 'District', 'Management of the District module.', 'conference,church_union', 0, 1, 17, '2022-07-08 15:07:50', NULL),
(28, 'Church', 'Management of the Church module.', 'district, conference, church_union', 0, 1, 17, '2022-07-08 15:12:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `section_id` int(10) UNSIGNED DEFAULT NULL,
  `display_name` varchar(130) NOT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `state_id` int(10) UNSIGNED DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `name`, `description`, `section_id`, `display_name`, `is_core`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'sample_permission', 'Sample.', 1, 'Sample', 1, 2, 52, '2022-03-25 08:58:03', '2022-04-11 11:08:03'),
(2, 'view_sections', 'View a list of all the sections.', 2, 'Create Section', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:51:00'),
(3, 'create_section', 'Create a new section.', 2, 'View Sections', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:51:00'),
(4, 'edit_section', 'Edit an existing section.', 2, 'Edit Section', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:50:59'),
(5, 'delete_section', 'Delete an existing section.', 2, 'Delete Section', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:50:59'),
(6, 'suspend_section', 'Suspend an existing section.', 2, 'Suspend Section', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:51:00'),
(7, 'activate_section', 'Activate an existing section.', 2, 'Activate Section', 1, 1, 52, '2022-03-25 09:13:59', '2022-04-05 16:51:00'),
(8, 'view_menus', 'View a list of all the menus.', 2, 'View Menus', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(9, 'create_menu', 'Create a new menu.', 2, 'Create Menu', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(10, 'edit_menu', 'Edit an existing menu.', 2, 'Edit Menu', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(11, 'delete_menu', 'Delete an existing menu.', 2, 'Edit Menu', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(12, 'suspend_menu', 'Suspend an existing menu.', 2, 'Suspend Menu', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(13, 'activate_menu', 'Activate an existing menu.', 2, 'Activate Menu', 1, 1, 52, '2022-03-25 09:14:38', '2022-04-05 16:51:00'),
(14, 'view_roles', 'View a list of all the roles.', 1, 'View Roles', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:51:00'),
(15, 'create_role', 'Create a new role.', 1, 'Create Role', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:51:00'),
(16, 'edit_role', 'Edit an existing role.', 1, 'Edit Role', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:50:59'),
(17, 'delete_role', 'Delete an existing role.', 1, 'Delete Role', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:51:00'),
(18, 'suspend_role', 'Suspend an existing role.', 1, 'Suspend Role', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:50:59'),
(19, 'activate_role', 'Activate an existing role.', 1, 'Activate Role', 1, 1, 52, '2022-03-25 09:15:09', '2022-04-05 16:50:59'),
(20, 'view_permissions', 'View a list of all the permissions.', 1, 'View Permissions', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:51:00'),
(21, 'create_permission', 'Create a new permission.', 1, 'Create Permission', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:51:00'),
(22, 'edit_permission', 'Edit an existing permission.', 1, 'Edit Permission', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:51:00'),
(23, 'delete_permission', 'Delete an existing permission.', 1, 'Delete Permission', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:51:00'),
(24, 'suspend_permission', 'Suspend an existing permission.', 1, 'Suspend Permission', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:50:59'),
(25, 'activate_permission', 'Activate an existing permission.', 1, 'Suspend Permission', 1, 1, 52, '2022-03-25 09:15:46', '2022-04-05 16:50:59'),
(26, 'view_users', 'View a list of all the users.', 1, 'View Users', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(27, 'create_user', 'Create a new user.', 1, 'Create User', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(28, 'edit_user', 'Edit an existing user.', 1, 'Edit User', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(29, 'delete_user', 'Delete an existing user.', 1, 'Delete User', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(30, 'suspend_user', 'Suspend an existing user.', 1, 'Suspend User', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(31, 'activate_user', 'Activate an existing user.', 1, 'Activate User', 1, 1, 52, '2022-03-25 09:16:42', '2022-04-05 16:50:59'),
(32, 'view_modules', 'View a list of all the modules.', 2, 'View Modules', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(33, 'create_module', 'Create a new module.', 2, 'Create Module', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(34, 'edit_module', 'Edit an existing module.', 2, 'Edit Module', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(35, 'delete_module', 'Delete an existing module.', 2, 'Delete Module', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(36, 'suspend_module', 'Suspend an existing module.', 2, 'Suspend Module', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(37, 'activate_module', 'Activate an existing module.', 2, 'Activate Module', 1, 1, 52, '2022-03-25 09:18:40', '2022-04-05 16:50:59'),
(38, 'view_colors', 'View a list of all the colors.', 1, 'View Colors', 1, 1, 52, '2022-03-25 09:19:35', '2022-04-05 16:50:59'),
(39, 'create_color', 'Create a new color.', 1, 'Create Color', 1, 1, 52, '2022-03-25 09:19:35', '2022-04-05 16:50:59'),
(40, 'edit_color', 'Edit an existing color.', 1, 'Edit Color', 1, 1, 52, '2022-03-25 09:19:35', '2022-04-05 16:50:59'),
(41, 'delete_color', 'Delete an existing color.', 1, 'Delete Color', 1, 1, 52, '2022-03-25 09:19:36', '2022-04-05 16:50:59'),
(42, 'suspend_color', 'Suspend an existing color.', 1, 'Suspend Color', 1, 1, 52, '2022-03-25 09:19:36', '2022-04-05 16:50:59'),
(43, 'activate_color', 'Activate an existing color.', 1, 'Activate Color', 1, 1, 52, '2022-03-25 09:19:36', '2022-04-05 16:50:59'),
(44, 'view_statuses', 'View a list of all the statuses.', 1, 'Activate Color', 1, 1, 52, '2022-03-25 09:19:36', '2022-04-05 16:50:59'),
(45, 'create_status', 'Create a new status.', 1, 'Create Status', 1, 1, 52, '2022-03-25 09:20:17', '2022-04-05 16:50:59'),
(46, 'edit_status', 'Edit an existing status.', 1, 'Edit Status', 1, 1, 52, '2022-03-25 09:20:17', '2022-04-05 16:50:59'),
(47, 'delete_status', 'Delete an existing status.', 1, 'Delete Status', 1, 1, 52, '2022-03-25 09:20:17', '2022-04-05 16:50:59'),
(48, 'suspend_status', 'Suspend an existing status.', 1, 'Suspend Status', 1, 1, 52, '2022-03-25 09:20:17', '2022-04-05 16:50:59'),
(49, 'activate_status', 'Activate an existing status.', 1, 'Activate Status', 1, 1, 52, '2022-03-25 09:20:17', '2022-04-05 16:50:59'),
(50, 'view_states', 'View a list of all the states.', 1, 'View States', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(51, 'create_state', 'Create a new state.', 1, 'Create State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(52, 'edit_state', 'Edit an existing state.', 1, 'Edit State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(53, 'delete_state', 'Delete an existing state.', 1, 'Delete State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(54, 'suspend_state', 'Suspend an existing state.', 1, 'Suspend State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(55, 'activate_state', 'Activate an existing state.', 1, 'Activate State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(56, 'view_samples', 'View a list of all the samples.', 3, 'Activate State', 1, 1, 52, '2022-04-05 10:17:00', '2022-04-05 16:50:59'),
(57, 'create_sample', 'Create a new sample.', 3, 'Create Sample', 1, 1, 17, '2022-04-05 10:54:21', '2022-04-05 16:50:59'),
(58, 'edit_sample', 'Edit an existing sample.', 3, 'Edit Sample', 1, 1, 17, '2022-04-05 10:54:21', '2022-04-05 16:50:59'),
(59, 'delete_sample', 'Delete an existing sample.', 3, 'Delete Sample', 1, 1, 17, '2022-04-05 10:54:21', '2022-04-05 16:50:59'),
(60, 'suspend_sample', 'Suspend an existing sample.', 3, 'Suspend Sample', 1, 1, 17, '2022-04-05 10:54:21', '2022-04-05 16:50:59'),
(61, 'activate_sample', 'Activate an existing sample.', 3, 'Activate Sample', 1, 1, 17, '2022-04-05 10:54:21', '2022-04-05 16:50:59'),
(63, 'regenerate_module', 'Sample.', 3, 'Regenerate Module', 1, 1, 17, '2022-04-05 12:50:43', '2022-04-05 16:50:59'),
(65, 'view_tests', 'View a list of all the tests.', 3, 'View Tests', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(66, 'create_test', 'Create a new test.', 3, 'View Tests', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(67, 'edit_test', 'Edit an existing test.', 3, 'Edit Test', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(68, 'delete_test', 'Delete an existing test.', 3, 'Delete Test', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(69, 'suspend_test', 'Suspend an existing test.', 3, 'Suspend Test', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(70, 'activate_test', 'Activate an existing test.', 3, 'Activate Test', 1, 1, 17, '2022-04-05 14:15:57', '2022-04-05 16:50:59'),
(85, 'assign_user_to_role', 'Sample.', 1, 'Assign User To Role', 0, 1, 17, '2022-04-12 14:49:05', NULL),
(86, 'view_user_roles', 'View a list of all the user roles.', 1, 'View User Roles', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(87, 'create_user_role', 'Create a new user role.', 1, 'Create User Role', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(88, 'edit_user_role', 'Edit an existing user role.', 1, 'Edit User Role', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(89, 'delete_user_role', 'Delete an existing user role.', 1, 'Delete User Role', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(90, 'suspend_user_role', 'Suspend an existing user role.', 1, 'Suspend User Role', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(91, 'activate_user_role', 'Activate an existing user role.', 1, 'Activate User Role', 0, 1, 17, '2022-04-13 14:58:22', NULL),
(92, 'view_role_permissions', 'View a list of all the role permissions.', 1, 'View Role Permissions', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(93, 'create_role_permission', 'Create a new role permission.', 1, 'Create Role Permission', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(94, 'edit_role_permission', 'Edit an existing role permission.', 1, 'Edit Role Permission', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(95, 'delete_role_permission', 'Delete an existing role permission.', 1, 'Delete Role Permission', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(96, 'suspend_role_permission', 'Suspend an existing role permission.', 1, 'Suspend Role Permission', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(97, 'activate_role_permission', 'Activate an existing role permission.', 1, 'Activate Role Permission', 0, 1, 17, '2022-04-13 15:57:40', NULL),
(110, 'view_church_levels', 'View a list of all the church levels', 5, 'View Church Levels', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(111, 'create_church_level', 'Create a new church level', 5, 'Create Church Level', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(112, 'edit_church_level', 'Edit an existing church level', 5, 'Edit Church Level', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(113, 'delete_church_level', 'Delete an existing church level', 5, 'Delete Church Level', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(114, 'suspend_church_level', 'Suspend an existing church level', 5, 'Suspend Church Level', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(115, 'activate_church_level', 'Activate an existing church level', 5, 'Activate Church Level', 1, 1, 17, '2022-06-24 09:20:21', '2022-06-24 09:21:43'),
(116, 'view_divisions', 'View a list of all the divisions', 5, 'View Divisions', 1, 1, 17, '2022-06-24 09:24:50', NULL),
(117, 'create_division', 'Create a new division', 5, 'Create Division', 1, 1, 17, '2022-06-24 09:24:50', NULL),
(118, 'edit_division', 'Edit an existing division', 5, 'Edit Division', 1, 1, 17, '2022-06-24 09:24:50', NULL),
(119, 'delete_division', 'Delete an existing division', 5, 'Delete Division', 1, 1, 17, '2022-06-24 09:24:50', NULL),
(120, 'suspend_division', 'Suspend an existing division', 5, 'Suspend Division', 1, 1, 17, '2022-06-24 09:24:50', NULL),
(121, 'activate_division', 'Activate an existing division', 5, 'Activate Division', 1, 1, 17, '2022-06-24 09:24:51', NULL),
(122, 'view_church_unions', 'View a list of all the church unions', 5, 'View Church Unions', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(123, 'create_church_union', 'Create a new church union', 5, 'Create Church Union', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(124, 'edit_church_union', 'Edit an existing church union', 5, 'Edit Church Union', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(125, 'delete_church_union', 'Delete an existing church union', 5, 'Delete Church Union', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(126, 'suspend_church_union', 'Suspend an existing church union', 5, 'Suspend Church Union', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(127, 'activate_church_union', 'Activate an existing church union', 5, 'Activate Church Union', 1, 1, 17, '2022-06-24 13:22:46', NULL),
(128, 'view_union_types', 'View a list of all the union types', 6, 'View Union Types', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(129, 'create_union_type', 'Create a new union type', 6, 'Create Union Type', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(130, 'edit_union_type', 'Edit an existing union type', 6, 'Edit Union Type', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(131, 'delete_union_type', 'Delete an existing union type', 6, 'Delete Union Type', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(132, 'suspend_union_type', 'Suspend an existing union type', 6, 'Suspend Union Type', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(133, 'activate_union_type', 'Activate an existing union type', 6, 'Activate Union Type', 1, 1, 17, '2022-06-24 13:32:58', NULL),
(134, 'view_conferences', 'View a list of all the conferences', 5, 'View Conferences', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(135, 'create_conference', 'Create a new conference', 5, 'Create Conference', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(136, 'edit_conference', 'Edit an existing conference', 5, 'Edit Conference', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(137, 'delete_conference', 'Delete an existing conference', 5, 'Delete Conference', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(138, 'suspend_conference', 'Suspend an existing conference', 5, 'Suspend Conference', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(139, 'activate_conference', 'Activate an existing conference', 5, 'Activate Conference', 1, 1, 17, '2022-07-08 14:58:59', NULL),
(140, 'view_districts', 'View a list of all the districts', 5, 'View Districts', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(141, 'create_district', 'Create a new district', 5, 'Create District', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(142, 'edit_district', 'Edit an existing district', 5, 'Edit District', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(143, 'delete_district', 'Delete an existing district', 5, 'Delete District', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(144, 'suspend_district', 'Suspend an existing district', 5, 'Suspend District', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(145, 'activate_district', 'Activate an existing district', 5, 'Activate District', 1, 1, 17, '2022-07-08 15:06:48', NULL),
(146, 'view_churches', 'View a list of all the churches', 5, 'View Churches', 1, 1, 17, '2022-07-08 15:13:18', NULL),
(147, 'create_church', 'Create a new church', 5, 'Create Church', 1, 1, 17, '2022-07-08 15:13:18', NULL),
(148, 'edit_church', 'Edit an existing church', 5, 'Edit Church', 1, 1, 17, '2022-07-08 15:13:18', NULL),
(149, 'delete_church', 'Delete an existing church', 5, 'Delete Church', 1, 1, 17, '2022-07-08 15:13:18', NULL),
(150, 'suspend_church', 'Suspend an existing church', 5, 'Suspend Church', 1, 1, 17, '2022-07-08 15:13:18', NULL),
(151, 'activate_church', 'Activate an existing church', 5, 'Activate Church', 1, 1, 17, '2022-07-08 15:13:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL DEFAULT 'Sample role description',
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `is_core`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'Admin', 'He oversees everything, so he must INITIALLY have access ONLY to VIEW all activities going on in the system.', 1, 1, 52, '2022-03-25 08:59:31', '2022-04-11 08:27:50'),
(2, 'Developer', 'He creates everything, he must always have full access to everything. He must always be deactivated once the system is complete, and before it is handed over to the client.', 1, 1, 52, '2022-03-25 08:59:53', '2022-04-05 16:52:43'),
(7, 'Sample', 'Role description', 0, 2, 17, '2022-04-11 08:23:18', '2022-04-11 08:27:54');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `permission_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(2, 2, 2, 1, 17, '2022-04-13 16:14:43', NULL),
(3, 2, 3, 1, 17, '2022-04-13 16:14:43', NULL),
(4, 2, 4, 1, 17, '2022-04-13 16:14:43', NULL),
(5, 2, 5, 1, 17, '2022-04-13 16:14:43', NULL),
(6, 2, 6, 1, 17, '2022-04-13 16:14:43', NULL),
(7, 2, 7, 1, 17, '2022-04-13 16:14:43', NULL),
(8, 2, 8, 1, 17, '2022-04-13 16:14:43', NULL),
(9, 2, 9, 1, 17, '2022-04-13 16:14:43', NULL),
(10, 2, 10, 1, 17, '2022-04-13 16:14:43', NULL),
(11, 2, 11, 1, 17, '2022-04-13 16:14:43', NULL),
(12, 2, 12, 1, 17, '2022-04-13 16:14:43', NULL),
(13, 2, 13, 1, 17, '2022-04-13 16:14:43', NULL),
(14, 2, 14, 1, 17, '2022-04-13 16:14:43', NULL),
(15, 2, 15, 1, 17, '2022-04-13 16:14:43', NULL),
(16, 2, 16, 1, 17, '2022-04-13 16:14:43', NULL),
(17, 2, 17, 1, 17, '2022-04-13 16:14:43', NULL),
(18, 2, 18, 1, 17, '2022-04-13 16:14:43', NULL),
(19, 2, 19, 1, 17, '2022-04-13 16:14:43', NULL),
(20, 2, 20, 1, 17, '2022-04-13 16:14:43', NULL),
(21, 2, 21, 1, 17, '2022-04-13 16:14:43', NULL),
(22, 2, 22, 1, 17, '2022-04-13 16:14:43', NULL),
(23, 2, 23, 1, 17, '2022-04-13 16:14:43', NULL),
(24, 2, 24, 1, 17, '2022-04-13 16:14:43', NULL),
(25, 2, 25, 1, 17, '2022-04-13 16:14:43', NULL),
(26, 2, 26, 1, 17, '2022-04-13 16:14:43', NULL),
(27, 2, 27, 1, 17, '2022-04-13 16:14:43', NULL),
(28, 2, 28, 1, 17, '2022-04-13 16:14:43', NULL),
(29, 2, 29, 1, 17, '2022-04-13 16:14:43', NULL),
(30, 2, 30, 1, 17, '2022-04-13 16:14:43', NULL),
(31, 2, 31, 1, 17, '2022-04-13 16:14:43', NULL),
(32, 2, 32, 1, 17, '2022-04-13 16:14:43', NULL),
(33, 2, 33, 1, 17, '2022-04-13 16:14:43', NULL),
(34, 2, 34, 1, 17, '2022-04-13 16:14:43', NULL),
(35, 2, 35, 1, 17, '2022-04-13 16:14:43', NULL),
(36, 2, 36, 1, 17, '2022-04-13 16:14:43', NULL),
(37, 2, 37, 1, 17, '2022-04-13 16:14:43', NULL),
(38, 2, 38, 1, 17, '2022-04-13 16:14:43', NULL),
(39, 2, 39, 1, 17, '2022-04-13 16:14:43', NULL),
(40, 2, 40, 1, 17, '2022-04-13 16:14:43', NULL),
(41, 2, 41, 1, 17, '2022-04-13 16:14:43', NULL),
(42, 2, 42, 1, 17, '2022-04-13 16:14:43', NULL),
(43, 2, 43, 1, 17, '2022-04-13 16:14:43', NULL),
(44, 2, 44, 1, 17, '2022-04-13 16:14:43', NULL),
(45, 2, 45, 1, 17, '2022-04-13 16:14:43', NULL),
(46, 2, 46, 1, 17, '2022-04-13 16:14:43', NULL),
(47, 2, 47, 1, 17, '2022-04-13 16:14:43', NULL),
(48, 2, 48, 1, 17, '2022-04-13 16:14:43', NULL),
(49, 2, 49, 1, 17, '2022-04-13 16:14:43', NULL),
(50, 2, 50, 1, 17, '2022-04-13 16:14:43', NULL),
(51, 2, 51, 1, 17, '2022-04-13 16:14:43', NULL),
(52, 2, 52, 1, 17, '2022-04-13 16:14:43', NULL),
(53, 2, 53, 1, 17, '2022-04-13 16:14:43', NULL),
(54, 2, 54, 1, 17, '2022-04-13 16:14:43', NULL),
(55, 2, 55, 1, 17, '2022-04-13 16:14:43', NULL),
(56, 2, 56, 1, 17, '2022-04-13 16:14:43', NULL),
(57, 2, 57, 1, 17, '2022-04-13 16:14:43', NULL),
(58, 2, 58, 1, 17, '2022-04-13 16:14:43', NULL),
(59, 2, 59, 1, 17, '2022-04-13 16:14:43', NULL),
(60, 2, 60, 1, 17, '2022-04-13 16:14:43', NULL),
(61, 2, 61, 1, 17, '2022-04-13 16:14:43', NULL),
(62, 2, 63, 1, 17, '2022-04-13 16:14:43', NULL),
(64, 2, 65, 1, 17, '2022-04-13 16:14:43', NULL),
(65, 2, 66, 1, 17, '2022-04-13 16:14:43', NULL),
(66, 2, 67, 1, 17, '2022-04-13 16:14:43', NULL),
(67, 2, 68, 1, 17, '2022-04-13 16:14:43', NULL),
(68, 2, 69, 1, 17, '2022-04-13 16:14:43', NULL),
(69, 2, 70, 1, 17, '2022-04-13 16:14:43', NULL),
(84, 2, 85, 1, 17, '2022-04-13 16:14:43', NULL),
(85, 2, 86, 1, 17, '2022-04-13 16:14:43', NULL),
(86, 2, 87, 1, 17, '2022-04-13 16:14:43', NULL),
(87, 2, 88, 1, 17, '2022-04-13 16:14:43', NULL),
(88, 2, 89, 1, 17, '2022-04-13 16:14:43', NULL),
(89, 2, 90, 1, 17, '2022-04-13 16:14:43', NULL),
(90, 2, 91, 1, 17, '2022-04-13 16:14:43', NULL),
(91, 2, 92, 1, 17, '2022-04-13 16:14:43', NULL),
(92, 2, 93, 1, 17, '2022-04-13 16:14:43', NULL),
(93, 2, 94, 1, 17, '2022-04-13 16:14:43', NULL),
(94, 2, 95, 1, 17, '2022-04-13 16:14:43', NULL),
(95, 2, 96, 1, 17, '2022-04-13 16:14:43', NULL),
(96, 2, 97, 1, 17, '2022-04-13 16:14:43', NULL),
(109, 2, 110, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(110, 2, 111, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(111, 2, 112, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(112, 2, 113, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(113, 2, 114, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(114, 2, 115, 1, 17, '2022-06-24 09:20:21', '2022-07-25 10:10:20'),
(115, 2, 116, 1, 17, '2022-06-24 09:24:50', '2022-07-25 10:10:20'),
(116, 2, 117, 1, 17, '2022-06-24 09:24:50', '2022-07-25 10:10:20'),
(117, 2, 118, 1, 17, '2022-06-24 09:24:50', '2022-07-25 10:10:20'),
(118, 2, 119, 1, 17, '2022-06-24 09:24:50', '2022-07-25 10:10:20'),
(119, 2, 120, 1, 17, '2022-06-24 09:24:51', '2022-07-25 10:10:20'),
(120, 2, 121, 1, 17, '2022-06-24 09:24:51', '2022-07-25 10:10:20'),
(121, 2, 122, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(122, 2, 123, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(123, 2, 124, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(124, 2, 125, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(125, 2, 126, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(126, 2, 127, 1, 17, '2022-06-24 13:22:46', '2022-07-25 10:10:20'),
(127, 2, 128, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(128, 2, 129, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(129, 2, 130, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(130, 2, 131, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(131, 2, 132, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(132, 2, 133, 1, 17, '2022-06-24 13:32:58', '2022-07-25 10:10:20'),
(133, 2, 134, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(134, 2, 135, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(135, 2, 136, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(136, 2, 137, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(137, 2, 138, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(138, 2, 139, 1, 17, '2022-07-08 14:58:59', '2022-07-25 10:10:20'),
(139, 2, 140, 1, 17, '2022-07-08 15:06:48', '2022-07-25 10:10:20'),
(140, 2, 141, 1, 17, '2022-07-08 15:06:48', '2022-07-25 10:10:20'),
(141, 2, 142, 1, 17, '2022-07-08 15:06:48', '2022-07-25 10:10:20'),
(142, 2, 143, 1, 17, '2022-07-08 15:06:48', '2022-07-25 10:10:20'),
(143, 2, 144, 1, 17, '2022-07-08 15:06:48', '2022-07-25 10:10:20'),
(144, 2, 145, 1, 17, '2022-07-08 15:06:49', '2022-07-25 10:10:20'),
(145, 2, 146, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20'),
(146, 2, 147, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20'),
(147, 2, 148, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20'),
(148, 2, 149, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20'),
(149, 2, 150, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20'),
(150, 2, 151, 1, 17, '2022-07-08 15:13:18', '2022-07-25 10:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL DEFAULT 'Sample section description',
  `position` int(10) NOT NULL,
  `icon` varchar(30) DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 0,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`id`, `name`, `description`, `position`, `icon`, `is_core`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'Utility', 'All functionality that helps the system run', 98, NULL, 1, 1, 52, '2022-04-29 12:43:46', NULL),
(2, 'System', 'All functionality that is mandatory for the system to run', 99, NULL, 1, 1, 52, '2022-04-29 12:43:46', NULL),
(3, 'Test', 'Sample', 99, NULL, 1, 2, 17, '2022-04-29 12:43:46', NULL),
(5, 'Church Levels', 'Sample', 1, NULL, 0, 1, 17, '2022-06-24 09:19:23', NULL),
(6, 'Church Accessories', 'Sample', 96, NULL, 0, 1, 17, '2022-06-24 13:32:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL,
  `color_id` int(10) UNSIGNED DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `name`, `color_id`, `is_core`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'Active', 4, 1, NULL, '2022-04-05 11:14:54', '2022-04-29 15:26:29'),
(2, 'Inactive', 1, 1, NULL, '2022-04-05 11:15:07', '2022-04-29 15:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL,
  `color_id` int(10) UNSIGNED DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`, `color_id`, `is_core`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 'Active', 4, 1, NULL, '2022-04-06 11:14:54', '2022-04-29 15:26:29'),
(3, 'Pending', 3, 1, NULL, '2022-04-06 11:15:16', '2022-04-29 15:26:33'),
(4, 'Inactive', 1, 1, NULL, '2022-04-06 11:15:28', '2022-04-29 15:26:33'),
(5, 'New', 5, 1, NULL, '2022-04-06 11:15:40', '2022-04-29 15:26:33');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstname` varchar(150) NOT NULL,
  `lastname` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `gravatar` varchar(190) DEFAULT NULL,
  `status_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_hash` varchar(64) DEFAULT NULL,
  `password_reset_expires_at` datetime DEFAULT NULL,
  `activation_hash` varchar(64) DEFAULT NULL,
  `remote_address` varchar(65) NOT NULL DEFAULT '127.0.0.1',
  `user_failed_logins` tinyint(1) NOT NULL,
  `user_last_failed_login` int(11) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `gravatar`, `status_id`, `state_id`, `is_core`, `password_hash`, `password_reset_hash`, `password_reset_expires_at`, `activation_hash`, `remote_address`, `user_failed_logins`, `user_last_failed_login`, `created_by`, `created_at`, `modified_at`) VALUES
(17, 'Jean', 'Kyle', 'john@example.com', NULL, 1, 1, 1, '$2y$10$3ih2VPgq8VhcskOnmqqFn.l/5tsjGD9w5p4y8JPEDZKSUeEZbzv8K', NULL, NULL, NULL, '127.0.0.1', 0, NULL, NULL, '2022-02-01 14:16:40', NULL),
(49, 'Adam', 'Example', 'adam@example.com', NULL, 1, 1, 1, '$2y$10$NV2H/XlyHaKIdWX.sNaBkODK1B4UJ9/PV3uRnHW/EqDnKdP0AsyBu', NULL, NULL, NULL, '127.0.0.1', 0, NULL, NULL, '2022-02-01 14:16:40', NULL),
(52, 'Ricardo', 'Miller', 'ricardo@example.com', NULL, 1, 1, 1, '2y$10$qgL2WTxXvkU4S6qFTRbKAenxBPHll3vV4r1SKrrK9peVE448o3fyG', NULL, NULL, NULL, '127.0.0.1', 0, NULL, NULL, '2022-02-01 14:16:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `state_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `state_id`, `created_by`, `created_at`, `modified_at`) VALUES
(1, 49, 2, 1, 17, '2022-04-12 15:17:52', NULL),
(2, 52, 2, 1, 17, '2022-04-12 15:17:52', NULL),
(3, 17, 2, 1, 17, '2022-04-12 15:17:52', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`),
  ADD KEY `color_ibfk_1` (`created_by`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_ibfk_1` (`section_id`),
  ADD KEY `menu_ibfk_2` (`state_id`),
  ADD KEY `menu_ibfk_3` (`created_by`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_ibfk_1` (`state_id`),
  ADD KEY `module_ibfk_2` (`created_by`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_ibfk_2` (`state_id`),
  ADD KEY `permission_ibfk_3` (`created_by`),
  ADD KEY `permission_ibfk_1` (`section_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_ibfk_1` (`state_id`),
  ADD KEY `role_ibfk_2` (`created_by`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_ibfk_1` (`role_id`),
  ADD KEY `role_permission_ibfk_3` (`state_id`),
  ADD KEY `role_permission_ibfk_4` (`created_by`),
  ADD KEY `role_permission_ibfk_2` (`permission_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_ibfk_1` (`state_id`),
  ADD KEY `section_ibfk_2` (`created_by`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_ibfk_1` (`color_id`),
  ADD KEY `state_ibfk_2` (`created_by`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_ibfk_1` (`color_id`),
  ADD KEY `status_ibfk_2` (`created_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ibfk_1` (`status_id`),
  ADD KEY `user_ibfk_2` (`state_id`),
  ADD KEY `user_ibfk_3` (`created_by`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role_ibfk_1` (`user_id`),
  ADD KEY `user_role_ibfk_2` (`role_id`),
  ADD KEY `user_role_ibfk_3` (`state_id`),
  ADD KEY `user_role_ibfk_4` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `union_type`
--
ALTER TABLE `union_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `color`
--
ALTER TABLE `color`
  ADD CONSTRAINT `color_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `section` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `module`
--
ALTER TABLE `module`
  ADD CONSTRAINT `module_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `module_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `permission_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `permission_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role`
--
ALTER TABLE `role`
  ADD CONSTRAINT `role_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_permission_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_ibfk_3` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_permission_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `section_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `state_ibfk_1` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `state_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `status_ibfk_1` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `status_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_role_ibfk_3` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_role_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
