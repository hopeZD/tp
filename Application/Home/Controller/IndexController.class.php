<?php
namespace Home\Controller;

use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;


class IndexController extends Controller {

    public function index(){

    	$token = "hope";

    	$wechat = new Wechat($token);

    	$data = $wechat->request();

    	if($data && is_array($data)) {
    		switch($data['MsgType']) {
    			case "text" :
    			$this->Text($wechat, $data);
    		}
    	}
        
    }


   //回复文本消息
   private function Text($wechat, $data) {

   	if(strstr($data['Content'], "文本")) {
   		$text = "我正在使用ThinkPHP开发微信";
   		$this->logger("发送消息: \n".$text);
   		$wechat->replyText($text);

   	} else if (strstr($data['Content'], 'myself')) {
   		$this->users($wechat, $data);
   	}

   } 

   private function users($wechat, $data) {

   		$openid = $data['FromUserName'];
   		$appID = "wx8b1b083b085166b5";
   		$appSecret = "359161300729b69553b91aa2a0dcd5c6";
   		$token = session('token');

   		if($token) {

   			$wechatAuth = new WechatAuth($appID, $appSecret, $token);

   		} else {

   			$wechatAuth = new WechatAuth($appID, $appSecret);

	   		$accsseToken = $wechat->getAccessToken();
	   		$token = $accsseToken['access_token'];
	   		session('token', $token);

   		}

   		$user = $wechatAuth->userInfo($openid);
   		$text = "你的openid是：".$user['openid']."\n你的昵称是：".$user['nickname']."\n 你的姓别是：".$user['sex']."\n你所在城市是：".$user['city']."\n 你所在国家是： ".$user['country']."\n你所在的省份是：".$user['provice'];


   		$this->logger("发送用户的信息");
   		$wechat->replyText($text);

   		

   }

   //日志
   private function logger($content) {
   	
   	$logSize = 100000;
   	$log = "log.txt";

   	if(file_exists($log) && filesize($log) > $logSize) {
   		unlink($log);
   	}

   	file_put_contents($log, date("H:i:s")."".$content."\n", FILE_APPEND);
   }


}