<?php namespace mp; // vim: se fdm=marker:

use http\request;

class ip extends wx{

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
