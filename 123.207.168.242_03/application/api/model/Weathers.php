<?php 
namespace app\api\model;

use think\Model;
use think\Db;

class Weathers extends Model
{
    //通过城市code取得天气信息
  	public function getWeathers($id = 1)
    {
        $res = Db::name('ins_county')->where('weather_code', $id)->column('weather_info');
        return $res;
    }
	
  	//通过城市名称取得城市code
  	public function getCityCode($city = 1)
    {
        $res = Db::name('ins_county')->where('county_name', $city)->column('weather_code');
        return $res;
    }
  
    public function getWeathersList()
    {
        $res = Db::name('ins_county')->select();
        return $res;
    }
  	
  	//更新天气信息
  	public function update_info()
    {
        $res = Db::name('ins_county')->column('weather_code');
      	for ($i = 0; $i < 100;$i++){
     	 	//通过citycode找到天气信息
     		$url='http://t.weather.sojson.com/api/weather/city/'.$res[$i];
	 		//json_decode解析了json格式，组成字典，方便使用
     		$html = json_decode(file_get_contents($url));
   	 	  	$weather_info = '您查询的城市：'.$html->cityInfo->city.'更新时间：'.$html->cityInfo->updateTime.' 天气：'.$html->data->forecast[0]->type.' 高温：'.$html->data->forecast[0]->high.' 低温：'.$html->data->forecast[0]->low.' 湿度: '.$html->data->shidu;
          	Db::name('ins_county')->where('weather_code',$res[$i])->update(['weather_info'=>$weather_info]);

        }
      	return $res;
    }
  	
}