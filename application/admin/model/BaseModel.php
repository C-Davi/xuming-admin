<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/5/20
 * Time: 15:15
 */

namespace app\admin\model;
use think\Model;

class BaseModel extends Model
{
    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }
}