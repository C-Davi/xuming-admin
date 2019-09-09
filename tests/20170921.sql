CREATE TABLE yueche_version (
id INT(11) unsigned auto_increment,
app_type VARCHAR(20) not null DEFAULT '' comment 'app类型 iOS android',
version INT(8) unsigned not null DEFAULT '0' comment '内部版本号',
version_code VARCHAR(20) not null DEFAULT '' comment '外部版本号1.2.1',
is_force tinyint(1) unsigned not null DEFAULT '0' comment '是否强制更新，0否，1是',
apk_url VARCHAR(255) not null DEFAULT '' comment 'apk最新地址',
upgrade_point VARCHAR(500) not null DEFAULT '' comment '升级提示',
status tinyint(1) not null DEFAULT '0' comment '状态',
created_time INT(11) unsigned not null DEFAULT '0',
update_time  INT(11) unsigned not null DEFAULT '0',
PRIMARY KEY (id)
)ENGINE=innodb DEFAULT charset=utf8 comment '版本信息表';