<?php namespace mp; // vim: se fdm=marker:

use http\request;

class qrcode{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  /**
   * 创建临时二维码
   * @param int $scene_id
   * @param int $expire_seconds 默认30秒过期，最大允许2592000秒（30天）
   */
  function QR_SCENE(int $scene_id, int $expire_seconds=30):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['expire_seconds'=>$expire_seconds,'action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_id'=>$scene_id]]]))
      ->json());
  }


  /**
   * 创建临时二维码
   * @param string $scene_str
   * @param int $expire_seconds 默认30秒过期，最大允许2592000秒（30天）
   */
  function QR_STR_SCENE(string $scene_str, int $expire_seconds=30):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['expire_seconds'=>$expire_seconds,'action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_str'=>$scene_str]]]))
      ->json());
  }


  /**
   * 创建永久二维码
   * @param int $scene
   */
  function QR_LIMIT_SCENE(int $scene_id):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_id'=>$scene_id]]]))
      ->json());
  }


  /**
   * 创建永久二维码
   * @param string $scene_str
   */
  function QR_LIMIT_STR_SCENE(string $scene_str):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_str'=>$scene_str]]]))
      ->json());
  }


  /**
   * 如果直接访问这个API，将会得到一张jpg图片，如果出错将返回404
   */
  function showqrcode(string $ticket):string{
    return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
  }

}
