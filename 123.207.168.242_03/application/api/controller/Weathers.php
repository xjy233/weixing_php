<?php
namespace app\api\controller;

use think\Controller;

class Weathers extends Controller
{
    public function read()
    {
        $id = input('id');
        $model = model('Weathers');
        $data = $model->getWeathers($id);
        if ($data) {
            $code = 200;
        } else {
            $code = 404;
        }
        $data = [
            'code' => $code,
            'data' => $data
        ];
        return json($data);
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
        return json($data);
    }
  
  	//将最新的天气信息写入到数据库
  	public function update_info(){
        $model = model('Weathers');
        $data = $model->update_info();
        if ($data) {
            $code = 200;
        } else {
            $code = 404;
        }
        $data = [
            'code' => $code,
            'data' => $data
        ];
        return json($data);
    }
}