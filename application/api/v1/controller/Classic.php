<?php
/**
 * Created by 24th_srm.
 * User: By Cbw
 * Email: [17854288794@163.com]
 * Date: 2019/3/13
 * Time: 14:19
 */

namespace app\v1\controller;

use think\Controller;

use think\Request;
use app\v1\model\Classic as Csc;
use app\v1\model\ClassicUserLike as CulModel;
class Classic extends Controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->img_url_prefix = 'https://www.qtc369.com/static/xiaochengxu';
    }

    public function getLast()
    {
        $nowClassis = Csc::where('status',1)
            ->order('pubdate','desc')
            ->find();
        $nowClassis->like_status = CulModel::get(['classic_id'=>$nowClassis->id])->is_liked;
        $nowClassis->image = $this->img_url_prefix .$nowClassis->image;
        return $nowClassis;
    }

    public function getPreviousOrNext($index,$page)
    {
        $sum = Csc::count();
        $min_index = 1;
        $max_index = $sum;
        if ($page == 'next'){
            $next_index = $index + 1;
            if ($next_index > $max_index){
                $next_index = $min_index;
            }
        }else{
            $next_index = $index -1;
            if ($next_index < $min_index){
                $next_index = $max_index;
            }
        }
        $newClassis = Csc::where(['index'=> $next_index,'status' =>1])->find();
        $newClassis->image = $this->img_url_prefix .$newClassis->image;
        return $newClassis;
    }
}