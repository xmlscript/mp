<?php namespace mp; // vim: se fdm=marker:

use http\request;

class user{

  private $token;
  
  final function __construct(string $token){
    $this->token = $token;
  }


  /**
   * 如果之前是通过scope=snsapi_userinfo才能拿到数据，如果是snsapi_base则必然错误
   * @param string $code
   * @param string $openid
   * @param string $lang zh_CN | zh_TW | en
   * @throws \RunTimeException
   */
  function info(string $code, string $openid, string $lang='zh_CN'):array{
    $response = request::url($this->host.'/sns/userinfo')
      ->fetch(['access_token'=>$this->token,'openid'=>$openid,'lang'=>$lang])
      ->json();

    if(isset($response->errcode,$response->errmsg))
      throw new \RunTimeException($response->errmsg,$response->errcode);
    else
      return $response;

    [
      'openid' => 'oaAFuxKa4UsAytIN5SatkctKbMtg',
      'nickname' => '二眉猫',
      'sex' => 1, //1男2女0未知
      'province' => '陕西',
      'city' => '西安',
      'country' => '中国',
      'headimgurl' => 'http://thirdwx.qlogo.cn/mmopen/xxxxxxxxxx/46',
      'privilege' => ['chinaunicom','xxx'],//用户特权，chinaunicom是微信沃卡用户
      'unionid' => 'oB39WwSIXRtrFZLFewc9QEdMLgxo'
    ];

    [
      'errcode' => 40003,
      'errmsg' => 'invalid openid'
    ];
  }


  function w():array{#
    return request::url($this->host.'/cgi-bin/customservice/getonlinekflist')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }


  //FIXME: 封装的不彻底
  function whoami(string $openid){
    return request::url($this->host.'/cgi-bin/user/info')
      ->fetch(['access_token'=>$this->token,'openid'=>$openid,'lang'=>'zh_CN'])
      ->json();
  }


  function getkflist():array{
    return request::url($this->host.'/cgi-bin/customservice/getkflist')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }


  /**
   * 获取可用的消息模板
   * @todo 获取模板，不能和发送模板消息合并吗？？？
   */
  function template():array{
    return request::url($this->host.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>$this->token])
      ->json();
  }


  /**
   * @see event::TemplateSendJobFinish()
   * @param $touser
   * @param $template_id
   * @param $url=null 模板跳转链接
   * @param $miniprogram=null 跳小程序所需数据，不需跳小程序可不用传该数据
   *   string $appid 所需跳转到的小程序appid（该小程序appid必须与发模板消息的公众号是绑定关联关系）
   *   string $pagepath 所需跳转到小程序的具体页面路径，支持带参数,（示例index?foo=bar）
   * @param array &$data 模板数据
   */
  function 发送模板消息(string $openid, string $template_id, string $url, array &$data, array $miniprogram=null):\stdClass{#
    //TODO: 没有彻底封装
    return request::url($this->host.'/cgi-bin/message/template/send')
      ->query(['access_token'=>$this->token])
      ->timeout($this->timeout)
      ->POST(json_encode([
        'touser'=>$openid,
        'template_id'=>$template_id,
        'url'=>$url,
        'data'=>$data
      ]));
  }


  /**
   * 给某个粉丝添加备注
   * @todo 批量添加备注可行吗？看官方文档似乎并没有提供
   */
  function 备注(string $openid, string $str):bool{#
    $result = request::url($this->host.'')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['openid'=>$openid,'remark'=>$str]))
      ->json();
    if($result['errcode'])
      throw new \Exception($result['errmsg'], $result['errcode']);
    else
      return true;
  }
  
  /**
   * 批量拉黑粉丝
   */
  function deny(string ...$openid):bool{
    $result = request::url($this->host.'/cgi-bin/tags/members/batchblacklist')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['openid_list'=>$openid]))
      ->json();
    if($result['errcode'])
      return true;
    else throw new \Exception($result['errmsg'], $result['errcode']);
  }


  /**
   * 取消拉黑粉丝
   * @todo 方法越来越多，已经不能明显看出来方法名到底是说的谁了
   * @see
   */
  function allow(string ...$openid):bool{
    $response = request::url($this->host.'/cgi-bin/XXXXXXXXXXXXX')
      ->query(['access_token'=>$this->token])
      ->POST();
  }

  /**
   * 获取粉丝黑名单
   * {
   *  "total":23000,
   *  "count":10000,
   *  "data":{"
   *     openid":[
   *        "OPENID1",
   *        "OPENID2",
   *        ...,
   *        "OPENID10000"
   *     ]
   *   },
   *   "next_openid":"OPENID10000"
   * }
   *
   * @param int $begin_openid 为空时，默认从开头拉取
   * @todo 能否实现自动翻页？？
   */
  function blacklist(string $begin_openid=''):array{
    $result = request::url($this->host.'/cgi-bin/tags/members/getblacklist')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['begin_openid'=>$begin_openid]))
      ->json();
    if(isset($result['errcode'])&&$result['errcode'])
      throw new \Exception($result['errmsg'],$result['errcode']);
    return $result;
  }

  
  /**
   * 增删改查用户标签label
   * @param ?string $label 如果label显式null，就是查询所有label；如果$value显式null，表示删除这个label，其他情况表示覆盖label
   * @see 
   */
  function label(?string $label, ?string $value=null){
    $response = request::url($this->host.'/cgi-bin/XXXXXXXXXXXXX')
      ->query(['access_token'=>$this->token])
      ->POST();
  }


  function menu(string $menu=''):array{
    if($menu)
      return request::url($this->host.'/cgi-bin/menu/addconditional')
        ->query(['access_token'=>$this->token])
        ->header('Content-Type','application/json;charset=UTF-8')
        ->POST(json_encode($menu+$matchrule))
        ->json();
    else
      return request::url($this->host.'/cgi-bin/menu/get')
        ->fetch(['access_token'=>$this->token])
        ->json();
  }

}
