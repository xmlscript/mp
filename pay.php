<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

class pay{

  private const HOST = 'https://api.weixin.qq.com';
  private $token;
  
  final function __construct(token $token){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141115
   * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_3
   * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=20_1
   */
  function chooseWXPay():array{
    $arr = array_filter([
      'appId' => $this->token->appid,
      'timeStamp' => time(),
      'nonceStr' => 'xxx',
      'package' => 'prepay_id=xxxxxxx',
      'signType' => 'MD5'
    ]);
    sort($arr,SORT_STRING);
    return [
      'timeStamp' => $time,
      'nonceStr' => $nonceStr,
      'package' => $package,
      'signType' => 'MD5',
      'paySign' => strtoupper(md5(http_build_query($arr).$key));
    ];
  }

}
