<?php namespace mp; // vim: se fdm=marker:

use http\request;

class quote extends wx{
  
  function clear(string $appid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433744592
    return $appid&&$this->check(request::url(self::HOST.'/cgi-bin/clear_quota')
      ->query(['access_token'=>$this->token->token])
      ->POST(json_encode(['appid'=>$appid]))
      ->body());
  }

}
