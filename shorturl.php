<?php namespace mp; // vim: se fdm=marker:

class shorturl extends wx{

  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433600
   */
  function long2short(string $long_url):string{
    return rawurldecode($this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action'=>__FUNCTION__,'long_url'=>$long_url]))
      ->json())->short_url);
  }

}
