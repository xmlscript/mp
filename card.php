<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

class card{

  private $host, $token, $ticket;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->host = $host;
    $this->token = $token;
  }


  /**
   * 调用微信卡券的ticket，又多一个么蛾子。。。
   * 说要通过access_token来获取，然而还不清楚到底是哪个token？？？
   * ticket应该在服务端缓存一份，7200秒(两小时)有效期
   */
  final function ticket():string{
    if($ticket = (string)new cache($this->appid.__FUNCTION__,$this->token,7200))
      return $ticket;
    else{
      $result = request::url($this->host.'/cgi-bin/ticket/getticket')
        ->fetch(['access_token'=>$this->token,'type'=>'wx_card'])
        ->json();
      if(isset($result->ticket)){
        return (new cache($this->appid.__FUNCTION__,$this->token))($result->ticket)[0];
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

}
