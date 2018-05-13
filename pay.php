<?php namespace mp; // vim: se fdm=marker:

use http\request;

class pay{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141115
   * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_3
   * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=20_1
   */
  function chooseWXPay():array{
    return [
      'timeStamp' => $time=time(),
      'nonceStr' => $nonceStr='xxx',
      'package' => $package='prepay_id=xxxxxxx',
      'signType' => 'MD5',
      'paySign' => $this->signature($this->token->appid, $nonceStr, $package, $time)
    ];
  }


  private function signature(string $appid, string $nonceStr, string $package, int $timeStamp):string{
    $arr = array_filter([
      'appId' => $appid,
      'timeStamp' => time(),
      'nonceStr' => $nonceStr,
      'package' => $package,
      'signType' => 'MD5'
    ]);
    sort($arr,SORT_STRING);
    return strtoupper(md5(http_build_query($arr).$key));
  }

}
