<?php
/**
 * Created by 七月.
 * Author: 七月
 * 微信公号：小楼昨夜又秋风
 * 知乎ID: 七月在夏天
 * Date: 2017/2/26
 * Time: 14:15
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use think\Controller;
use think\Log;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    
    public function getPreOrder($id='')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay= new PayService($id);
        return json_encode($pay->pay());
    }

    public function redirectNotify()
    {
        $notify = new WxNotify();
        return json_encode($notify->Handle());
    }

    public function notifyConcurrency()
    {
        $notify = new WxNotify();
        return json_encode($notify->Handle());
    }
    
    public function receiveNotify()
    {
//        $xmlData = file_get_contents('php://input');
//        Log::error($xmlData);

        $notify = new WxNotify();
        return json_encode($notify->Handle());
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http:/zerg.cn/api/v1/pay/re_notify?XDEBUG_SESSION_START=13133',
//            $xmlData);
//        return $result;
//        Log::error($xmlData);
    }
}