<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

class token{

  private $appid,$secret,$host;
  private const CIPHER = 'AES-256-CBC';
  
  final function __construct(string $appid, string $secret, string $host='https://api.weixin.qq.com'){
    $this->appid = $appid;
    $this->secret = $secret;
    $this->host = $host;
    $this->dir = session_save_path();//FIXME 仍然在/tmp之下呢？
    $this->dir = getcwd();
  }


  final static function construct(string $appid, string $secret, string $host='https://api.weixin.qq.com'):self{
    return new self($appid, $secret, $host);
  }


  final function token():string{
    if($token=(string)new cache($this->appid.__FUNCTION__,$this->secret,7200))
      return $token;
    else{
      $result = request::url($this->host.'/cgi-bin/token')
        ->fetch(['grant_type'=>'client_credential','appid'=>$this->appid,'secret'=>$this->secret])
        ->json();
      if(isset($result->access_token)){
        return (new cache($this->appid.__FUNCTION__,$this->secret))($result->access_token)[0];
      }else
        throw new \Exception($result->errmsg, $result->errcode);
    }
  }


  /**
   * 公众号内嵌网页需要调用JSSDK，首先需要使用token获取ticket，进而计算得到signature
   * ticket应该在服务端缓存一份，7200秒(两小时)有效期
   */
  final function jsapi_ticket():string{
    if($ticket = (string)new cache($this->appid.__FUNCTION__,$this->secret,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fecth(['access_token'=>$this->token()])
        ->json();
      if(isset($result->ticket)){
        return (new cache($this->appid.__FUNCTION__,$this->secret))($result->ticket)[0];
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
    if($ticket = (string)new cache($this->appid.__FUNCTION__,$this->secret,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fetch(['access_token'=>$this->token(),'type'=>'wx_card'])
        ->json();
      if(isset($result->ticket)){
        return (new cache($this->appid.__FUNCTION__,$this->secret))($result->ticket)[0];
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
    if($access_token = (string)new cache($this->appid.__FUNCTION__,$this->secret,7200))
      return $access_token;
    else{
      $result = request::url($this->host.'/sns/oauth2/access_token')
        ->fetch(['appid'=>$this->appid,'secret'=>$this->secret,'code'=>$code,'grant_type'=>'authorization_code'])
        ->json();
      if(isset($result->access_token))
        return (new cache($this->appid.__FUNCTION__,$this->secret))($result->access_token)[0];
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

}
