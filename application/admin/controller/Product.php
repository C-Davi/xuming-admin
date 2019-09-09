<?php


namespace app\admin\controller;


use think\Db;
use think\Log;
use think\Request;

class Product extends Common
{
    private $theme;
    private $category;
    private $product;
    private $img;
    private $distribution;
    private $system;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->theme = model('Theme');
        $this->category = model('Category');
        $this->product = model('NewProduct');
        $this->img = model('Image');
        $this->distribution = model('Distribution');
        $this->system = model('Systems');
    }

    /**
     * @desc 产品列表
     * @return mixed
     */
    public function product()
    {
        $count = $this->product->count();
        $info=$this->product->getProductInfo();
        $this->assign('res',$info);
        $this->assign('count',$count);
        return $this->fetch();
    }

    /**
     * @desc 添加商品
     * @return mixed
     */
    public function product_add()
    {
        $param = input('param.');
        $theme = $this->theme->ProductandTheme();
        $category = $this->category->ProductandCategory();
        $this->assign('res1',$theme);
        $this->assign('res2',$category);
        return $this->fetch();
    }

    /**
     * @desc 添加商品逻辑
     */
    public function product_add_sure()
    {
        $param = input('param.');
        //保存照片表数据
        if ($param['img_thumb_url']){
            $img_url = '/'.$param['img_thumb_url'];
            $data = [
                'url' => $img_url,
                'from'=>1
            ];
        }
        Db::startTrans();
        try{
            if ($img_url){
                $img = $this->img->insert($data);
                $img_id = (int)$img;
            }
            $data1 = [
                'name' => $param['pname'],
                'price'=>$param['price'],
                'stock'=>$param['stock'],
                'category_id'=>$param['category']
            ];
            if ($img_id && $img_url){
                $data1['img_id'] = $img_id;
                $data1['main_img_url'] = $img_url;
            }
            //拼团
            if ($param['is_group']==1){
                $data1['is_groupon'] =$param['is_group'];
                $data1['groupon_end_time'] = $param['group_end_time'];
                $data1['groupon_price'] = $param['groupon_price'];
            }
            //分销
            if ($param['is_fenxiao']==1){
                $data1['is_distribution']=$param['is_fenxiao'];
                $scale = $this->system->getSystem(['id'=> 1])->getData('share_scale');
                $share_price = $scale *$param['price'];
                $data1['distribution_income'] = $share_price/100;
            }
            $res = $this->product->insert($data1);
            $product_id = (int)$res;
            $data2 = [
                'theme_id' => $param['theme'],
                'product_id' => $product_id
            ];

            if ($param['is_fenxiao'] ==1){
                $disData = [
                    'product_id' => $product_id
                ];
                $distributionCollection = $this->distribution->insertData($disData);
            }
            $res1 = $this->product->insertTheme($data2);
            Db::commit();
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            $data3['code'] = false;
            return json_encode($data3);
        }
        $data4['code'] = true;
        return json_encode($data4);

    }

    /**
     * @desc 产品修改
     * @return mixed
     */
    public function product_edit()
    {
        $param = input('param.');
        $id = $param['id'];
        $res = $this->product->getProduct($id);
        $theme = $this->theme->ProductandTheme();
        $category = $this->category->ProductandCategory();
        $this->assign('res1',$theme);
        $this->assign('res2',$category);
        $this->assign('res',$res);
        return $this->fetch();
    }

    /**
     * @desc 修改post 提交
     * @return false|string
     */
    public function product_edit_sure()
    {
        $param = input('param.');
        $id = $param['product_id'];
        $name = $param['pname'];
        $price = $param['price'];
        $stock = $param['stock'];
        $category_id = $param['category'];
        $theme = $param['theme'];
        $is_distribution=$param['is_fenxiao'];//分销
        if ($param['img_thumb_url']){
            $url = '/'.$param['img_thumb_url'];
        }
        $main_img_id = $param['main_img_id'];
        $data=[
            'name' => $name,
            'price'=> $price,
            'stock'=> $stock,
            'category_id'=> $category_id,
            'from'=>1,
            'img_id'=>$main_img_id
        ];
        Db::startTrans();

        try{
            $data=[
                'name' => $name,
                'price'=> $price,
                'stock'=> $stock,
                'category_id'=> $category_id,
                'from'=>1,
                'img_id'=>$main_img_id
            ];

            //拼团
            if ($param['is_group']==1){
                $data['is_groupon'] =$param['is_group'];
                $data['groupon_end_time'] = $param['group_end_time'];
                $data['groupon_price'] = $param['groupon_price'];
            }
            //分销
            if ($is_distribution==1){
                $data['is_distribution']=$param['is_fenxiao'];
                $selectCollection = $this->distribution->select($id);
                $scale = $this->system->getSystem(['id'=> 1])->getData('share_scale');
                $share_price = ($scale *$param['price'])/100;
                $data['distribution_income'] = $share_price;
                if (!$selectCollection){
                    $disData = [
                        'product_id' => $id
                    ];
                    $distributionCollection = $this->distribution->insertData($disData);
                }else{

                }
            }
            $res = $this->product->updateProduct($id,$data);
            $data1=[
                'theme_id'=>$theme
            ];
            $res1 = $this->product->updateProductAndTheme($id,$data1);
            Db::commit();
        }catch (\Exception $e){
            // 回滚事务
            Db::rollback();
            $data3['code'] = false;
            return json_encode($data3);
        }
        $data4['code'] = true;
        return json_encode($data4);
    }

    /**
     * @desc 产品下架
     * @return mixed
     */
    public function product_del()
    {
        $param = input('param.');
        $product_id = $param['product_id'];
        $data['from'] = 2;
        $res = $this->product->updateProduct($product_id,$data);
        if ($res){
            $data1['code'] = true;
            return json_encode($data1);
        }else{
            $data1['code'] = false;
            return json_encode($data1);
        }
    }


    public function product_distribution()
    {
        $info=$this->getConditionProduct();
//        var_dump($info);die();
        $count = count($info);
        $this->assign('res',$info);
        $this->assign('count',$count);
        return $this->fetch();
    }

    public function getConditionProduct()
    {
        $data =[
            'is_distribution' => 1
        ];
        $productCollection = $this->product->getConditionProduct($data);
        return $productCollection;
    }
}