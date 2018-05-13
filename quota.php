<?php namespace mp; // vim: se fdm=marker:

use http\request;

class quota{
  
  function __construct(token $token){
    $this->token = $token;
  }
  
  function clear():bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433744592
    return !request::url(self::HOST.'/cgi-bin/clear_quota')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['appid'=>$this->token->appid]))
      ->json()->errcode;
  }

}
