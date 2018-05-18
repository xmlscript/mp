<?php namespace mp; // vim: se fdm=marker:

use http\request;

class tpl{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * 获取可用的消息模板
   * @todo 获取模板，不能和发送模板消息合并吗？？？
   */
  function template():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
    return token::check(request::url(token::HOST.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->template_list;
    //TODO parse DATA 并转换为方法
    /*
{
 "template_list": [{
      "template_id": "iPk5sOIt5X_flOVKn5GrTFpncEYTojx6ddbt8WYoV5s",
      "title": "领取奖金提醒",
      "primary_industry": "IT科技",
      "deputy_industry": "互联网|电子商务",
      "content": "{ {result.DATA} }\n\n领奖金额:{ {withdrawMoney.DATA} }\n领奖  时间:{ {withdrawTime.DATA} }\n银行信息:{ {cardInfo.DATA} }\n到账时间:  { {arrivedTime.DATA} }\n{ {remark.DATA} }",
      "example": "您已提交领奖申请\n\n领奖金额：xxxx元\n领奖时间：2013-10-10 12:22:22\n银行信息：xx银行(尾号xxxx)\n到账时间：预计xxxxxxx\n\n预计将于xxxx到达您的银行卡"
   }]
}
     */
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
  function 发送模板消息(string $openid, string $template_id, string $url, array $data, array $miniprogram=null):int{
    //TODO: 没有彻底封装
    return token::check(request::url(token::HOST.'/cgi-bin/message/template/send')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode([
        'touser'=>$openid,
        'template_id'=>$template_id,
        'url'=>$url,
        'data'=>$data
      ], JSON_UNESCAPED_UNICODE)))->msgid;
  }

}
