CREATE TABLE yueche_user (
user_id INT(11) unsigned auto_increment,
user_mobile CHAR(11) not null DEFAULT '',
nick_name VARCHAR(255) not null DEFAULT '' comment '用户昵称',
sex SMALLINT(1) not null DEFAULT '0' comment '用户性别 0男 1 女',
dob VARCHAR(20) null comment '用户生日',
email VARCHAR(50) null comment '用户邮箱',
pass VARCHAR(255) not null DEFAULT '' comment '用户密码',
wx_id VARCHAR(255) null comment '微信id',
qq_id VARCHAR(255) null comment 'qq_id',
balance DECIMAL (5,2) not null DEFAULT '0.00' comment '余额',
home_address VARCHAR(255) null comment '家的位置',
work_address VARCHAR(255) null comment '公司位置',
inte INT(11) not null DEFAULT '0' comment '积分',
   icon VARCHAR(255) not null DEFAULT '' comment '用户头像',
register_time datetime null,
active_time timestamp not null DEFAULT CURRENT_TIMESTAMP comment '最后活跃时间',
unique_ident VARCHAR(50) not null comment '手机唯一标识',
login_id VARCHAR(255) not null DEFAULT '' comment '登录号',
PRIMARY KEY (user_id),
key user_mobile (user_mobile)
)engine=innodb DEFAULT charset=utf8 comment '用户信息表';

