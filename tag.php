<?php namespace mp; // vim: se fdm=marker:

use http\request;

class tag{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * @param string ...$name 公众号最多100个标签，每个标签限制30字
   */
  function create(string ...$name):array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140837
    return token::check(request::url(token::HOST.'/cgi-bin/tags/create')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['tag'=>array_map(function($v){return ['name'=>$v];},$name)]))
      ->json())->tag;//TODO 把[{'id':int,'name':str},...] 转换成 [int=>$str,...]
  }


  function get(string $next_openid=null):array{
    return token::check(request::url(token::HOST.'/cgi-bin/tags/get')
      ->fetch(['access_token'=>$this->token])
      ->json())->tags;

  }


  function update(int $id, string $name):bool{
    return !request::url(token::HOST.'/cgi-bin/tags/update')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['tag'=>['id'=>$id,'name'=>$name]]))
      ->json()->errcode;
  }


  function delete(int $id):bool{
    return !request::url(token::HOST.'/cgi-bin/tags/delete')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['tag'=>['id'=>$id]]))
      ->json()->errcode;
  }


  function get_user(int $id, string $next_openid=''):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/user/tag/get')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['tagid'=>$id,'next_openid'=>$next_openid]))
      ->json());
  }


  /**
   * @param string ...$openid_list 每次最多50人，而且每人最多20个
   */
  function batchtagging(int $id, string ...$openid_list):bool{
    return !request::url(token::HOST.'/cgi-bin/tags/members/batchtagging')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['openid_list'=>$openid_list,'tagid'=>$id]))
      ->json()->errcode;
  }


  /**
   * @param string ...$openid_list 每次最多50人
   */
  function batchuntagging(int $id, string ...$openid_list):bool{
    return !request::url(token::HOST.'/cgi-bin/tags/members/batchuntagging')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['openid_list'=>$openid_list,'tagid'=>$id]))
      ->json()->errcode;
  }

  /**
   * @param string ...$openid 最多20个
   */
  function batchblacklist(string ...$openid_list):bool{
    return !request::url(token::HOST.'/cgi-bin/tags/members/batchblacklist')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['openid_list'=>$openid_list]))
      ->json()->errcode;
  }


  function getidlist(string $openid):array{
    return token::check(request::url(token::HOST.'/cgi-bin/tags/getidlist')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['openid'=>$openid]))
      ->json())->tagid_list;
  }

}
