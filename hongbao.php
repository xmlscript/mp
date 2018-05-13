<?php namespace mp; // vim: se fdm=marker:

use http\request;

class hongbao{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * 设置单个红包的金额，类型等，生成红包信息。预下单完成后，需要在72小时内调用jsapi完成抽红包的操作。（红包过期失效后，资金会退回到商户财付通帐号。）
   */
  function 发红包(){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1459327193
    return token::check(request::url('https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder')
      ->header('Content-Type','application/xml')
      ->POST()
      ->xml());
  }

  function 创建红包(){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1459327195
    $response = request::url('https://api.weixin.qq.com/shakearound/lottery/addlotteryinfo')
      ->query(['use_template'=>1,'logo_url'=>'LOG_URL','access_token'=>(string)$this->token])
      ->POST();
  }

}
