<?php namespace mp; // vim: se fdm=marker:

use http\request;

class template{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  /**
   * @param int $industry_id1 目前支持1到41
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function api_set_industry(int $industry_id1, int $industry_id2):bool{
    return $industry_id1&&$this->check(request::url(self::HOST.'/cgi-bin/template/api_set_industry')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['industry_id1'=>$industry_id1,'industry_id2'=>$industry_id2]))
      ->json());
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function get_industry(int $industry_id1, int $industry_id2):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/template/get_industry')
      ->fetch(['access_token'=>$this->token])
      ->json());
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function api_add_template(string $template_id_short):string{
    return $this->check(request::url(self::HOST.'/cgi-bin/template/api_add_template')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['template_id_short'=>$template_id_short]))
      ->json())->template_id;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function get_all_private_template():array{
    return $this->check(request::url(self::HOST.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>$this->token])
      ->json())->template_list;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function del_private_template(string $template_id):array{
    return $template_id&&$this->check(request::url(self::HOST.'/cgi-bin/template/del_private_template')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['template_id'=>$template_id]))
      ->json());
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
   */
  function send(string $template_id, string $openid, array $data):int{
    return $this->check(request::url(self::HOST.'/cgi-bin/template/del_private_template')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['template_id'=>$template_id,'touser'=>$openid,'data'=>$data]))
      ->json())->msgid;
  }

}
