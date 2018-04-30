<?php namespace mp;

use http\request;

class menu{

  private const HOST = 'https://api.weixin.qq.com';
  private $token,$host;

  final function __construct(string $token){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }

  function __toString():string{
    foreach(($obj=$this->check(request::url(self::HOST.'/cgi-bin/menu/get')->fetch(['access_token'=>$this->token])->json())->menu)->button as &$o)
      unset($o->sub_button->sub_button);
    return str_replace('  ',' ',json_encode($obj,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
  }

  function create(string $json):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
    return json_decode($json)&&$this->check(request::url(self::HOST.'/cgi-bin/menu/create')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json());
  }

  function delete():bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
    return !$this->check(request::url(self::HOST.'/cgi-bin/menu/delete')
      ->fetch(['access_token'=>$this->token])
      ->json())->errcode;
  }

  function addconditional(string $json):string{
    return $this->check(request::url(self::HOST.'/cgi-bin/menu/addconditional')
      ->query(['access_token'=>$this->token])
      ->POST($json)
      ->json())->menuid;
  }

  function delconditional(string $menuid):bool{
    return $menuid&&$this->check(request::url(self::HOST.'/cgi-bin/menu/delconditional')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['menuid'=>$menuid]))
      ->json());
  }

  function trymatch(string $id):string{
    foreach(($obj=$this->check(request::url(self::HOST.'/cgi-bin/menu/trymatch')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['user_id'=>$json]))
      ->json()))->button as &$o)
      unset($o->sub_button->sub_button);

    return str_replace('  ',' ',json_encode($obj,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
  }

  function get_current_selfmenu_info():string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1434698695
    foreach(($obj=$this->check(request::url(self::HOST.'/cgi-bin/get_current_selfmenu_info')->fetch(['access_token'=>$this->token])->json())->selfmenu_info)->button as &$o)
      if(isset($o->sub_button->list)){
        $o->sub_button = $o->sub_button->list;
        unset($o->sub_button->sub_button);
      }

    return str_replace('  ',' ',json_encode($obj,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
  }

}
