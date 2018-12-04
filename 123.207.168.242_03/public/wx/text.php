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
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        //第一次接入weixin api接口的时候
        echo  $echostr;
        exit;
    }
 else{
   //1.获取到微信推送过来post数据（xml格式）
        $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        //2.处理消息类型，并设置回复类型和内容
        $postObj = simplexml_load_string( $postArr );
        //判断该数据包是否是订阅的事件推送
        if( strtolower( $postObj->MsgType) == 'event'){
            //如果是关注 subscribe 事件
            if( strtolower($postObj->Event == 'subscribe') ){
                //回复用户消息(纯文本格式)
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号，此公众号为测试公众号！'.$postObj->FromUserName.'-'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
        }
   else   if(strtolower($postObj->MsgType) == 'text')
{ 
$content = $postObj->Content;
$toUser=$postObj->FromUserName; 
$fromUser=$postObj->ToUserName;
	//通过城市名找到citycode
     $url_citycode = 'http://www.redpanda233.xyz/api/weathers/getcitycode/name/'.$content;
     $html2 = json_decode(file_get_contents($url_citycode));
     $citycode = $html2->data[0];
     
     //通过citycode找到天气信息
     $url='http://t.weather.sojson.com/api/weather/city/'.$citycode;
	 //json_decode解析了json格式，组成字典，方便使用
     $html = json_decode(file_get_contents($url));
     
$time =time();
$msgType ='text';
     
     if($html->status==200) //200表示服务器正常处理了请求
		$content ='您查询的城市：'.$html->cityInfo->city.' 天气：'.$html->data->forecast[0]->type.' 湿度: '.$html->data->shidu.' pm25: '.$html->data->pm25.' 空气质量：'.$html->data->quality;
     else
       $content ='没有这个城市的编码';
     
$template ="<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[%s]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";

$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content); 
echo $info;
}
    }