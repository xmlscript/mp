<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

final class token{

  private const HOST = 'https://api.weixin.qq.com';
  public $appid,$token;
  private static $expires_in=7200;
  
  function __construct(string $appid, string $secret){
    [$this->appid,$this->token] = [$appid,new cache($appid.__CLASS__, $secret, self::$expires_in, function() use ($appid, $secret){
      $result = request::url(self::HOST.'/cgi-bin/token')
        ->fetch(['grant_type'=>'client_credential','appid'=>$appid,'secret'=>$secret])
        ->json();
      if(isset($result->access_token,$result->expires_in)&&self::$expires_in=$result->expires_in)
        return $result->access_token;
      else
        error_log($result->errmsg);
    })];
  }

  function __toString():string{
    return $this->token;
  }

}
