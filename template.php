<?php namespace mp; // vim: se fdm=marker:

use http\request;

class template{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * @param int $industry_id1 目前支持1到41
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function api_set_industry(int $industry_id1, int $industry_id2):bool{
    return !request::url(token::HOST.'/cgi-bin/template/api_set_industry')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['industry_id1'=>$industry_id1,'industry_id2'=>$industry_id2]))
      ->json()->errcode;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function get_industry(int $industry_id1, int $industry_id2):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/template/get_industry')
      ->fetch(['access_token'=>(string)$this->token])
      ->json());
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function api_add_template(string $template_id_short):string{
    return token::check(request::url(token::HOST.'/cgi-bin/template/api_add_template')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['template_id_short'=>$template_id_short]))
      ->json())->template_id;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function get_all_private_template():array{
    return token::check(request::url(token::HOST.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->template_list;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function del_private_template(string $template_id):bool{
    return !request::url(token::HOST.'/cgi-bin/template/del_private_template')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['template_id'=>$template_id]))
      ->json()->errcode;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function send(string $template_id, string $openid, array $data):int{
    return token::check(request::url(token::HOST.'/cgi-bin/template/del_private_template')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['template_id'=>$template_id,'touser'=>$openid,'data'=>$data]))
      ->json())->msgid;
  }

}
