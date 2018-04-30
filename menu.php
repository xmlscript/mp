<?php namespace mp;

use http\request;

class menu{

  private const HOST = 'https://api.weixin.qq.com';
  private $token,$host;

  final function __construct(string $token){
    $this->token = $token;
  }

  function __toString():string{
    return str_replace('  ',' ',json_encode(json_decode(request::url(self::HOST.'/cgi-bin/menu/get')
      ->fetch(['access_token'=>$this->token])),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
  }

  function create(string $json):void{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
    if(($json=request::url(self::HOST.'/cgi-bin/menu/create')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json())->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
  }

  function delete():void{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
    if(($json=request::url(self::HOST.'/cgi-bin/menu/delete')
      ->fetch(['access_token'=>$this->token])
      ->json())->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
  }

  function addconditional(string $json):stdClass{
    return request::url(self::HOST.'/cgi-bin/menu/addconditional')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json();
    //{"menuid":"208379533"}
    //{"errcode":40018,"errmsg":"invalid button name size"}
  }

  function delconditional(string $json):stdClass{
    return request::url(self::HOST.'/cgi-bin/menu/delconditional')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json();
    //{"menuid":"208379533"}
  }

  function trymatch(string $json):stdClass{
    return request::url(self::HOST.'/cgi-bin/menu/trymatch')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json();
  }

  function get_current_selfmenu_info():string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1434698695
    return str_replace('  ',' ',json_encode(json_decode(request::url(self::HOST.'/cgi-bin/get_current_selfmenu_info')
      ->fetch(['access_token'=>$this->token])),JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
  }

}
