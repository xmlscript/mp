<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

class invoke{

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


}
