<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10 0010
 * Time: 下午 7:24
 */

namespace app\lib\exception;


class TuijianException extends BaseException
{
    public $code = 404;
    public $msg = '指定推荐商品不存在，请检查推荐ID';
    public $errorCode = 80005;
}