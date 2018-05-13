<?php namespace mp; // vim: se fdm=marker:

class shorturl{

  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433600
   */
  function __construct(token $token, string $long_url){
    $this->url = urldecode(request::url(token::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action'=>__FUNCTION__,'long_url'=>$long_url]))
      ->json()->short_url??'');
  }

  function __toString():string{
    return $this->url;
  }

}
