<?php namespace mp; // vim: se fdm=marker:

use http\request;

class tpl{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
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

}
