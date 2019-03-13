# 使用说明

## 开发环境 phpstudy php 5.6.27 + Apache + Mysql 5.5.3 (root:root)

## 开发框架 PHP CI 3.1.9

## 编辑器 phpstrom

## 数据库连接配置 修改配置文件
 cat application\config\database.php
 
 $db['default'] = array(
 	'dsn'	=> '',
 	'hostname' => 'localhost',
 	'username' => 'root',
 	'password' => 'root',
 	'database' => 'vue_admin',

##  phpstudy 配置站点域名管理, 同时修改hosts文件（可选）
    www.phpapi.com:8888

    接口调用使用示例：
    http://www.phpapi.com:8888/api/v1/user/testapi
    http://www.phpapi.com:8888/api/v1/user/login

    http://www.phpapi.com:8888/index.php/api/v1/user/testapi  带index.php 去掉修改.htaccess文件 可选
