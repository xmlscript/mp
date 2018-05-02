<?php namespace mp; // vim: se fdm=marker:

use http\request;

class message{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  function send(string $msgtype, array $content, string ...$openid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $openid&&$this->check(request::url(self::HOST.'/cgi-bin/message/mess/send')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$openid,$msgtype=>$content,'msgtype'=>$msgtype]))
      ->json());
  }

  /**
   * 删除群发
   */
  function delete(int $msg_id, int $article_idx):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $msg_id&&$this->check(request::url(self::HOST.'/cgi-bin/message/mess/delete')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['msg_id'=>$msg_id,$article_idx=>$article_idx]))
      ->json());
  }


  function preview(string $openid, string $msgtype, array $content):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $content&&$this->check(request::url(self::HOST.'/cgi-bin/message/mess/preview')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$openid,'msgtype'=>$msgtype,$msgtype=>$content]))
      ->json());
  }


  function get(int $msg_id):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/message/mess/get')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['msg_id'=>$msg_id]))
      ->json())->msg_status;
  }


  function speed_get():\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/message/mess/speed/get')
      ->query(['access_token'=>$this->token])
      ->POST()
      ->json());
  }


  /**
   * @param int $speed 0到4，一共5个级别，分别是80w/min, 60w/min, 45w/min, 30w/min, 10w/min
   * @return 暂时不知道
   */
  function speed_set(int $speed):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/message/mess/speed/set')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['speed'=>$speed]))
      ->json());
  }

}
