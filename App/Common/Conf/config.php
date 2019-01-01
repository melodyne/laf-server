<?php
/**
*
* 功能说明：配置文件。
*
**/
return array(
	'URL' =>'http://demo.010xr.com', //网站根URL
	//数据库链接配置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'xrswzl', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '010xr.com', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'qw_', // 数据库表前缀
	'DB_CHARSET'=>  'utf8',      // 数据库编码默认采用utf8
	//备份配置
	'DB_PATH_NAME'=> 'db',        //备份目录名称,主要是为了创建备份目录
	'DB_PATH'     => './db/',     //数据库备份路径必须以 / 结尾；
	'DB_PART'     => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
	'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
	'DB_LEVEL'    => '9',         //压缩级别   1:普通   4:一般   9:最高

	'TMPL_ACTION_SUCCESS'=>'Public:dispatch_jump',		//自定义success跳转页面
	'TMPL_ACTION_ERROR'=>'Public:dispatch_jump',		//自定义error跳转页面

	/*================开启页面调试功能===========*/
	'SHOW_PAGE_TRACE' =>false, // 显示页面调试Trace信息,项目上线后 true false

	//I方法参数过滤
	'DEFAULT_FILTER'=>'htmlspecialchars', // 默认参数过滤方法 用于I函
	'URL_MODEL' => '2',
	'URL_CASE_INSENSITIVE' =>true, //地址访问不区分大小写
	'LOG_RECORD' => true, // 开启日志记录
	//'APP_FILE_CASE' => false, // 是否检查文件的大小写 对Windows平台有效
	'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息

);
