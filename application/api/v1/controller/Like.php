<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/6/13
 * Time: 16:17
 */

namespace app\v1\controller;


use think\Controller;
use think\Request;
use app\v1\model\User as UserModel;
use app\v1\model\Classic as ClassModel;
use app\v1\model\ClassicUserLike as CulModel;
class Like extends Controller
{
    private $defaultArr;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->defaultArr = [11,12,13];
    }

    public function like()
    {
        $params = input('param.');
        $id = $params['art_id'];
        $type = $params['type'];
        $openId = $params['openId'];
        $user= UserModel::getByOpenID($openId);
        $fav_nums = ClassModel::get($id)->fav_nums;
        if ($user){
            $userId = $user->id;
        }else{
            return show(0,'用户未注册呦','');
        }
        if (in_array($type,$this->defaultArr)){
            $classicCollection = ClassModel::update(['fav_nums'=>($fav_nums+1)],['id'=>$id]);
            $culCollection = CulModel::create(['classic_id'=>$id,'user_id'=>$userId,'is_liked'=>1]);
            if ($classicCollection && $culCollection){
                return show(1,'suceess','修改状态成功');
            }else{
                return show(0,'fail','修改状态失败');
            }
        }
    }

    public function cancel()
    {

    }
}