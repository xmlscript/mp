<?php namespace mp; // vim: se fdm=marker:

use http\request;

class quote{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }
  
  
  function clear(string $appid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433744592
    return $appid&&$this->check(request::url(self::HOST.'/cgi-bin/clear_quota')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['appid'=>$appid]))
      ->body());
  }

}
