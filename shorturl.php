<?php namespace mp; // vim: se fdm=marker:

use http\request;

class shorturl{

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
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433600
   */
  function long2short(string $long_url):string{
    return rawurldecode($this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action'=>__FUNCTION__,'long_url'=>$long_url]))
      ->json())->short_url);
  }

}
