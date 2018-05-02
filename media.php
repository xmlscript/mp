<?php namespace mp; // vim: se fdm=marker:

use http\request;

class media{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  function uploadimg():string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/media/uploadimg')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//TODO 
      ->json())->url;
  }


  function uploadnews(array ...$news):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/media/uploadnews')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['articles'=>$news]))
      ->json())->url;
  }

}
