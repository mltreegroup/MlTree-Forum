MlTree Forum Tp6 Version
===============

> 运行环境要求PHP7.1+、打开popen。

## 主要新特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 全新的事件系统
* 统一和精简大量用法
* 采用标准RESTful接口响应标准HTTP动词
* 采用PHPmigrations数据库迁移
* 前后端分离响应速度更快
* 命令行一条命令安装

## 前端文件

[MlTreeForum-TP6-View](https://git.guupp.cn/MlTree.Inc/MlTreeForum-TP6-View)

## 安装

1. 首先将`TP6`分支代码拉到本地`git clone -b TP6 https://git.guupp.cn/MlTree.Inc/MlTree-Forum.git`
2. 使用compoer安装依赖库，执行`composer install`
3. 复制一份`example.env`文件重命名为`.env`。参照`example.env`文件将`.env`文件中的信息填写完整
4. 命令行切换至根目录下，执行`php think install [admin password] [--mail [admin email]]`安装数据库。默认密码：`admin`，邮箱：`admin@admin.com`
5. 执行`php think run`即可在本地搭建出一个基础开发/预览环境

> 命令执行成功后，将会自动创建数据库结构并写入基础信息。随后你可以访问`https://your_domain/admin`修改

## 文档

等待补充

## 参与开发

等待补充

## 更新日志

[UPDATE LOG](update.md)

## 版权信息

MlTree Forum遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2017-2019 by Mltree (https://blog.kigsr.cc)

All rights reserved。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
