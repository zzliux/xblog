### 设置篇
* 设置*uploads/images*和*uploads/images/thumb/*为777权限
* 设置好config目录下面的两个文件
* 将config下面的xblog.sql导入到数据库中,导入后删除这个文件
* 初始用户2333@qq.com 密码123  改密自行md5(pwd+salt)后放数据库
* 还可以在登录后访问/user/regin.php 进行注册,注册后请在数据库中删除老用户

### 提示
* 本博客运行在apache上,请自行打开rewrite模块,若使用其他服务器,/article路径请自行完成url重写