<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
* @author Davi
* @desc 封装通用API接口数据输出
* @param int $status 业务状态码
* @param string $message 信息提示
* @param [] $data 数据
* @param int $httpCode http状态码
 */
function show($status,$message,$data,$httpCode=200)
{
    $result = [
        'status'  => $status,
        'message' => $message,
        'data'    => $data,
    ];

    return json($result, $httpCode);
}