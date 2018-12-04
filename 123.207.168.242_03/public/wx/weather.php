<?php
//获得参数 signature nonce token timestamp echostr
    $nonce     = $_GET['nonce'];
    $token     = 'haha';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    //形成数组，然后按字典序排序
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    //拼接成字符串,sha1加密 ，然后与signature进行校验
    $str = sha1(implode($array));
    if ($str == $signature && $echostr) {
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }
    //1.获取到微信推送过来post数据（xml格式）
    $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
    //2.处理消息类型，并设置回复类型和内容
    /*<xml>
    <ToUserName><![CDATA[toUser]]></ToUserName>
    <FromUserName><![CDATA[FromUser]]></FromUserName>
    <CreateTime>123456789</CreateTime>
    <MsgType><![CDATA[event]]></MsgType>
    <Event><![CDATA[subscribe]]></Event>
    </xml>*/
    $postObj = simplexml_load_string($postArr);
    //$postObj->ToUserName = '';
    //$postObj->FromUserName = '';
    //$postObj->CreateTime = '';
    //$postObj->MsgType = '';
    //$postObj->Event = '';
    // gh_e79a177814ed
    //判断该数据包是否是订阅的事件推送
    if (strtolower($postObj->MsgType) == 'event') {
        //如果是关注 subscribe 事件
        if (strtolower($postObj->Event == 'subscribe')) {
            //回复用户消息(纯文本格式)
            $toUser   = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
            $time     = time();
            $msgType  =  'text';
            $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
            $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
            $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
            echo $info;
            /*<xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>12345678</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[你好]]></Content>
            </xml>*/
        }
    }

    // 获取用户发送的消息
    if (strtolower($postObj->MsgType) == 'text') {
        //回复用户消息(纯文本格式)
        $userText = $postObj->Content;
        $toUser   = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName;
        $time     = time();
        $msgType  =  'text';

        $textSendToUser = $userText; // 默认回复消息为用户发送的消息
        if ($userText == '天气' || $userText == '北京') {
            //$textSendToUser = $this->getToken($userText)
          	$textSendToUser = "晴，PM2.5 30 空气质量优，东南风，湿度30%，温度20~23摄氏度";
        }elseif ($userText == '网页' || $userText == '网页天气' ) {
            $textSendToUser = "http://140.143.225.159/weather.html";
        }else{
          	$textSendToUser = "无效信息，请输入:天气,北京天气,网页,网页天气";
        }

        $content  = $textSendToUser;
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
        $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
        echo $info;
        /*<xml>
        <ToUserName><![CDATA[toUser]]></ToUserName>
        <FromUserName><![CDATA[fromUser]]></FromUserName>
        <CreateTime>12345678</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[你好]]></Content>
        </xml>*/
    }

/*	function getToken($cityname){
          return $this->checkAccessToken($cityname);
      }

    function checkAccessToken($cityname){
          $url_get='http://123.207.168.242/api/weathers/getcitycode/name/'.$cityname;
          $json= $this->https_request($url_get);
          var_dump($json);
          $weather_info=$json['data'];
          if($weather_info){
            return $weather_info;
          }else{
            echo "城市名不正确";
        	return false;
          }
    }

	function https_request ($url){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $out = curl_exec($ch);
      curl_close($ch);
      return  json_decode($out,true);
    }

*/