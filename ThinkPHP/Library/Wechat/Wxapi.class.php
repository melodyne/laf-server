<?php
/**
  * wechat php test
  */

namespace Wechat;

class Wxapi{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
	

    public function responseMsg(){
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$RX_TYPE = trim($postObj->MsgType);
                switch($RX_TYPE){
                    case "text":
                        $resultStr = $this->handleText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->handleEvent($postObj);
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
			}else {
				echo "";
				exit;
			}
    }
	
	public function handleText($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
				$textTpl2 = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[PHP编程基础培训班报名通道]]></Title>
							<Description><![CDATA[点击查看详情]]></Description>
							<PicUrl><![CDATA[http://55625.com/Public/Home/images/0.jpg]]></PicUrl>
							<Url><![CDATA[http://www.55625.com/Home/mobile/view.html?id=116]]></Url>
							</item>
							</Articles>
							</xml>";
        if(!empty( $keyword )){

            if($keyword=="报名"){
								$msgType = "news";
                $contentStr = $fromUsername;
								$resultStr = sprintf($textTpl2, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								echo $resultStr;
            }elseif($keyword=="苏州"){
							  $msgType = "text";
                $contentStr = "上有天堂，下有苏杭";
								$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								echo $resultStr;
            }else{
							  $msgType = "news";
                $contentStr = $fromUsername;
								$resultStr = sprintf($textTpl2, $fromUsername, $toUsername, $time, $msgType, $contentStr);
								echo $resultStr;
            }

        }else{
            echo "Input something...";
        }
    }

    public function handleEvent($object){
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "PHP编程基础培训班报名学习通道已开启（回复‘报名’两个字我告诉你）";
								$resultStr = $this->responseTex($object, $contentStr);
                break;
            default :
								$contentStr = $object->Event;
								$resultStr = $this->responsePic($object, $contentStr);
								//$contentStr = "听说9月份的PHP编程基础培训班报名开始啦（回复‘报名’两个字我告诉你）";
                break;
        }
				return $resultStr;
    }
		// 默认文字消息格式
    public function responseText($object, $content, $flag=0){
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
		// 关注的时候主动推送消息
		public function responseTex($object, $content, $flag=0){
			$msgType = "news";
			$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>1</ArticleCount>
						<Articles>
						<item>
						<Title><![CDATA[山西大学编程实训基地遭曝光]]></Title>
						<Description><![CDATA[点击查看详情]]></Description>
						<PicUrl><![CDATA[http://55625.com/Public/Home/images/9.jpg]]></PicUrl>
						<Url><![CDATA[http://mp.weixin.qq.com/s?__biz=MjM5NTQ3NzQ3Mw==&mid=2447570769&idx=1&sn=e5ca2766aad1aac6e706451610d90bce#rd]]></Url>
						</item>
						</Articles>
						</xml>";
			$resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $msgType, $content);
			return $resultStr;
		}
		// 9月份PHP图文信息
		public function responsePic($object, $content){
			  $msgType = "news";
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[PHP编程基础培训班报名通道]]></Title>
							<Description><![CDATA[点击查看详情]]></Description>
							<PicUrl><![CDATA[http://55625.com/Public/Home/images/0.jpg]]></PicUrl>
							<Url><![CDATA[http://www.55625.com/Home/mobile/view.html?id=116]]></Url>
							</item>
							</Articles>
							</xml>";
				$resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $msgType, $content);
				return $resultStr;
		}

	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

}

?>
