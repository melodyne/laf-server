<?php
/**
*
* 版权所有：恰维网络<qwadmin.qiawei.com>
* 作    者：寒川<hanchuan@qiawei.com>
* 日    期：2015-09-17
* 版    本：1.0.0
* 功能说明：模块公共文件。
*
**/


function UpImage($callBack="image",$width=100,$height=100,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" width='.$width.' height="'.$height.'"  src="'.U('Upload/uploadpic').'?Width='.$width.'&Height='.$height.'&BackCall='.$callBack.'&Img='.$image.'"></iframe>
         <input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}
function BatchImage($callBack="image",$height=300,$image=""){
    echo '<iframe scrolling="no" frameborder="0" border="0" onload="this.height=this.contentWindow.document.body.scrollHeight;this.width=this.contentWindow.document.body.scrollWidth;" src="'.U('Upload/batchpic').'?BackCall='.$callBack.'&Img='.$image.'"></iframe>
		<input type="hidden" name="'.$callBack.'" id="'.$callBack.'">';
}


/*
 * 函数：网站配置获取函数
 * @param  string $k      可选，配置名称
 * @return array          用户数据
*/
function setting($k=''){
	if($k==''){
        $setting =M('setting')->field('k,v')->select();
		foreach($setting as $k=>$v){
			$config[$v['k']] = $v['v'];
		}
		return $config;
	}else{
		$model = M('setting');
		$result=$model->where("k='{$k}'")->find();
		return $result['v'];
	}
}

/**
 * 函数：格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
	return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 函数：加密
 * @param string            密码
 * @return string           加密后的密码
 */
function password($password){
	/*
	*后续整强有力的加密函数
	*/
	return md5('Q'.$password.'W');

}

/**
 * 随机字符
 * @param number $length 长度
 * @param string $type 类型
 * @param number $convert 转换大小写
 * @return string
 */
function random($length=6, $type='string', $convert=0){
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'string';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

// 微信相关
function curlGet($url,$method='get',$data=''){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $temp = curl_exec($ch);
    return $temp;
}
function access_token($appid,$secret){
	$result =array();
	$gateway = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
	$result = file_get_contents($gateway);
	$data = json_decode($result,true);
	$access_token = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$data['access_token'];
	return $access_token;
}
//  微信菜单相关
function menu_token($appid,$secret){
	$result =array();
	$gateway = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
	$result = file_get_contents($gateway);
	$data = json_decode($result,true);
	$menu_token = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$data['access_token'];
	return $menu_token;
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

function JSON($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);
	return urldecode($json);
}
// 过滤微信菜单内的HTML标签
function cut_tags_html($str){
$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                 "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                 "'([\r\n])[\s]+'",                 // 去掉空白字符
                 "'&(quot|#34);'i",                 // 替换 HTML 实体
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&#(\d+);'e");                    // 作为 PHP 代码运行

$replace = array ("",
                  "",
                  "",//"\\1",
                  "\"");
  return  preg_replace ($search, $replace, $str);

}

// GPS经纬度转换为百度坐标
//百度坐标转换成GPS坐标
//$lnglat = '121.437518,31.224665';
function FromBaiduToGpsXY($lnglat){
	// 经度,纬度
	$lnglat = explode(',',$lnglat);
	list($x,$y) = $lnglat;
	$Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x={$x}&y={$y}";
	$result = @file_get_contents($Baidu_Server);
	$json = json_decode($result);
	if($json->error == 0)
	{
		$bx = base64_decode($json->x);
		$by = base64_decode($json->y);
		$GPS_x = 2 * $x - $bx;
		$GPS_y = 2 * $y - $by;
		return $GPS_x.','.$GPS_y;//经度,纬度
	}
        else
          return  $lnglat;
}
// 微信小程序接口用的HTML转义JS函数
function jsformat($str){
  $str = trim($str);
  $str = str_replace('\\s\\s', '\\s', $str);
  $str = str_replace(chr(10), '', $str);
  $str = str_replace(chr(13), '', $str);
  $str = str_replace('  ', '', $str);
  $str = str_replace('\\', '\\\\', $str);
  $str = str_replace('"', '\\"', $str);
  $str = str_replace('\\\'', '\\\\\'', $str);
  $str = str_replace("'", "\'", $str);
  return $str;
}