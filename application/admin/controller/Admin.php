<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

/**
 * @author Davi
 */
class Admin extends Controller
{
	private $obj;
	
	public function _initialize()
	{
		$this->obj = model('Admin');
	}


	/**
	 * @author Davi
	 * @desc    登录界面
	 * @param   [param]
	 * @return  [return]
	 */
	public function login()
	{
		return $this->fetch();
	}

	public function logincheck(Request $Req)
	{
		if(!request()->isPost()){
			$this->error('非法提交');
		}
		$data = input('post.');
		
		$user_mobile = $Req->param();
	}

}