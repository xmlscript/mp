<?php namespace mp;

use http\request;

class menu{

  private const HOST = 'https://api.weixin.qq.com';
  private $token,$host;

  final function __construct(string $token){
    $this->token = $token;
  }

  function create(string $json){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
    return request::get(self::HOST.'/cgi-bin/menu/create')
      ->query(['access_token'=>$this->token])
      ->body($json)
      ->POST()
      ->json();
    //{"errcode":0,"errmsg":"ok"}
    //{"errcode":40018,"errmsg":"invalid button name size"}
  }

  function get(){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141014
    return request::get(self::HOST.'/cgi-bin/menu/get')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }

  function delete():stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
    return request::get(self::HOST.'/cgi-bin/menu/delete')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }

  function addconditional(string $json):stdClass{
    return request::get(self::HOST.'/cgi-bin/menu/addconditional')
      ->query(['access_token'=>$this->token])
      ->body($json)
      ->POST()
      ->json();
    //{"menuid":"208379533"}
    //{"errcode":40018,"errmsg":"invalid button name size"}
  }

  function delconditional(string $json):stdClass{
    return request::get(self::HOST.'/cgi-bin/menu/delconditional')
      ->query(['access_token'=>$this->token])
      ->body($json)
      ->POST()
      ->json();
    //{"menuid":"208379533"}
  }

  function trymatch(string $json):stdClass{
    return request::get(self::HOST.'/cgi-bin/menu/trymatch')
      ->query(['access_token'=>$this->token])
      ->body($json)
      ->POST()
      ->json();
  }

  function get_current_selfmenu_info():stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1434698695
    return request::get(self::HOST.'/cgi-bin/get_current_selfmenu_info')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }

}
