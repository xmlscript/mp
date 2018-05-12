<?php namespace mp; // vim: se fdm=marker:

use http\request;

class user extends wx{

  /**
   * 这个接口设计的特别幼稚
   * 一次获取1w个openid数组
   * 真正有用的只是get()->data->openid = [1w];
   * 此时yield已经失去意义
   * 也许存入cache比较好，但是新增的粉丝是否经过排序？或是追加到队列尾
   */
  function get(string $next_openid=null):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140840
    return $this->check(request::url(self::HOST.'/cgi-bin/user/get')
      ->fetch(['access_token'=>(string)$this->token,'next_openid'=>$next_openid])
      ->json());

    [
      "total" => 2,
      "count" => 2,
      "data" => ["openid"=>["OPENID1","OPENID2"]],
      "next_openid" => "NEXT_OPENID"
    ];
  }


  function info(string $openid):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839
    return $this->check(request::url(self::HOST.'/cgi-bin/user/info')
      ->fetch(['access_token'=>(string)$this->token,'openid'=>$openid,'lang'=>'zh_CN'])
      ->json());
  }


  function batchget(string ...$openid):array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140839
    return $this->check(request::url(self::HOST.'/cgi-bin/user/info/batchget')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['user_list'=>array_map(function($v){return ['openid'=>$v];},$openid)]))
      ->json())->user_info_list;
  }


  function updateremark(string $openid, string $str):bool{#
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140838
    return request::url(self::HOST.'/cgi-bin/user/info/updateremark')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['openid'=>$openid,'remark'=>$str]))
      ->json();
  }

  /**
   * @param string ...$openid 最多20个
   */
  function batchblacklist(string ...$openid_list):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1471422259_pJMWA
    return $openid&&$this->check(request::url(self::HOST.'/cgi-bin/tags/members/batchblacklist')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['openid_list'=>$openid_list]))
      ->json());
  }

  /**
   * @param string ...$openid 最多20个
   */
  function batchunblacklist(string ...$openid_list):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1471422259_pJMWA
    return $openid&&$this->check(request::url(self::HOST.'/cgi-bin/tags/members/batchunblacklist')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['openid_list'=>$openid_list]))
      ->json());
  }

}
