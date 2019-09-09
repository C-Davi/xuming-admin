<?php


namespace app\api\controller\v1;


use think\Controller;
use think\Request;
use app\admin\model\ShareDis;
class Distribution extends Controller
{
    private $ShareProductOrderModel;
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->shareProductModel = new ShareDis();
    }

    public function getShareProductList()
    {

    }
}