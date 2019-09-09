<?php


namespace app\admin\controller;


use think\Db;
use think\Request;

class Theme extends Common
{
    private $theme;
    private $img;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->theme = model('Theme');
        $this->img = model('Image');
    }

    /**
     * @desc 专题列表
     * @return mixed
     */
    public function theme()
    {
        $count = $this->theme->getTheme();
        $res = $this->theme->getThemeInfoToArray();
        foreach ($res as $k => $v){
            $res[$k]['topic_img'] = $this->theme->getThemeImg($v['topic_img_id'])['url'];
            $res[$k]['head_img'] = $this->theme->getThemeImg($v['head_img_id'])['url'];
        }
        $this->assign([
            'res'=>$res,
            'count'=>$count,
        ]);
        return $this->fetch();
    }

    /**
     * @desc 专题添加页面
     * @return mixed
     */
    public function add()
    {
        return $this->fetch();
    }

    /**
     * @desc 专题修改
     * @return mixed
     */
    public function theme_edit()
    {
        $param = input('param.');
        $theme = $this->theme->getThemInfo($param['id']);
        $topic_img = $this->theme->getThemeImg($theme['topic_img_id'])['url'];
        $head_img = $this->theme->getThemeImg($theme['head_img_id'])['url'];

        $this->assign([
            'theme'=>$theme,
            'topic_img'=>$topic_img,
            'head_img'=>$head_img
        ]);
        return $this->fetch();
    }

    /**
     * @desc 提交
     * @return \think\response\Json
     */
    public function theme_add_sure()
    {
        $param = input('param.');
        $name = $param['theme_name'];
        $str = $param['image_thumb_url'];
        $str = substr($str,0,strlen($str)-1);
        $arr = explode(",", $str);
        $img_utl = '/'.$arr[0];
        $img_utl_top = '/'.$arr[1];
        if ($img_utl && $img_utl_top){
            $data_t['url'] = $img_utl_top;
            $data['url'] = $img_utl;
            $data_t['from'] = 1;
            $data['from'] = 1;
        }
        Db::startTrans();
        try{
            $res = $this->img->insert($data);
            $res_t = $this->img->insert($data_t);
            $img_id = (int)$res;
            $img_t_id = (int)$res_t;
            $result = [
                'name' => $name,
                'topic_img_id' => $img_id,
                'head_img_id' => $img_t_id,
                'description' => $name,
            ];
            $res = $this->theme->insert($result);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        $data['code'] = true;
        $data['img_thumb_url'] = json_encode($arr);
        $data['mes'] = $param;
        return show(1,'success',$data,200);
    }

    /**
     * @desc 确认修改专题
     * @return false|string
     */
    public function theme_edit_sure()
    {
        $param = input('param.');
        $id = $param['theme_id'];
        $name = $param['theme_name'];
        $str = $param['img_thumb_url'];

        if(!$str==''){
            $str = substr($str,0,strlen($str)-1);
            $arr = explode(",", $str);
            $img_utl = '/'.$arr[0];
            $img_utl_top = '/'.$arr[1];
            if ($img_utl && $img_utl_top){
                $data_t['url'] = $img_utl_top;
                $data['url'] = $img_utl;
                $data_t['from'] = 1;
                $data['from'] = 1;
            }
        }else{
            $arr = [];
        }
        Db::startTrans();
        try{
            if(!$str==''){
                $res = $this->img->insert($data);
                $res_t = $this->img->insert($data_t);
                $img_id = (int)$res;
                $img_t_id = (int)$res_t;
                $result = [
                    'name' => $name,
                    'topic_img_id' => $img_id,
                    'head_img_id' => $img_t_id,
                    'description' => $name,
                ];
            }else{
                $result = [
                    'name'=>$name,
                    'description' => $name
                ];
            }
            $res = $this->theme->updated($id,$result);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        $data['code'] = true;
        $data['img_thumb_url'] = json_encode($arr);
        $data['mes'] = $param;
        return show(1,'success',$data,200);
    }

    /**
     * @desc 下架删除
     * @return \think\response\Json
     */
    public function theme_del()
    {
        $param = input('param.');
        $theme_id = $param['theme_id'];
        $data = [
            'type' => 2
        ];
        $res = $this->theme->updated($theme_id,$data);
        if ($res){
            $response = [
                'code' => true,
                'msg'  => 'success'
            ];
        }else{
            $response=[
                'code' => false,
                'msg'  => 'error'
            ];
        }
        return show(1,'success',$response,200);
    }

    /**
     * @desc 重新上架
     * @return \think\response\Json
     */
    public function theme_start()
    {
        $param = input('param.');
        $theme_id = $param['theme_id'];
        $data = ['type'=>1];
        $res = $this->theme->updated($theme_id,$data);
        if ($res){
            $response = [
                'code' => true,
                'msg'  => 'success'
            ];
        }else{
            $response=[
                'code' => false,
                'msg'  => 'error'
            ];
        }
        return show(1,'success',$response,200);
    }

    /**
     * @desc 专题下产品
     * @return mixed
     */
    public function theme_product_list()
    {
        return $this->fetch();
    }

    /**
     * @desc 专题产品添加
     * @return mixed
     */
    public function theme_product_add()
    {
        return $this->fetch();
    }

    /**
     * @desc 专题产品删除
     * @return mixed
     */
    public function theme_product_del()
    {
        return $this->fetch();
    }
}