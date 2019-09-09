<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/10 0010
 * Time: 下午 7:12
 */

namespace app\api\model;
use app\lib\exception\ProductException;
use app\lib\exception\ThemeException;
use think\Model;

class Tuijian extends BaseModel
{
    /**
     * 关联Image
     * 要注意belongsTo和hasOne的区别
     * 带外键的表一般定义belongsTo，另外一方定义hasOne
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public function getProduct()
    {
        return $this->belongsTo('Product', 'product_id', 'id');
    }
}