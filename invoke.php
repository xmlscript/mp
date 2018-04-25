<?php namespace mp; // vim: se fdm=marker:

use http\request;

class invoke{

  private $appid,$secret,$host;
  private const CIPHER = 'AES-256-CBC';
  
  final function __construct(string $appid, string $secret, string $host='https://api.weixin.qq.com'){
    $this->appid = $appid;
    $this->secret = $secret;
    $this->host = $host;
    $this->dir = session_save_path();//FIXME 仍然在/tmp之下呢？
    $this->dir = getcwd();
  }

  final function appid():string{
    return $this->appid;
  }


  final static function construct(string $appid, string $secret, string $host='https://api.weixin.qq.com'):self{
    return new self($appid, $secret, $host);
  }


  final private function load(string $filename, int $expire):?string{

    $file= $this->dir.DIRECTORY_SEPARATOR.'.'.$this->appid.'.'.$filename;
    $iv = substr($this->appid,-16);

    if(
      file_exists($file) &&
      time()-filemtime($file)<$expire && //文档说新旧token可以在临界点共存5分钟(300秒) 本机时间
      $token = openssl_decrypt(file_get_contents($file),self::CIPHER,$this->secret,OPENSSL_RAW_DATA,$iv)
    )
      return $token;
    else{
      is_writable($file) && unlink($file);
      return null;
    }

  }


  final private function &save(string $filename, string $str):string{

    $file= $this->dir.DIRECTORY_SEPARATOR.'.'.$this->appid.'.'.$filename;//FIXME 云主机这个路径可用吗？？？
    $iv = substr($this->appid,-16);

    file_put_contents($file, openssl_encrypt($result->access_token,self::CIPHER,$this->secret,OPENSSL_RAW_DATA,$iv)) or error_log("无法写入$file");

    return $str;

  }


  final function token():string{

    if($token = $this->load(__FUNCTION__, 7200))
      return $token;
    else{
      $result = request::url($this->host.'/cgi-bin/token')
        ->fetch(['grant_type'=>'client_credential','appid'=>$this->appid,'secret'=>$this->secret])
        ->json();
      if(isset($result->access_token)){
        return $this->save(__FUNCTION__,$result->access_token);
      }else
        throw new \Exception($result->errmsg, $result->errcode);
    }
  }


