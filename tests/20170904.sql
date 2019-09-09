CREATE TABLE yueche_admin(
user_id INT (11) unsigned auto_increment,
mobile CHAR (11) not null DEFAULT '' comment '管理员手机号',
status SMALLINT (1) not null DEFAULT '0' comment '管理员状态',
nickname VARCHAR (255) not null DEFAULT '' comment '管理员昵称',
created_at datetime null,
update_at timestamp not null DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (user_id),
KEY mobile (mobile)
)engine=innodb DEFAULT charset=utf8 comment '管理员信息表';


CREATE TABLE yueche_smscode(
id INT (11) unsigned auto_increment,
mobile CHAR (11) not null DEFAULT '' comment '用户手机号',
code INT (4) not null comment '验证码',
create_at datetime not null,
update_at datetime not null,
PRIMARY KEY (id),
KEY mobile (mobile)  
)engine=innodb DEFAULT charset=utf8 comment '短信验证码';