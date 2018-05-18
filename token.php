<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

final class token{

  const HOST = 'https://api.weixin.qq.com';
  
  function __construct(string $appid, string $secret){

    $cache = new cache($appid.__CLASS__, $secret, 7200);

    $this->access_token = $cache->valid()?
      $cache:
      $cache(token::check(request::url(token::HOST.'/cgi-bin/token')
        ->fetch(['grant_type'=>'client_credential','appid'=>$appid,'secret'=>$secret])
        ->json())->access_token);

    $this->appid = $appid;
    //$this->secret = $secret; //TODO 生成一个伪key
  }

  function __toString():string{
    return $this->access_token;
  }

  static function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }

  function article():article{
    return new article($this);
  }

  function tpl():tpl{
    return new tpl($this);
  }

  function ip():ip{
    return new ip($this);
  }

  function media():media{
    return new media($this);
  }

}
