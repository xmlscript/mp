<?php namespace mp; // vim: se fdm=marker:

use http\request;

class ip{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * 获取官方ip
   */
  function getcallbackip():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140187
    return token::check(request::url(token::HOST.'/cgi-bin/getcallbackip')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->ip_list;
  }

}
