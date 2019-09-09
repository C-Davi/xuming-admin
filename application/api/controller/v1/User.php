<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\service\UserToken;
use app\api\model\User as UserModel;
use app\lib\wx\WXBizDataCrypt;
use app\admin\model\Distribution;
use think\Db;
use think\Log;
use think\Request;

class User extends BaseController
{
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxMaxQrCodeUrl;
    protected $QrcodePath;
    protected $distribution;
    protected $imgPrefix;
    public function __construct(Request $request = null)
    {
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxAccessTokenUrl = sprintf(
            config('wx.access_token_url'), $this->wxAppID, $this->wxAppSecret);
        $this->QrcodePath = ROOT_PATH.'public/images/qrcode/';
        $this->distribution = new Distribution();
        $this->imgPrefix = config('setting.img_prefix');
        parent::__construct($request);
    }

    /**
     * @desc 查看是否绑定手机号
     * @return false|string
     * @throws \app\lib\exception\ParameterException
     */
    public function getBindTel()
    {
        $uid = Token::getCurrentUid();
        $result = UserModel::getById($uid)->getData('phone');
        if (is_null($result)){
            $response = [
                'code' =>2,
                'msg' => 'fail',
                'status' => 'fail',
                'data' => array()
            ];
        }else{
            $response = [
                'code' =>1,
                'msg' => 'success',
                'status' => 'success',
                'data' => array('phone' => $result)
            ];
        }
        return json_encode($response);
    }

    public function madeQrCodeImg()
    {
        $params = input('post.');
        $path = $params['path'];//小程序路径
        $productId = $params['productId'];//商品id
        $phone = $params['phone'];//分销者手机号
        $scene = 'id='.$productId.'&phone'.$phone;
        $uid = Token::getCurrentUid();
        //判断当前用户对应的产品分销二维码是否存在
        $getQrcodeCollection = $this->distribution->selectData(['user_id'=>$uid,'product_id'=>$productId]);

        if (!$getQrcodeCollection){
            //缓存access——token-url
            session_start();
            $_SESSION['access_token']='';
            $_SESSION['expires_in']=0;
            if (!isset($_SESSION['access_token']) || (isset($_SESSION['expires_in'])) && time() > $_SESSION['expires_in']){
                $result = curl_get($this->wxAccessTokenUrl);
                // 注意json_decode的第一个参数true
                // 这将使字符串被转化为数组而非对象
                $wxResult = json_decode($result, true);
                $_SESSION['access_token'] = $wxResult['access_token'];
                $_SESSION['expires_in'] = time()+7200;
                $ACCESS_TOKEN = $wxResult['access_token'];
            }else{
                $ACCESS_TOKEN = $_SESSION['access_token'];
            }
            //判断目录是否存在
            $userPath = ROOT_PATH.'public/images/qr_code/'.$phone;
            if (is_dir($userPath)==false){
                mkdir($userPath, 0777);
            }

            //构建请求二维码
            $this->wxMaxQrCodeUrl = sprintf(
                config('wx.max_qr_code_url'), $ACCESS_TOKEN);
            $param = json_encode([
                'scene' => $scene,
                'is_hyaline'=>true
            ]);
            $getQrCodeResult = httpRequest($this->wxMaxQrCodeUrl,$param,"POST");
            $save_qrcode_img_url = '/qr_code/'.$phone.'/'.$productId.'_qrcode_wx.png';
            $file_name = ROOT_PATH.'public/images/qr_code/'.$phone.'/'.$productId.'_qrcode_wx.png';
            //生成小程序码存放
            file_put_contents($file_name,$getQrCodeResult);
            if (file_exists($file_name)){
                Db::startTrans();
                try{
                    $data = [
                        'product_id' => $productId,
                        'qr_code_img'=> $save_qrcode_img_url,
                        'user_id' => $uid
                    ];
                    $distributionCollection = $this->distribution->insertData($data);
                    Db::commit();
                    $response = [
                        'code' =>1,
                        'msg' => 'success',
                        'status' => 'success',
                        'data' => ['url' => $this->imgPrefix.$save_qrcode_img_url]
                    ];
                }catch (\Exception $e){
                    Db::rollback();
                    $this->writeLog($e);
                }

            }else{
                $response = [
                    'code' =>2,
                    'msg' => 'fail',
                    'status' => 'fail',
                    'data' => []
                ];
            }
        }else{
            $response = [
                'code' =>1,
                'msg' => 'success',
                'status' => 'success',
                'data' => ['url' => $this->imgPrefix.$getQrcodeCollection->getData('qr_code_img')]
            ];
        }


        return json_encode($response);
    }

    /**
     * @desc 获取微信授权手机号
     * @return false|string
     */
    public function getPhone()
    {
        $params = input('post.');
        $session_key = $params['session_key'];
        $encryptedData = $params['encryptedData'];
        $iv = $params['iv'];
        $data = '';
        $result = new WXBizDataCrypt($this->wxAppID,$session_key);
        $errCode = $result->decryptData($encryptedData,$iv,$data);
        if ($errCode ==0){
            $response = [
                'code' =>1,
                'msg' => 'success',
                'status' => 'success',
                'data' => json_decode($data)->phoneNumber
            ];
        }else{
            $response = [
                'code' =>2,
                'msg' => 'fail',
                'status' => 'fail',
                'data' => array()
            ];
        }
        return json_encode($response);
    }

    /**
     *
     * Desc: 更新微信绑定手机号
     * Date: 2019/6/20
     * @return false|string
     * @throws \app\lib\exception\ParameterException
     */
    public function updatePhone()
    {
        $params = input('post.');
        $phone = $params['phone'];
        $uid = Token::getCurrentUid();
        $data = ['phone' =>$phone];
        $result = UserModel::update($data,['id' => $uid]);
        if ($result){
            $response = [
                'code' =>1,
                'msg' => 'success',
                'status' => 'success',
                'data' => []
            ];
        }else{
            $response = [
                'code' =>2,
                'msg' => 'fail',
                'status' => 'fail',
                'data' => []
            ];
        }
        return json_encode($response);
    }

    /**
     * @desc 定义日志
     * @param $e
     */
    private function writeLog($e){
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record(json_encode($e->getMessage()),'error');
    }


}