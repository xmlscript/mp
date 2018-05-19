<?php namespace mp; // vim: se fdm=marker:

use http\request;

class tpl{
  
  final function __construct(token $token){
    $this->token = $token;
  }


  final function __invoke():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277
    return token::check(request::url(token::HOST.'/cgi-bin/template/get_all_private_template')
      ->fetch(['access_token'=>(string)$this->token])
      ->json())->template_list;
    /*{
 "template_list": [{
      "template_id": "iPk5sOIt5X_flOVKn5GrTFpncEYTojx6ddbt8WYoV5s",
      "title": "领取奖金提醒",
      "primary_industry": "IT科技",
      "deputy_industry": "互联网|电子商务",
      "content": "{{result.DATA}}\n\n领奖金额:{{withdrawMoney.DATA}}\n领奖  时间:{{withdrawTime.DATA}}\n银行信息:{{cardInfo.DATA}}\n到账时间:  {{arrivedTime.DATA}}\n{{remark.DATA}}",
      "example": "您已提交领奖申请\n\n领奖金额：xxxx元\n领奖时间：2013-10-10 12:22:22\n银行信息：xx银行(尾号xxxx)\n到账时间：预计xxxxxxx\n\n预计将于xxxx到达您的银行卡"
   }]
}*/
  }


  /**
   * 需要二次封装，固化template_id，固化方法名（采用title名？重名？），固化参数，固化颜色
   * @see event::TemplateSendJobFinish()
   * @param $touser
   * @param $template_id
   * @param $url=null 模板跳转链接
   * @param $miniprogram=[] 跳小程序所需数据，不需跳小程序可不用传该数据
   *   string appid 所需跳转到的小程序appid（该小程序appid必须与发模板消息的公众号是绑定关联关系）
   *   string pagepath 所需跳转到小程序的具体页面路径，支持带参数,（示例index?foo=bar）
   * @param array &$data 模板数据
   */
  final function send(string $openid, string $template_id, array $data, string $url='', array $miniprogram=[]):string{
    //TODO: 没有彻底封装
    return token::check(request::url(token::HOST.'/cgi-bin/message/template/send')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode([
        'touser'=>$openid,
        'template_id'=>$template_id,
        'data'=>$data,
        'url'=>$url,
        'miniprogram'=>$miniprogram
      ], JSON_UNESCAPED_UNICODE))
      ->json())->msgid;
  }

}
