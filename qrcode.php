<?php namespace mp; // vim: se fdm=marker:

use http\request;

class qrcode{
  
  function __construct(token $token){
    $this->token = $token;
  }

  public $ticket, $expire_seconds, $url;

  function __construct(token $token, string $scene, int $expires=null){
    foreach(token::check(request::url(token::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['expire_seconds'=>$expires,'action_name'=>$expires?'QR_STR_SCENE':'QR_LIMIT_STR_SCENE','action_info'=>['scene'=>['scene_str'=>$scene]]]))
      ->json()) as $k=>$v)
      $this->$k=$v;
  }


  function __toString():string{
    return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$this->ticket;
  }

}
