<?php
namespace app\common\lib;

/**
 * @author Davi
 * @desc 创建订单号
 */
class Orderno{

    /**
     * 创建订单号
     */
    public static function createorderno($uid)
    {
        return date('Ymd').$uid .substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}