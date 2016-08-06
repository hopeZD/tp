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

   	}

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