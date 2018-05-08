<?php namespace mp; // vim: se fdm=marker:

use http\request;

/**
 * 卡券二维码，只允许用户扫码领取卡券一次而已。
 * 1. 获取token
 * 2. 上传图片素材 POST /cgi-bin/media/uploadimg
 * 3. 设置适用门店，
 * 首先，POST /card/qrcode/create生成卡券，此时返回json包括：
 *  - ticket 可以自己拼装GET /cgi-bin/showqrcode?ticket=XXX
 *  - url 二维码解析的实际内容，开发者可以自行生成个性化的二维码
 *  - show_qrcode_url 已经拼装好了的showqrcode网址，真是让人精神分裂。。。
 */
class card extends wx{

  /**
   * @param array $mixed 非常复杂的结构
   *  - 团购券 GROUPON
   *  - 代金券 CASH
   *  - 折扣券 DISCOUNT
   *  - 兑换券 GIFT
   *  - 优惠券 GENERAL_COUPON
   */
  function create(array $mixed):string{
    return $this->check(request::url(self::HOST.'/card/create')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json')
      ->POST(json_encode(['card'=>$mixed]))//FIXME 比较麻烦
      ->json())->card_id;
  }


  /**
   * 之前做好了card之后，需要用二维码的形式让用户扫码，以便将扫到的卡券装入用户自己的卡包里
   * @param string $action_name "QR_CARD" "QR_MULTIPLE_CARD"
   * @param string $card_id
   * @param string $code 如果$use_custom_code===true，则此项必填。自定义code和导入code模式的卡券不必填写???
   * @param string $openid 指定领取者的openid，只有该用户能领取。bind_openid字段为true的卡券必须填写，非指定openid不必填写。
   * @param bool $is_unique_code=false 
   * @param int $expire_seconds 指定二维码的有效时间，范围是60 ~ 1800秒。不填默认为365天有效
   * @param bool $bind_openid
   * @param int $outer_id=0 领取场景值，用于领取渠道的数据统计，60位数字。用户领取卡券后触发的 事件中会带上此自定义场景值。
   * @param string $outer_str outer_id字段升级版本，字符串类型，用户首次领卡时，会通过 领取事件推送 给商户； 对于会员卡的二维码，用户每次扫码打开会员卡后点击任何url，会将该值拼入url中，方便开发者定位扫码来源
   *
   * @todo 事件里能做什么？？？
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1451025062
   */
  function qrcode(string $card_id):\stdClass{
    return $this->check(request::url(self::HOST.'/card/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json')
      ->POST(json_encode(['action_name'=>'QR_CARD|QR_MULTIPLE_CARD']))//FIXME 比较麻烦
      ->json());

    [
     "errcode"=>0,
     "errmsg"=>"ok",
     "ticket"=>"gQH98...SfgVQMEgDPhAQ==",
     "expire_seconds"=>1800,
     "url"=>"http://weixin.qq.com/q/BHWya_zlfioH6fXeL16o ",
     "show_qrcode_url"=>" https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQH98...SfgVQMEgDPhAQ%3D%3D"
   ];
  }

}
