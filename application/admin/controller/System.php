<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/5/20
 * Time: 14:48
 */

namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use think\Exception;
use think\Request;

class System extends Common
{
    private $obj;

    /**
     *
     * Desc: 初始化定义模型
     * Date: 2019/5/20
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->obj = model('Systems');
    }

    /**
     *
     * Desc: 获取system数据
     * Date: 2019/5/20
     */
    public function getSystem()
    {
        $data = ['id'=>1];
        $result = $this->obj->getSystem($data);

        if (!$result){
            $res = '';
        }else{
            $res = $result;
        }
        $this->assign('system',$res);
        return $this->fetch();
    }

    /**
     *
     * Desc:是否存在system设置
     * Date: 2019/5/20
     */
    public function hasSystem()
    {
        $data = ['id'=>1];
        $result = $this->obj->getSystem($data);
        if (!$result){
            return false;
        }else{
            return true;
        }
    }

    /**
     *
     * Desc:创建system数据
     * Date: 2019/5/20
     */
    public function createSystem()
    {
        $param = input('param.');
        $res = $this->obj->insertData($param);
        if (!$res){
            return false;
        }else{
            return true;
        }
    }

    /**
     *
     * Desc:更新system数据
     * Date: 2019/5/20
     */
    public function updateSystem()
    {
        $param = input('param.');
        $back_img = explode(",",$param['image_url']);
        $result = self::hasSystem();
        $data = [
            'web_name'=>$param['web_name'],
            'web_detail'=>$param['web_detail'],
            'bottom_copyright'=>$param['bottom_copyright'],
            'beian_num'=>$param['beian_num']
        ];
        if (is_array($back_img)){
            $quan_img = $back_img[0];
            $save_img = $back_img[1];
            if ($quan_img && $save_img){
                $data['quan_img'] = '/'.$quan_img;
                $data['save_img'] = '/'.$save_img;
            }
        }
        if (!$result){
            $res = self::createSystem($data);
        }else{
            $res = $this->obj->updateData(['id'=>1],$data);
        }
        if (!$res){
            return show(2,'error',[],200);
        }else{
            return show(1,'success',[],200);
        }
    }

    /**
     *
     * Desc:软删除system数据
     * Date: 2019/5/20
     */
    public function deleteSystem()
    {

    }
}