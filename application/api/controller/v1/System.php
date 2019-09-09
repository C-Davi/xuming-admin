<?php


namespace app\api\controller\v1;
use app\api\service\Token;
use think\Controller;
use app\admin\model\Systems as SystemModel;
use app\admin\model\Bill;
use app\admin\model\Distribution;
use app\api\model\Product as ProductModel;
use think\Request;

class System extends Controller
{
    private $obj;
    private $bill;
    protected $distribution;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->obj = new SystemModel();
        $this->bill = new Bill();
        $this->distribution = new Distribution();
    }

    public function getSystem()
    {
        $data = ['id'=>1];
        $result = $this->obj->getSystem($data);
        if ($result){
            return show(1,'success',$result,200);
        }else{
            return show(2,'fail','[]',200);
        }
    }

    public function getOneSystem()
    {
        $result = SystemModel::get(1);
        $result['quan_img'] = config('setting.img_prefix').$result['quan_img'];
        $result['save_img'] = config('setting.img_prefix').$result['save_img'];
        if ($result){
            return show(1,'success',$result,200);
        }else{
            return show(2,'fail','[]',200);
        }
    }

    public function getOneSystemToArray()
    {
        $result = SystemModel::get(1);
        $result['quan_img'] = config('setting.img_prefix').$result['quan_img'];
        $result['save_img'] = config('setting.img_prefix').$result['save_img'];
        return $result;
    }

    public function getBillImg()
    {
        $params = input('post.');
        $phone = $params['phone'];
        $qr_code = $params['qrCode'];
        $product_id = $params['productId'];
        $product_info = ProductModel::getProductDetail($product_id)->getData();
        $product_name = $product_info['name'];
        $product_price = $product_info['price'];
        $product_img = config('setting.img_prefix').$product_info['main_img_url'];
        $uid = Token::getCurrentUid();
        $getShareCollection = $this->distribution->selectData(['user_id'=>$uid,'product_id'=>$product_id]);
        $getShareImg = $getShareCollection->getData('share_img');
        $getSaveImg = $getShareCollection->getData('save_img');
        if (!$getShareImg || !$getSaveImg){
            //判断目录是否存在
            $userPath = ROOT_PATH.'public/images/qr_code/'.$phone;
            if (is_dir($userPath)==false){
                mkdir($userPath, 0777);
            }
            $data = [
                'title' =>$product_name,
                'price_market' =>$product_price,
                'price_member' => '',
                'goods_img' => $product_img,
                'qr_code'   => $qr_code
            ];
            $save = $data;
            $back_quan_img = $this->getOneSystemToArray()->getData('quan_img');
            $back_save_img = $this->getOneSystemToArray()->getData('save_img');
            //share
            $data['backImg'] = $back_quan_img;
            $data['type'] =1;
            $data['new_img'] = $userPath.'/quan_'.$product_id.'.jpg';
            $data['share_img'] = '/qr_code/'.$phone.'/quan_'.$product_id.'.jpg';
            if (!$getShareImg){
                $quanImg = $this->bill->getBillImage($data);
            }else{
                $quanImg = false;
            }

            //save
            $save['backImg'] = $back_save_img;
            $save['type'] =2;
            $save['new_img'] = $userPath.'/save_'.$product_id.'.jpg';
            $save['save_img'] = '/qr_code/'.$phone.'/save_'.$product_id.'.jpg';
            if (!$getSaveImg){
                $saveImg = $this->bill->getBillImage($save);
            }

            if ($quanImg || $saveImg){
                if ($quanImg){
                    $result_share = $this->distribution->updateData(['product_id'=>$product_id,'user_id'=>$uid],['share_img'=>$data['share_img']]);
                    $share_img = config('setting.img_prefix').$data['share_img'];
                }else{
                    $result_share = true;
                    $share_img = config('setting.img_prefix').$getShareImg;
                }
                if ($saveImg){
                    $result_save = $this->distribution->updateData(['product_id'=>$product_id,'user_id'=>$uid],['save_img'=>$save['save_img']]);
                    $save_img = config('setting.img_prefix').$save['save_img'];
                }else{
                    $result_save=true;
                    $save_img = config('setting.img_prefix').$getSaveImg;
                }


                if ($result_share && $result_save){

                    $data = [
                        'share_img' => $share_img,
                        'save_img' => $save_img,
                    ];
                    return show(1,'success',$data,200);
                }
                return show(2,'fail',[],200);
            }else{
                $data = [];
                return show(2,'fail',$data,200);
            }
        }else{
            return show(1,'success',['share_img'=>config('setting.img_prefix').$getShareImg,'save_img'=>config('setting.img_prefix').$getSaveImg],200);
        }



    }

}