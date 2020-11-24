/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 5.7.26 : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`test` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `test`;

/*Table structure for table `admin_login_log` */

DROP TABLE IF EXISTS `admin_login_log`;

CREATE TABLE `admin_login_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL COMMENT '账号id',
  `login_time` timestamp NULL DEFAULT NULL COMMENT '登陆时间',
  `login_ip` binary(16) DEFAULT NULL COMMENT '登陆ip',
  `user_agent` varchar(200) DEFAULT NULL COMMENT '登录ua',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='登陆日志表';

/*Data for the table `admin_login_log` */

insert  into `admin_login_log`(`id`,`admin_id`,`login_time`,`login_ip`,`user_agent`,`created_at`,`updated_at`) values 
(1,1,'2020-10-29 16:11:48','127.0.0.1\0\0\0\0\0\0\0','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36','2020-10-29 16:11:48','2020-10-29 16:11:48'),
(2,1,'2020-10-29 17:50:25','127.0.0.1\0\0\0\0\0\0\0','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36','2020-10-29 17:50:25','2020-10-29 17:50:25'),
(3,1,'2020-10-30 09:14:04','127.0.0.1\0\0\0\0\0\0\0','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36','2020-10-30 09:14:04','2020-10-30 09:14:04'),
(4,1,'2020-10-30 11:21:34','127.0.0.1\0\0\0\0\0\0\0','Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36','2020-10-30 11:21:34','2020-10-30 11:21:34');

/*Table structure for table `admin_role_permission` */

DROP TABLE IF EXISTS `admin_role_permission`;

CREATE TABLE `admin_role_permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL,
  `permission` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='账号角色关联表';

/*Data for the table `admin_role_permission` */

insert  into `admin_role_permission`(`id`,`admin_id`,`role_id`,`permission`,`created_at`,`updated_at`) values 
(18,1,5,NULL,NULL,NULL),
(15,2,4,NULL,NULL,NULL),
(17,1,3,NULL,NULL,NULL),
(16,1,1,NULL,NULL,NULL);

/*Table structure for table `admins` */

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `number` varchar(20) NOT NULL COMMENT '工号',
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `phone` char(11) NOT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `password` char(60) NOT NULL COMMENT '密码',
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('1','2') DEFAULT '1' COMMENT '是否启用1启用2禁用',
  `dep_id` int(10) DEFAULT NULL COMMENT '部门id',
  `parent_admin_id` int(10) DEFAULT NULL COMMENT '上级账号id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='账号表';

/*Data for the table `admins` */

insert  into `admins`(`id`,`number`,`name`,`nickname`,`phone`,`email`,`password`,`remember_token`,`status`,`dep_id`,`parent_admin_id`,`created_at`,`updated_at`) values 
(1,'10001','zhangsan1','zhangsan2','18812312312','123@qq.com','$2y$10$k0hH9wpo624NHzUYw8IOYu5AeTnbHEZ2sjIrF11G2FhkaWT26mGqW',NULL,'1',1,NULL,'2020-10-28 08:29:27','2020-10-30 16:30:15'),
(2,'100086','lisi222','lisi222','18812312313','234@qq.com','$2y$10$hFLXuJ/oH/EKfCP3qqraauSa1Q63V03.a8Zyuv0NW.bjrq/lf3FR6',NULL,'1',2,1,'2020-10-28 08:33:38','2020-10-30 15:43:54');

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(20) NOT NULL COMMENT '部门名称',
  `dep_des` varchar(200) DEFAULT NULL COMMENT '部门描述',
  `dep_num` int(10) NOT NULL DEFAULT '1' COMMENT '部门人数',
  `parent_dep_id` int(10) DEFAULT NULL COMMENT '上级id',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '状态1启用2停用',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='部门表';

/*Data for the table `department` */

insert  into `department`(`id`,`dep_name`,`dep_des`,`dep_num`,`parent_dep_id`,`status`,`created_at`,`updated_at`) values 
(1,'技术部','技术部',1,NULL,'1','2020-10-29 11:02:00','2020-10-30 11:12:22'),
(2,'运营部','运营部',1,NULL,'1','2020-10-29 11:04:25','2020-10-29 11:04:25'),
(3,'技术一组','技术一组',1,1,'1','2020-10-29 11:07:19','2020-10-29 11:07:19'),
(4,'产品部','产品部',1,NULL,'1','2020-10-29 11:07:32','2020-10-30 15:43:41');

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(30) NOT NULL,
  `permission_route` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='权限表';

