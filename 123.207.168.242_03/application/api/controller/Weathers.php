<?php
namespace app\api\controller;
header("Content-type: text/html; charset=utf-8"); 
use think\Controller;

class Weathers extends Controller
{
    public function read()
    {
        $id = input('id');
        $model = model('Weathers');
        $data = $model->getWeathers($id);
        #这里必须先转成数组，然后再以json格式返回，不然html格式会出错，导致使用这个接口获得的数据在
      	#json_decode（）时会变成null
      	$html = json_decode($data[0]);
      	return json($html);
    }
  	//得到城市id
  	public function getcitycode()
    {
        $id = input('name');
        $model = model('Weathers');
        $data = $model->getCityCode($id);
        if ($data) {
            $code = 200;
        } else {
            $code = 404;
        }
        $data = [
            'code' => $code,
            'data' => $data
        ];
      	//$url= 'http://www.redpanda233.xyz/api/weathers/read/id/101010100';
      	//$html = file_get_contents($url);
      	//$html1 = json_decode($html)
        //return $html;
      	return json($data);
      	//return $html1->cityInfo->city;
      
    }
  
  	//将最新的天气信息写入到数据库
  	public function update_info(){
       $url= 'http://www.redpanda233.xyz/api/weathers/read/id/101010100';
      	//$a = file_get_contents($url);
      	//$a = urldecode($a);
      	//$html = json_decode($a);
       	return dump($html);
        //return $html->cityInfo->city;
    }
  
}