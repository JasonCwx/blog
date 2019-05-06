#文章类别表
CREATE TABLE blog_category(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  name VARCHAR(50) NOT NULL DEFAULT '',
  create_time DATETIME NOT NULL DEFAULT now(),
  KEY name(`name`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#文章分类表
CREATE TABLE blog_tag(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  name VARCHAR(50) NOT NULL DEFAULT '',
  create_time DATETIME NOT NULL DEFAULT now(),
  KEY name(`name`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#文章与分类的公用表
CREATE TABLE blog_article_and_tag(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  article_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  tag_id INT(11) UNSIGNED NOT NULL DEFAULT 0,
  KEY article_id(`article_id`),
  KEY tag_id(`tag_id`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#文章表
CREATE TABLE blog_article(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  title VARCHAR(100) NOT NULL DEFAULT '' COMMENT '文章标题',
  content TEXT NOT NULL COMMENT '文章主体部分',
  excerpt VARCHAR(50) NOT NULL DEFAULT '' COMMENT '文章简要主体',
  click INT(11) NOT NULL DEFAULT 0 COMMENT '文章点击量',
  category_id INT(11) NOT NULL DEFAULT 0 COMMENT '文章关联的类别',
  create_time DATETIME NOT NULL DEFAULT now() COMMENT '文章创建的时间',
  update_time DATETIME NOT NULL DEFAULT now() COMMENT '文章更新的时间',
  KEY title(`title`),
  KEY category_id(`category_id`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#前台用户表
CREATE TABLE blog_user(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  name VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户使用的昵称',
  username VARCHAR(50) NOT NULL DEFAULT '' COMMENT '登录时使用的用户名,唯一不能重复使用',
  password CHAR(32) NOT NULL DEFAULT '' COMMENT '登录时使用的密码',
  email VARCHAR(255) NOT NULL DEFAULT '' COMMENT '邮箱，唯一不能重复使用',
  phone VARCHAR(20) NOT NULL DEFAULT '' COMMENT '手机号码，唯一不能重复使用',
  status TINYINT(1) NOT NULL DEFAULT 0 COMMENT '用户账号状态，0待激活，1激活，2禁止使用',
  code VARCHAR(6) NOT NULL DEFAULT '' COMMENT '用户账号激活验证码',
  create_time DATETIME NOT NULL DEFAULT now(),
  last_login DATETIME NOT NULL DEFAULT now(),
  UNIQUE KEY username(`username`),
  UNIQUE KEY email(`email`),
  UNIQUE KEY phone(`phone`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#后台用户表
CREATE TABLE blog_admin_user(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  name VARCHAR(50) NOT NULL DEFAULT '' COMMENT '用户使用的昵称',
  username VARCHAR(50) NOT NULL DEFAULT '' COMMENT '登录时使用的用户名,唯一不能重复',
  password CHAR(32) NOT NULL DEFAULT '' COMMENT '登录时使用的密码',
  email VARCHAR(255) NOT NULL DEFAULT '' COMMENT '邮箱，唯一不能重复',
  phone VARCHAR(20) NOT NULL DEFAULT '' COMMENT '管理员手机号码',
  is_super TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否为超级用户, 0否，1是',
  create_time DATETIME NOT NULL DEFAULT now(),
  last_login DATETIME NOT NULL DEFAULT now(),
  UNIQUE KEY username(`username`),
  UNIQUE KEY email(`email`),
  UNIQUE KEY phone(`phone`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE blog_comment(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
  user_id  INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论的用户id',
  content Text NOT NULL,
  parent_id INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复评论时需要用到父类id',
  article_id INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '将评论和文章绑定',
  create_time DATETIME NOT NULL DEFAULT now(),
  KEY article_id(`article_id`),
  KEY parent_id(`parent_id`)
)ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
