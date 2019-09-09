<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10 0010
 * Time: 下午 7:20
 */

namespace app\api\controller\v1;
use app\api\model\Tuijian as TuijianModel;
use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\ThemeProduct;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TuijianException;
use think\Controller;
use think\Exception;

class Tuijian extends Controller
{
    /**
     * @desc 获取推荐商品
     * @param string $ids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws Exception
     * @throws TuijianException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getSimpleList($ids = '')
    {
        $validate = new IDCollection();
        $validate->goCheck();
        $ids = explode(',', $ids);
        $result = TuijianModel::with('topicImg,getProduct')->select($ids);
        if ($result->isEmpty()) {
            throw new TuijianException();
        }
        return json_encode($result);
    }
}