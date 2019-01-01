<?php
/**
*
* 功能说明：文章控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
use Vendor\Tree;

class SwlistController extends ComController {

	public function lists($p=1){

		$M = M('xcxsw_list');
		$p = intval($p)>0?$p:1;
		$pagesize = 15;
		$offset = $pagesize*($p-1);
		$prefix = C('DB_PREFIX');
		$where['display'] = 0;
		$count = $M->where($where)->count();
		$list = $M->where($where)->join("{$prefix}xcxsw_user ON {$prefix}xcxsw_list.openid = {$prefix}xcxsw_user.openid")->order("{$prefix}xcxsw_list.id DESC")->limit($offset.','.$pagesize)->select();
		$page	=	new \Think\Page($count,$pagesize);
		$page = $page->show();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this -> display();
	}

	public function del(){

		$where['id'] = I('id');
		$art = M('xcxsw_list')->where($where)->find();
		if($art['photo'] != ''){
			unlink($_SERVER['DOCUMENT_ROOT'].'/'.$art['photo']);
		}
		if(M('xcxsw_list')->where($where)->delete()){
			$w['vid'] = I('id');
			M('xcxsw_guanzhu')->where($w)->delete();
			$this->success('信息删除成功');
		}else{
			$this->error('参数错误');
		}
	}
	
	public function users(){
		$userList = M('xcxsw_user')->select();
		$this->assign('userList',$userList);
		$this -> display();
	}
	
}