/*Data for the table `permissions` */

insert  into `permissions`(`id`,`permission_name`,`permission_route`,`created_at`,`updated_at`) values 
(2,'账号管理','/admin/index','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(3,'角色管理','/role/index','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(4,'部门管理','/dep/index','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(6,'查询账号列表','/admin/lists','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(7,'查询账号信息','/admin/info','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(8,'保存账号信息','/admin/save','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(9,'角色列表','/role/lists','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(10,'查看角色信息','/role/info','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(11,'保存角色信息','/role/save','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(12,'保存角色权限','/role/savePermission','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(13,'部门列表','/dep/lists','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(14,'查看部门信息','/dep/info','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(15,'保存部门信息','/dep/save','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(16,'修改账号状态','/admin/saveStatus','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(17,'修改角色状态','/role/saveStatus','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(18,'修改部门状态','/dep/saveStatus','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(19,'权限管理','/permission/index','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(20,'权限列表','/permission/lists','2020-10-27 10:21:27','2020-10-27 10:21:27'),
(21,'权限保存','/permission/save','2020-10-30 16:51:49','2020-10-30 16:51:49'),
(22,'角色分配权限','/permission/rolePermission','2020-10-30 16:56:11','2020-10-30 16:56:11');

/*Table structure for table `role_permission` */

DROP TABLE IF EXISTS `role_permission`;

CREATE TABLE `role_permission` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL COMMENT '角色id',
  `permission_id` int(10) NOT NULL COMMENT '权限id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8 COMMENT='角色权限关联表';

/*Data for the table `role_permission` */

insert  into `role_permission`(`id`,`role_id`,`permission_id`,`created_at`,`updated_at`) values 
(16,2,1,NULL,NULL),
(17,2,2,NULL,NULL),
(18,2,3,NULL,NULL),
(19,2,4,NULL,NULL),
(100,3,14,NULL,NULL),
(101,3,15,NULL,NULL),
(102,1,1,NULL,NULL),
(103,1,2,NULL,NULL),
(104,1,3,NULL,NULL),
(105,1,4,NULL,NULL),
(106,1,6,NULL,NULL),
(107,1,7,NULL,NULL),
(108,1,8,NULL,NULL),
(109,1,9,NULL,NULL),
(110,1,10,NULL,NULL),
(111,1,11,NULL,NULL),
(112,1,12,NULL,NULL),
(113,1,13,NULL,NULL),
(114,1,14,NULL,NULL),
(115,1,15,NULL,NULL),
(116,1,16,NULL,NULL),
(117,1,17,NULL,NULL),
(128,5,3,NULL,NULL),
(129,5,9,NULL,NULL),
(130,5,19,NULL,NULL),
(131,5,20,NULL,NULL),
(132,5,21,NULL,NULL),
(133,1,22,NULL,NULL);

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` char(20) NOT NULL COMMENT '角色名称',
  `role_des` varchar(50) DEFAULT NULL COMMENT '角色描述',
  `parent_role_id` int(10) DEFAULT NULL COMMENT '角色父级id',
  `status` enum('1','2') NOT NULL DEFAULT '1' COMMENT '角色状态1启用2停用',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色表';

/*Data for the table `roles` */

insert  into `roles`(`id`,`role_name`,`role_des`,`parent_role_id`,`status`,`created_at`,`updated_at`) values 
(1,'管理员','超级管理员',1,'1','2020-10-27 10:21:27','2020-10-30 11:11:55'),
(2,'会员管理','会员管理',1,'1','2020-10-27 10:22:20','2020-10-28 18:09:56'),
(3,'客服','客服',2,'1','2020-10-27 10:22:20','2020-10-30 14:37:45'),
(4,'售后','售后',1,'2','2020-10-28 18:10:18','2020-10-30 16:57:58'),
(5,'会员',NULL,NULL,'1','2020-10-30 15:07:42','2020-10-30 15:07:42');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