CREATE TABLE yueche_collectd(
collectd_id INT (11) unsigned auto_increment,
user_id INT(11) not null DEFAULT '0' comment '用户id',
collect_address VARCHAR(255) not null DEFAULT '' comment '收藏地点',
longitude DECIMAL(9,6) not null DEFAULT '0.000000' comment '地点经度',
latitude DECIMAL(8,6) not null DEFAULT '0.000000' comment '地点纬度',
weight SMALLINT(1) not null DEFAULT '0' comment '收藏权重，默认为0',
remark VARCHAR(255) not null DEFAULT '' comment '备注',
created_time datetime null,
updated_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (collectd_id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '收藏表';

CREATE TABLE yueche_recharge(
recharge_id INT(11) unsigned auto_increment,
recharge_num CHAR(16) not null DEFAULT '' comment '充值订单号',
recharge_money INT(6) not null DEFAULT '0' comment '充值金额',
recharge_status SMALLINT(1) not null DEFAULT '0' comment '充值状态，0未充值，1充值中，2充值成功，3取消充值',
user_id INT(11) not null DEFAULT '0' comment '用户id',
recharge_class SMALLINT(1) not null DEFAULT '0' comment '充值类型，0支付宝，1微信',
created_time datetime null,
updated_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (recharge_id),
KEY user_id (user_id),
KEY recharge_num (recharge_num)
)engine=innodb DEFAULT charset=utf8 comment '充值订单';

CREATE TABLE yueche_order(
order_id INT(11) unsigned auto_increment,
order_num CHAR(16) not null DEFAULT '' comment '订单号',
user_id INT(11) not null DEFAULT '0' comment '用户id',
class SMALLINT(1) not null DEFAULT '0' comment '三方平台，默认0滴滴 1首汽 2曹操 3神州',
is_public SMALLINT(1) not null DEFAULT '0' comment '车辆公司属性：0私车 1公车',
is_change SMALLINT(1) not null DEFAULT '0' comment '是否代叫车：0否 1是',
start_address VARCHAR(255) not null DEFAULT '' comment '出发地',
slongitude DECIMAL(9,6) not null DEFAULT '0.000000' comment '出发地点经度',
slatitude DECIMAL(8,6) not null DEFAULT '0.000000' comment '出发地点纬度',
end_address VARCHAR(255) not null DEFAULT '' comment '目的地',
elongitude DECIMAL(9,6) not null DEFAULT '0.000000' comment '目的地经度',
elatitude DECIMAL(8,6) not null DEFAULT '0.000000' comment '目的地纬度',
order_status SMALLINT(1) not null DEFAULT '0' comment '订单状态：0创建订单成功 1司机已接单 2上车 3完成 4取消订单',
is_now SMALLINT(1) not null DEFAULT '0' comment '是否实时打车：0实时，1预约打车',
use_time datetime null comment '预约乘车时间',
esc_num SMALLINT(1) not null DEFAULT '0' comment '取消代号',
esc_cause VARCHAR(255) not null DEFAULT '' comment '取消原因',
created_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
end_time datetime null,
PRIMARY KEY (order_id),
KEY user_id (user_id),
KEY order_num (order_num)
)engine=innodb DEFAULT charset=utf8 comment '消费订单';

CREATE TABLE yueche_discount(
coupon_id INT(11) unsigned auto_increment,
coupin_money INT(11) not null DEFAULT '0' comment '优惠卷金额',
start_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
end_time datetime null,
user_id INT(11) not null DEFAULT '0' comment '用户id',
PRIMARY KEY (coupon_id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '优惠卷表';

CREATE TABLE yueche_histroy(
h_id INT(11) unsigned auto_increment,
user_id INT(11) not null DEFAULT '0' comment '用户id',
user_name VARCHAR(255) not null DEFAULT '' comment '乘车人昵称',
mobile CHAR(11) not null DEFAULT '' comment '手机号',
PRIMARY KEY (h_id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '历史乘车人表';

CREATE TABLE yueche_bill(
bill_id INT(11) unsigned auto_increment,
user_id INT(11) not null DEFAULT '0' comment '用户id',
bill_money DECIMAL(6,2) not null DEFAULT '0.00' comment '发票金额',
rem_money DECIMAL(6,2) not null DEFAULT '0.00' comment '剩余金额',
head_name VARCHAR(255) not null DEFAULT '' comment '发票抬头',
bill_num CHAR(20) not null 	DEFAULT '' comment '税号',
uname VARCHAR(255) not null DEFAULT '' comment '收件人姓名',
location VARCHAR(255) not null DEFAULT '' comment '收件地址',
mobile CHAR(11) not null DEFAULT '' comment '手机号',
PRIMARY KEY (bill_id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '发票表';

CREATE TABLE yueche_recharge_scale(
id INT(11) unsigned auto_increment,
user_id INT(11) not null DEFAULT '0' comment '用户id',
recharge_money DECIMAL(6,2) not null DEFAULT '0.00' comment '充值现金',
ret_money DECIMAL(6,2) not null DEFAULT '0.00' comment '返现金额',
wallet_money DECIMAL(6,2) not null DEFAULT '0.00' comment '钱包金额',
is_open SMALLINT(1) not null DEFAULT '1' comment '是否开放 默认1开放  0未关闭',
update_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '充值比例表';

CREATE TABLE yueche_user_ret(
ret_id INT(11) unsigned auto_increment,
user_id INT(11) not null DEFAULT '0' comment '用户id',
order_num CHAR(16) not null DEFAULT '' comment '订单号',
mobile CHAR(11) not null DEFAULT '' comment '用户手机号',
ret_class_num SMALLINT(1) not null DEFAULT '5' comment '平台评价：0-5分',
ret_driver_num SMALLINT(1) not null DEFAULT '5' comment '司机评价 0-5分',
create_time timestamp not null DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (ret_id),
KEY user_id (user_id)
)engine=innodb DEFAULT charset=utf8 comment '用户评价表';


ALTER TABLE yueche_user add COLUMN Hlongitude DECIMAL(9,6) not null DEFAULT '0.000000' comment '家经度' after home_address;
ALTER TABLE yueche_user add COLUMN Hlatitude DECIMAL(8,6) not null DEFAULT '0.000000' comment '家纬度' after home_address;

ALTER TABLE yueche_user add COLUMN Wlongitude DECIMAL(9,6) not null DEFAULT '0.000000' comment '公司经度' after work_address;
ALTER TABLE yueche_user add COLUMN Wlatitude DECIMAL(8,6) not null DEFAULT '0.000000' comment '公司纬度' after work_address;