<?php
/**
 * Created by Davi
 * Author: Davi
 
 * Time: 13:49
 */

return [
    //  +---------------------------------
    //  微信相关配置
    //  +---------------------------------

    // 小程序app_id 棒妈团
    'app_id' => 'wx7e50136677929a21',
    // 小程序app_secret
    'app_secret' => '89baefa0c4ed737c27bf81980d3eb0d4',

    // 微信使用code换取用户openid及session_key的url地址
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",

    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
        "grant_type=client_credential&appid=%s&secret=%s",
    //微信获取小程序码极多的地址
    'max_qr_code_url' => "https://api.weixin.qq.com/wxa/getwxacodeunlimit?".
        "access_token=%s",
];
