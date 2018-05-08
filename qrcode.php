<?php namespace mp; // vim: se fdm=marker:

use http\request;

class qrcode extends wx{

  public $ticket, $expire_seconds, $url;

  function __construct(token $token, string $scene, int $expires=null){
    foreach($this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['expire_seconds'=>$expires,'action_name'=>$expires?'QR_STR_SCENE':'QR_LIMIT_STR_SCENE','action_info'=>['scene'=>['scene_str'=>$scene]]]))
      ->json()) as $k=>$v)
      $this->$k=$v;
  }


  function __toString():string{
    return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$this->ticket;
  }

}
