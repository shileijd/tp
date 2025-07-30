![](https://www.thinkphp.cn/uploads/images/20230630/300c856765af4d8ae758c503185f8739.png)

ThinkPHP 8 CMS管理系统
===============

## 系统介绍

本系统是基于ThinkPHP 8开发的内容管理系统（CMS），提供了完整的后台管理功能，包括用户管理、权限控制、内容发布等核心功能。

## 系统特性

* 基于PHP`8.0+`重构，性能更优
* 采用现代化的MVC架构设计
* 集成RBAC权限管理机制
* 支持多级菜单管理
* 提供丰富的表单组件
* 响应式后台界面设计
* 支持多语言切换
* 完善的日志记录功能

> 运行环境要求PHP8.0+

## 功能模块

* 用户管理：支持用户添加、编辑、删除、状态管理
* 角色权限：灵活的RBAC权限控制
* 菜单管理：可视化菜单配置
* 内容管理：文章、分类等内容管理
* 系统配置：灵活的参数配置功能
* 操作日志：完整的操作记录

## 安装部署

~~~
composer create-project topthink/think tp
~~~

导入数据库文件（位于/database目录下）

配置.env文件中的数据库连接信息

启动服务

~~~
cd tp
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000/admin
~~~

默认管理员账号：admin 密码：123456

## 技术支持

[官方文档](https://doc.thinkphp.cn)

## 版权信息

本系统基于ThinkPHP 8开发，遵循Apache2开源协议发布。

版权所有Copyright © 2019-2025 by YZSZcms (http://yzszcms.com) All rights reserved。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
