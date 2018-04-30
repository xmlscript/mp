<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

final class token{

  private const HOST = 'https://api.weixin.qq.com';
  private $appid,$secret;
  private static $expires_in=7200;
  
  function __construct(string $appid, string $secret){
    $this->appid = $appid;
    $this->secret = $secret;
  }

  function __toString():string{
    return new cache($this->appid.__CLASS__, $this->secret, self::$expires_in, function(){
      $result = request::url(self::HOST.'/cgi-bin/token')
        ->fetch(['grant_type'=>'client_credential','appid'=>$this->appid,'secret'=>$this->secret])
        ->json();
      if(isset($result->access_token)){
        self::$expires_in = $result->expires_in;
        return $result->access_token;
      }else
        error_log($result->errmsg);
    });
  }

}
