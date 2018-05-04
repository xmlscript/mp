<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

class js{

  private $token,$ticket;
  
  final function __construct(token $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }


  /**
   * 公众号内嵌网页需要调用JSSDK，首先需要使用token获取ticket，进而计算得到signature
   * ticket应该在服务端缓存一份，7200秒(两小时)有效期
   */
  final function ticket():string{
    if($ticket = (string)new cache($this->token->appid.__FUNCTION__,$this->secret,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fecth(['access_token'=>$this->token])
        ->json();
      if(isset($result->ticket)){
        return (new cache($this->token->appid.__FUNCTION__,$this->secret))($result->ticket)[0];
      }else
        throw new \Exception($result->errmsg, $result->errcode);
    }
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
      'appid'=>$this->token->appid,
      'redirect_uri'=>request::normalize($uri),
      'response_type'=>'code',
      'scope'=>$scope,
      'state'=>$state
    ]).'#wechat_redirect';
  }

}
