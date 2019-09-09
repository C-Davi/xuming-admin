<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/5/29
 * Time: 17:02
 */

namespace app\v1\controller;

use think\Controller;
use app\v1\model\Movies as Mv;
use think\Request;

class Movies extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    public function getSome()
    {
        if ($this->request->isGet()){
            $arr = $this->outNums(5);
            $res = Mv::select($arr);
            return $res;
        }else{
            return show('2','fail','');
        }
    }

    /**
     *
     * Desc:产生随机数
     * Date: 2019/5/29
     * @param $num
     * @return array
     */
    public function outNums($num)
    {
        $min = 14;
        $max = 2917;
        $num = $num;
        $i = 1;$flag = 0;$arr = [];
        while ($i <= $num){
            $rundnum = rand($min, $max);
            if($flag != $rundnum){
                if(!in_array($rundnum,$arr)){
                    $arr[] = $rundnum;
                    $flag = $rundnum;
                }else{
                    $i--;
                }
                $i++;
            }
        }
        return $arr;
    }
}