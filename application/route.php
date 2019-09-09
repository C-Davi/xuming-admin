<?php
/**
 * 路由注册
 *
 * 以下代码为了尽量简单，没有使用路由分组
 * 实际上，使用路由分组可以简化定义
 * 并在一定程度上提高路由匹配的效率
 */

// 写完代码后对着路由表看，能否不看注释就知道这个接口的意义
use think\Route;

//Sample
Route::get('api/:version/sample/:key', 'api/:version.Sample/getSample');
Route::post('api/:version/sample/test3', 'api/:version.Sample/test3');

//Miss 404
//Miss 路由开启后，默认的普通模式也将无法访问
//Route::miss('api/v1.Miss/miss');

//Banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//Theme
// 如果要使用分组路由，建议使用闭包的方式，数组的方式不允许有同名的key
//Route::group('api/:version/theme',[
//    '' => ['api/:version.Theme/getThemes'],
//    ':t_id/product/:p_id' => ['api/:version.Theme/addThemeProduct'],
//    ':t_id/product/:p_id' => ['api/:version.Theme/addThemeProduct']
//]);

Route::group('api/:version/theme',function(){
    Route::get('', 'api/:version.Theme/getSimpleList');
    Route::get('/:id', 'api/:version.Theme/getComplexOne');
    Route::post(':t_id/product/:p_id', 'api/:version.Theme/addThemeProduct');
    Route::delete(':t_id/product/:p_id', 'api/:version.Theme/deleteThemeProduct');
});
//推荐商品
Route::get('api/:version/tuijian','api/:version.Tuijian/getSimpleList');
//Route::get('api/:version/theme', 'api/:version.Theme/getThemes');
//Route::post('api/:version/theme/:t_id/product/:p_id', 'api/:version.Theme/addThemeProduct');
//Route::delete('api/:version/theme/:t_id/product/:p_id', 'api/:version.Theme/deleteThemeProduct');

//Product
Route::post('api/:version/product', 'api/:version.Product/createOne');
Route::delete('api/:version/product/:id', 'api/:version.Product/deleteOne');
Route::get('api/:version/product/by_category/paginate', 'api/:version.Product/getByCategory');
Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id', 'api/:version.Product/getOne',[],['id'=>'\d+']);
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
Route::get('api/:version/product/by_distribution', 'api/:version.Product/getByDistribution');

//Category
Route::get('api/:version/category', 'api/:version.Category/getCategories');
// 正则匹配区别id和all，注意d后面的+号，没有+号将只能匹配个位数
//Route::get('api/:version/category/:id', 'api/:version.Category/getCategory',[], ['id'=>'\d+']);
//Route::get('api/:version/category/:id/products', 'api/:version.Category/getCategory',[], ['id'=>'\d+']);
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

//Token
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

//Address
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');
Route::get('api/:version/address', 'api/:version.Address/getUserAddress');

//Order
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail',[], ['id'=>'\d+']);
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');

//不想把所有查询都写在一起，所以增加by_user，很好的REST与RESTFul的区别
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');

//Pay
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify', 'api/:version.Pay/redirectNotify');
Route::post('api/:version/pay/concurrency', 'api/:version.Pay/notifyConcurrency');

//Message
Route::post('api/:version/message/delivery', 'api/:version.Message/sendDeliveryMsg');

//user
Route::get('api/:version/user/getBindTel', 'api/:version.User/getBindTel');
Route::post('api/:version/user/getPhone', 'api/:version.User/getPhone');
Route::post('api/:version/user/updatePhone', 'api/:version.User/updatePhone');
Route::post('api/:version/user/getQrCodeImg', 'api/:version.User/madeQrCodeImg');
//system
Route::get('api/:version/system/getSystem', 'api/:version.System/getOneSystem');
Route::post('api/:version/system/getBillImg', 'api/:version.System/getBillImg');

//return [
//        ':version/banner/[:location]' => 'api/:version.Banner/getBanner'
//];

//Route::miss(function () {
//    return [
//        'msg' => 'your required resource are not found',
//        'error_code' => 10001
//    ];
//});
//---------------------------------小程序后台----------------------------------//
//后台登录界面
Route::get('admin/index/login', 'admin/Login/login');
Route::post('admin/index/checklogin', 'admin/Login/check');
Route::get('admin/index/outlogin', 'admin/Login/logout');
Route::get('admin/index/index', 'admin/Index/index');
//banner
Route::get('admin/index/banner', 'admin/Banner/index');
Route::get('admin/index/banner_add', 'admin/Banner/add');
Route::post('admin/index/banner_add_store', 'admin/Banner/banner_add_store');
Route::post('admin/index/banner_add_sure', 'admin/Banner/banner_add_sure');
Route::get('admin/index/banner_edit', 'admin/Banner/edit');
//banner修改
Route::post('admin/index/banner_edit_sure', 'admin/Banner/banner_edit_sure');
//banner删除
Route::post('admin/index/banner_del', 'admin/Banner/banner_del');
//分类管理
Route::get('admin/index/category','admin/Category/category');
Route::get('admin/index/category_edit','admin/Category/category_edit');
Route::post('admin/index/category_edit_sure','admin/Category/category_edit_sure');
Route::get('admin/index/category_add','admin/Category/category_add');
Route::post('admin/index/category_add_sure','admin/Category/category_add_sure');
Route::post('admin/index/category_del','admin/Category/category_del');
Route::post('admin/index/category_start','admin/Category/category_start');
Route::post('admin/index/category_load','admin/Category/category_load');
//专题
Route::get('admin/index/theme','admin/Theme/theme');
Route::get('admin/index/theme_add','admin/Theme/add');
Route::get('admin/index/theme_edit','admin/Theme/theme_edit');
Route::post('admin/index/theme_add_sure','admin/Theme/theme_add_sure');
Route::post('admin/index/theme_edit_sure','admin/Theme/theme_edit_sure');
Route::post('admin/index/theme_del','admin/Theme/theme_del');
Route::post('admin/index/theme_start','admin/Theme/theme_start');
//产品
Route::get('admin/index/product','admin/Product/product');
Route::get('admin/index/product_add','admin/Product/product_add');
Route::post('admin/index/product_add_sure','admin/Product/product_add_sure');
Route::get('admin/index/product_edit','admin/Product/product_edit');
Route::post('admin/index/product_edit_sure','admin/Product/product_edit_sure');
Route::post('admin/index/product_del','admin/Product/product_del');
Route::get('admin/index/product_distribution','admin/Product/product_distribution');//分销产品
Route::post('admin/index/getConditionProduct','admin/Product/getConditionProduct');
//订单管理
Route::get('admin/index/order','admin/Order/order');
Route::get('admin/index/payOrder','admin/Order/pay_order');
Route::get('admin/index/noPayOrder','admin/Order/no_pay_order');
Route::get('admin/index/sendOrder','admin/Order/send_product_order');
Route::get('admin/index/noStockOrder','admin/Order/no_stock_order');
//图片上传
Route::post('admin/index/imgupload','admin/NewIndex/imageUpload');
//系统设置
Route::get('admin/system/index','admin/System/getSystem');
Route::post('admin/system/update','admin/System/updateSystem');


