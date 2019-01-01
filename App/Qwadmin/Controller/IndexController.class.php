<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2016-09-20
* 版    本：1.0.0
* 功能说明：后台首页控制器。
*
**/

namespace Qwadmin\Controller;
use Qwadmin\Controller\ComController;
class IndexController extends ComController {
    public function index(){
		
		$uid = $this->USER['uid'];
        $this->assign('list',$list);	
        $this->assign('page',$page);

        $this->assign('mysql',$mysql[0]['mysql']);
        $this->assign('nav',array('','',''));//导航
		$this -> display();
    }
	
	//循环删除目录和文件函数
    public function delDirAndFile($dirName){
    	if ( $handle = opendir( "$dirName" ) ) {
    		while ( false !== ( $item = readdir( $handle ) ) ) {
    			if ( $item != "." && $item != ".." ) {
    				if ( is_dir( "$dirName/$item" ) ) {
    					delDirAndFile( "$dirName/$item" );
    				} else {
    					unlink( "$dirName/$item" );
    				}
    			}
    		}
    		closedir( $handle );
    		if( rmdir( $dirName ) ) return true;
    	}
    }
	
	//清除缓存
    public function clear_cache(){
    	$str = I('clear');	//防止搜索到第一个位置为0的情况
    	if($str){
			//strpos 参数必须加引号
    		//删除Runtime/Cache/admin目录下面的编译文件
    		if(strpos("'".$str."'", '1')){   			
    			$dir = APP_PATH.'Runtime/Cache/Qwadmin/';
    			$this->delDirAndFile($dir);
    		}
    		//删除Runtime/Cache/Home目录下面的编译文件
    		if(strpos("'".$str."'", '2')){    			
    			$dir = APP_PATH.'Runtime/Cache/Home/';
    			$this->delDirAndFile($dir);
    		}
    		//删除Runtime/Data/目录下面的编译文件
    		if(strpos("'".$str."'", '3')){
    			$dir = APP_PATH.'Runtime/Data/';
    			$this->delDirAndFile($dir);
    		}
    		//删除Runtime/Temp/目录下面的编译文件
    		if(strpos("'".$str."'", '4')){	
    			$dir = APP_PATH.'Runtime/Temp/';
    			$this->delDirAndFile($dir);
    		}
    		$this->ajaxReturn(1);	//成功
    	}else{
    		$this->display();
    	}
    }
}