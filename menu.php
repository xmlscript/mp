<?php namespace mp;

use http\request;

class menu extends wx{

  function __toString():string{
    try{
      $menu = $this->get()->menu;
      unset($menu->menuid);
      foreach($menu->button as &$obj){
        if(empty($obj->sub_button))
          unset($obj->sub_button);
        else
          foreach($obj->sub_button as &$obj)
            unset($obj->sub_button);
      }
      return str_replace('  ',' ',json_encode($menu,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    }catch(\Throwable $t){
      return '';
    }
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }

  function get():\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/menu/get')->fetch(['access_token'=>(string)$this->token])->json());
  }

  function trymatch(string $id):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/menu/trymatch')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['user_id'=>$id]))
      ->json());
  }

  /**
   * 总是能获取最后一次用过的menu，即便delete之后，也可以获取，但is_menu_open=0
   */
  function get_current_selfmenu_info():\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1434698695
    return $this->check(request::url(self::HOST.'/cgi-bin/get_current_selfmenu_info')->fetch(['access_token'=>(string)$this->token])->json());
  }


  /**
   * @todo 判断不要重复添加，unique
   */
  function addconditional(string $json):string{
    return $this->check(request::url(self::HOST.'/cgi-bin/menu/addconditional')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST($json)
      ->json())->menuid;
  }

  function create(string $json):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
    return json_decode($json)&&$this->check(request::url(self::HOST.'/cgi-bin/menu/create')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST($json)
      ->json());
  }

  function delete():bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
    return !$this->check(request::url(self::HOST.'/cgi-bin/menu/delete')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->errcode;
  }

  function delconditional(int $menuid):bool{
    return $menuid&&$this->check(request::url(self::HOST.'/cgi-bin/menu/delconditional')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['menuid'=>$menuid]))
      ->json());
  }


}
