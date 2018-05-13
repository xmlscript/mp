<?php namespace mp;

use http\request;

/**
 * view类型的button需要在后台设置：“网页帐号（网页授权获取用户基本信息）无上限”，加入纯域名
 * 如果没有正确设置，点击将弹出对话框：（微信登录失败）redirect_url域名与后台配置不一致，错误码:10003
 * 必须掏钱开通微信认证才能获得该权限
 * 账号主体是个人，不能开通微信认证
 * 测试号直接获得该权限，而且额外支持ip
 * @todo 纯域名！！！一级域名！
 * @todo 测试号似乎不是个人的，
 */
class menu{
  
  function __construct(token $token){
    $this->token = $token;
  }

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

  function get():\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/menu/get')->fetch(['access_token'=>(string)$this->token])->json());
  }

  function trymatch(string $id):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/menu/trymatch')
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
    return token::check(request::url(token::HOST.'/cgi-bin/get_current_selfmenu_info')->fetch(['access_token'=>(string)$this->token])->json());
  }


  /**
   * @todo 判断不要重复添加，unique
   */
  function addconditional(string $json):string{
    return token::check(request::url(token::HOST.'/cgi-bin/menu/addconditional')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST($json)
      ->json())->menuid;
  }

  function create(string $json):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141013
    return json_decode($json)&&token::check(request::url(token::HOST.'/cgi-bin/menu/create')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST($json)
      ->json());
  }

  function delete():bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141015
    return !token::check(request::url(token::HOST.'/cgi-bin/menu/delete')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->errcode;
  }

  function delconditional(int $menuid):bool{
    return !request::url(token::HOST.'/cgi-bin/menu/delconditional')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['menuid'=>$menuid]))
      ->json()->errcode;
  }


}