  /**
   * 公众号内嵌网页需要调用JSSDK，首先需要使用token获取ticket，进而计算得到signature
   * ticket应该在服务端缓存一份，7200秒(两小时)有效期
   */
  final function jsapi_ticket():string{
    if($ticket = $this->load(__FUNCTION__,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fecth(['access_token'=>$this->token()])
        ->json();
      if(isset($result->ticket)){
        return $this->save(__FUNCTION__,$result->ticket);
      }else
        throw new \Exception($result->errmsg, $result->errcode);
    }
  }


  /**
   * 调用微信卡券的ticket，又多一个么蛾子。。。
   * 说要通过access_token来获取，然而还不清楚到底是哪个token？？？
   * ticket应该在服务端缓存一份，7200秒(两小时)有效期
   */
  final function wxcard_ticket():string{
    if($ticket = $this->load(__FUNTION__,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fetch(['access_token'=>$this->token(),'type'=>'wx_card'])
        ->json();
      if(isset($result->ticket)){
        return $this->save(__FUNCTION__, $result->ticket);
        [
          'errcode' => 0,
          'errmsg' => 'ok',
          'ticket' => 'bxLdikRXVbTPdHSM05e5u5sUoXNKdvsdshFKA',
          'expires_in' => 7200
        ];
        return $result->ticket;
      }else
        throw new \Exception($result->errmsg, $result->errcode);
    }
  }


  /**
   * 通过code换取网页授权的access_token
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842
   */
  final function access_token(string $code):array{
    if($access_token = $this->load(__FUNCTION__,7200))
      return $access_token;
    else{
      $result = request::url($this->host.'/sns/oauth2/access_token')
        ->fetch(['appid'=>$this->appid,'secret'=>$this->secret,'code'=>$code,'grant_type'=>'authorization_code'])
        ->json();
      if(isset($result->access_token))
        return $this->save(__FUNCTION__, $result->access_token);
      elseif(isset($json->errcode,$json->errmsg))
        throw new \RunTimeException($json->errmsg,$json->errcode);
      else
        throw new \Error;
    }

    [
      'access_token' => 'sldjfldsjlkf',
      'expires_in' => 7200,
      'refresh_token' => 'dnfweiuncweiybrciwybricwe',
      'openid' => 'wxljlkfjlkjdflj',
      'scope' => 'snsapi_base' //逗号分隔
    ];

    [
      'errcode' => 40029,
      'errmsg' => 'invalid code'
    ];
    //TODO 如果scope是snsapi_base，则已经拿到openid就此结束
    //TODO 如果scope是snsapi_userinfo，则继续获取用户信息
  }


  /**
   * 如果之前是通过scope=snsapi_userinfo才能拿到数据，如果是snsapi_base则必然错误
   * @param string $code
   * @param string $openid
   * @param string $lang zh_CN | zh_TW | en
   * @throws \RunTimeException
   */
  function userinfo(string $code, string $openid, string $lang='zh_CN'):array{
    $access_token = $this->access_token($code);
    $response = request::url($this->host.'/sns/userinfo')
      ->fetch(['access_token'=>$access_token,'openid'=>$openid,'lang'=>$lang])
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


  /**
   * 公众号跳转URL，这是微信菜单按钮类型view的网址，通过微信授权之后可以获取粉丝信息
   * @see https://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
   *
   * 经过微信跳转，现在的请求页面变成了 redirect_uri?code=xxx&state=xxx
   * 此时需要拿code换取网页专用的access_token
   * 注意，每次跳转之后code都不同，而且code五分钟就失效，所以赶紧去换access_token
   *
   * @param string $scope 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且， 即使在未关注的情况下，只要用户授权，也能获取其信息 ）
   * @param string $state 重定向后会带上state参数，开发者可以填写a-zA-Z0-9的参数值，最多128字节
   */
  final function url(string $uri, string $state='', string $scope='snsapi_base'):string{
    return 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query([
      'appid'=>$this->appid,
      'redirect_uri'=>request::normalize($uri),
      'response_type'=>'code',
      'scope'=>$scope,
      'state'=>$state
    ]).'#wechat_redirect';
  }


  function w():array{#
    return request::url($this->host.'/cgi-bin/customservice/getonlinekflist')
      ->fetch(['access_token'=>$this->token()])
      ->json();
  }


  //FIXME: 封装的不彻底
  function whoami(string $openid){
    return request::url($this->host.'/cgi-bin/user/info')
      ->fetch(['access_token'=>$this->token(),'openid'=>$openid,'lang'=>'zh_CN'])
      ->json();
  }


  function getkflist():array{
    return request::url($this->host.'/cgi-bin/customservice/getkflist')
      ->fetch(['access_token'=>$this->token()])
      ->json();
  }


  /**
   * 获取可用的消息模板
   * @todo 获取模板，不能和发送模板消息合并吗？？？
   */
  function template():array{
    return request::url($this->host.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
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
      ->query(['access_token'=>$this->token()])
      ->POST();
  }


  /**
   * get/set菜单
   */
  function menu(
    array $menu=null,
    string $tag_id=null,//用户标签id，可以通过api查询
    int $sex=null,//1男2女
    string $country=null,//国家/省份/城市 有areainfo.zip地区信息表可供下载
    string $province=null,//从大往小匹配，能省略小的，但不能跳过大的
    string $city=null,
    int $client_platform_type=null,//1=iOS, 2=Android, 3=Others
    string $language=null//21种语言: zh_CN, zh_TW, zh_HK, en, id, ms, es, ko, it, ja, pl, pt, ru, th, vi, ar, hi, he, tr, de, fr
  ):array{

    if(is_null($menu)){

      $result = request::url($this->host.'/cgi-bin/menu/get')
        ->fetch(['access_token'=>$this->token()])
        ->json();
      if($result['errcode'])
        throw new \Exception($result['errmsg'],$result['errcode']);
      else
        return $result;
      

    }elseif($matchrule=array_filter(['tag_id'=>$tag_id,'sex'=>$sex,'country'=>$country,'province'=>$province,'city'=>$city,'client_platform_type'=>$client_platform_type,'language'=>$language])){

      $result = request::url($this->host.'/cgi-bin/menu/addconditional')
        ->query(['access_token'=>$this->token()])
        ->header('Content-Type','application/json;charset=UTF-8')
        ->POST(json_encode($menu+$matchrule))
        ->json();
      $result = $response->json();
      if(isset($result['menuid']))
        return $result;
      else
        throw new \Exception($result['errmsg'],$result['errcode']);

    }else{

      $result = request::url($this->host.'/cgi-bin/menu/create')
        ->query(['access_token'=>$this->token()])
        ->header('Content-Type','application/json;charset=UTF-8')
        ->POST(json_encode($json))
        ->json();
      if($result['errcode'])
        throw new \Exception($result['errmsg'],$result['errcode']);
      else
        return $result;

    }
  }


  /**
   * 获取官方ip
   */
  function ip():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140187
    $result = request::url($this->host.'/cgi-bin/getcallbackip')
      ->fetch(['access_token'=>$this->token()])
      ->json();
    if(isset($result['errcode'])&&$result['errcode'])
      throw new \Exception($result['errmsg'],$result['errcode']);
    return $result['ip_list'];
  }
  
  
  /**
   * 获取或重置调用次数限额，微信公众号平台限制每个接口不同的调用次数，超过后，每月允许重置n次
   * @todo api需要额外限制不能频繁调用，否则导致月底不可访问
   * @todo 忘记了，先看看能不能查询限额
   * @see 
   */
  function quota():bool{
    $response = request::get($this->host.'/cgi-bin/xxxxxxxxxxxxxxxx')
      ->query(['access_token'=>$this->token()])
      ->POST();
  }
   
   
  /**
   * 还有更多零碎的方法
   * @todo 批量给用户增删label
   * @todo 获取符合某label的匹配用户数组
   * @todo 管理素材
   * @todo 管理二维码
   * @todo 管理卡券
   * @todo 管理门店
   * @todo 摇一摇
   * @todo 统计分析
   * @todo 文章管理，以及文章的评论管理
   * @todo 微信支付（这个需要通过srv类转发吗？？？还是各个app内部实现更好？？？）
   * @todo 小程序
   * @todo 企业微信？？？
   */


  /**
   * 设置单个红包的金额，类型等，生成红包信息。预下单完成后，需要在72小时内调用jsapi完成抽红包的操作。（红包过期失效后，资金会退回到商户财付通帐号。）
   */
  function 发红包(){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1459327193
    $response = request::url('https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder')->POST();
  }

  function 创建红包(){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1459327195
    $response = request::url('https://api.weixin.qq.com/shakearound/lottery/addlotteryinfo')
      ->query(['use_template'=>1,'logo_url'=>'LOG_URL','access_token'=>$this->token()])
      ->POST();
  }

}
