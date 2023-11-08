# cyBlog2

自己正在使用的自制单人博客系统，肯定会存在各种奇妙的未知 BUG。

现功能已经能够满足我个人使用，所以是否有更新就看我是否有新的需求。

该系统是给自己使用为主，所以一般情况下**不会回应新增功能的请求**，请谅解。

## 部署

推荐部署环境: Ubuntu 16.04 - 18.04

### 安装所需软件包
~~~
sudo apt-get install apache2 mysql php php-mbstring php-mysql php-curl
~~~

**参考环境的软件包版本**

> MySQL = 5.7.38-0ubuntu0.18.04.1
>
> PHP = 7.2.24-0ubuntu0.18.04.11
>
> Apache2 = 2.4.29-1ubuntu4.22

### 系统的释放和使用

将所有文件放置到 apache2 指定的目录, 方式自定。

配设 MySQL 数据库请查阅 settings/mysql.config

修改密码请查阅 settings/user.config

如果各种文件的权限配设没有错误，在浏览器输入对应地址即可开始使用

## 包含的第三方库/项目

- [jQuery.js](https://jquery.com/)
- [jquery.cookie.js](https://github.com/carhartl/jquery-cookie)
- [jquery.color.js](https://github.com/jquery/jquery-color)
- [jquery.easing.js](https://github.com/gdsmith/jquery.easing)
- [Popper.js](https://popper.js.org/)
- [highlight.js](https://highlightjs.org/)
- [Bootstarp 4](https://v4.bootcss.com/)
- [TinyMCE](https://www.tiny.cloud/tinymce/)