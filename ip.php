<?php namespace mp; // vim: se fdm=marker:

use http\request;

class ip{

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
   * 获取官方ip
   */
  function getcallbackip():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140187
    return $this->check(request::url($this->host.'/cgi-bin/getcallbackip')
      ->fetch(['access_token'=>$this->token])
      ->json())->ip_list;
  }

}
