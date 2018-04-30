<?php namespace mp; // vim: se fdm=marker:

use http\request;

class ip{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }


  /**
   * 获取官方ip
   */
  function list():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140187
    $result = request::url($this->host.'/cgi-bin/getcallbackip')
      ->fetch(['access_token'=>$this->token])
      ->json();
    if(isset($result['errcode'])&&$result['errcode'])
      throw new \Exception($result['errmsg'],$result['errcode']);
    return $result['ip_list'];
  }

}
