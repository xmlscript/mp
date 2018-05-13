<?php namespace mp; // vim: se fdm=marker:

use http\request;

class message{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * 暂时不知道返回什么数据
   */
  function send(string $openid, string $msgtype, array $content, string $kf_account=''):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
    return token::check(request::url(token::HOST.'/cgi-bin/message/custom/send')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['to_user'=>$openid,'msgtype'=>$msgtype,$msgtype=>$content,'customservice'=>['kf_account'=>$kf_account]]))
      ->json());
  }


  /**
   * 群发接口，唯一区别是openid是单个或多个
   */
  function sends(string $msgtype, array $content, string ...$openid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return !request::url(token::HOST.'/cgi-bin/message/mess/send')//仅认证服务号可用,而订阅号不可用
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$openid,'msgtype'=>$msgtype,$msgtype=>$content]))
      ->json()->errcode;
  }


  /**
   * 按粉丝标签群发
   * @param bool $send_ignore_reprint 仅当群发类型为文章时有意义，如果判定为转载，false就停止，true就尝试在原创文允许转载时继续群发
   */
  function sendall(string $msgtype, array $content, string $tag_id, bool $send_ignore_reprint=false):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return !request::url(token::HOST.'/cgi-bin/message/mess/sendall')//仅认证后可用
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['msgtype'=>$msgtype,$msgtype=>$content,'filter'=>['is_to_all'=>false,'tag_id'=>$tag_id],'send_ignore_reprint'=>(int)$send_ignore_reprint]))
      ->json()->errcode;
  }

  /**
   * 通常，使用这个接口时，很有可能这是一场运营事故了
   * 删除群发，仅对图文消息和视频消息有效，而且仅能删除详情页，card依然存在
   * @param int $article_idx 针对删除图文消息中第几条文章，第一篇是1，不填或填0都将全部删除
   */
  function delete(int $msg_id, int $article_idx):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return !request::url(token::HOST.'/cgi-bin/message/mess/delete')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['msg_id'=>$msg_id,$article_idx=>$article_idx]))
      ->json()->errcode;
  }


  /**
   * 每天100次
   * @param string $touser 粉丝所在公众号内的openid
   * @param string $towxname 粉丝的微信号,二选一，同时存在时，towxname优先（如果填''或null怎么样）
   */
  function preview(string $touser, string $msgtype, array $content):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return !request::url(token::HOST.'/cgi-bin/message/mess/preview')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$touser,'msgtype'=>$msgtype,$msgtype=>$content]))
      ->json()->errcode;
  }


  /**
   * @return string "SEND_SUCCESS" | "SENDING" | "SEND_FAIL" | "DELETE"
   */
  function get(int $msg_id):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return token::check(request::url(token::HOST.'/cgi-bin/message/mess/get')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['msg_id'=>$msg_id]))
      ->json())->msg_status;
  }


  function speed_get():\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return token::check(request::url(token::HOST.'/cgi-bin/message/mess/speed/get')
      ->query(['access_token'=>(string)$this->token])
      ->POST()
      ->json());
  }


  /**
   * @param int $speed 0到4，一共5个级别，分别是80w/min, 60w/min, 45w/min, 30w/min, 10w/min
   * @return 暂时不知道
   */
  function speed_set(int $speed):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return token::check(request::url(token::HOST.'/cgi-bin/message/mess/speed/set')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['speed'=>$speed]))
      ->json());
  }


  /**
   * 引导用户访问这个url，以获取授权，得到一次给用户推送一条模板消息的资格
   * @param int $scene 0到10000
   * @param string $template_id
   * @param string $redirect_url 域名部分必须匹配“业务域名”
   * @param string $reserved 0-9a-zA-Z最多128字
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1500374289_66bvB
   */
  function subscribemsg(int $scene, string $template_id, string $redirect_url, string $reserved):string{
    return request::url(token::HOST.'/mp/subscribemsg')
      ->query([
        'action'=>'get_confirm',
        'appid'=>$this->token->appid,
        'scene'=>$scene,
        'template_id'=>$template_id,
        'redirect_url'=>rawurlencode($redirect_url),
        'reserved'=>rawurlencode($reserved)
      ]).'#wechat_redirect';
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1500374289_66bvB
   */
  function subscribe(string $openid, string $template_id, string $scene, string $title, array $data):bool{
    return $openid&&$this->check(request::url(self::HOST.'/cgi-bin/message/template/subscribe')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$openid,'template_id'=>$template_id,'scene'=>$scene,'title'=>$title,'data'=>$data]))
      ->json());
  }

}
