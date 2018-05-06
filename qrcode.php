<?php namespace mp; // vim: se fdm=marker:

use http\request;

/**
 * 通用的qrcode只能赋予一个string或integer参数，扫码之后进入公众号session，将触发subscribe或SCAN事件。
 * 前者是未关注用户扫码流程，必须关注后才能进一步操作，而且额外附带了EventKey=qrscene_123123，其中qrscene_是前缀，后边的内容才是当初创建二维码时赋予的scene_id或scene_str。
 * 而且，还额外附带了Ticket，可以直接GET /cgi-bin/showqrcode?ticket=XXX 得到二维码图片
 *
 * 如果触发SCAN事件，则说明已经是粉丝，此时附带EventKey就是无前缀的scene_id或scene_str，也额外附带了Ticket
 *
 * 生成二维码之后可能返回两个或三个值：ticket，expire_seconds，url
 *  - ticket 如果不打算采用showqrcode获取官方样式的二维码图片，完全可以不理会这个ticket
 *  - expire_seconds 可能没有存在的意义，生成临时二维码时指定的时间，过期后扫描应该无法触发事件，也不会干扰业务流
 *  - url 类似"http://weixin.qq.com/q/kZgfwMTm72WWPkovabbI"的网址，能被微信客户端时别触发事件吧？待测试
 */
class qrcode extends wx{

  /**
   * 创建临时二维码
   * @param int $scene_id 目前支持1到100000
   * @param int $expire_seconds 默认30秒过期，最大允许2592000秒（30天）
   * @return ticket，expire_seconds, url url才是二维码实际扫描内容，开发者可以根据url来生成个性化二维码
   */
  function QR_SCENE(int $scene_id, int $expire_seconds=30):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['expire_seconds'=>$expire_seconds,'action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_id'=>$scene_id]]]))
      ->json());
  }


  /**
   * 创建临时二维码
   * @param string $scene_str 1到64字符
   * @param int $expire_seconds 默认30秒过期，最大允许2592000秒（30天）
   * @return ticket，expire_seconds, url url才是二维码实际扫描内容，开发者可以根据url来生成个性化二维码
   */
  function QR_STR_SCENE(string $scene_str, int $expire_seconds=30):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['expire_seconds'=>$expire_seconds,'action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_str'=>$scene_str]]]))
      ->json());
  }


  /**
   * 创建永久二维码
   * @param int $scene_id 目前支持1到100000
   */
  function QR_LIMIT_SCENE(int $scene_id):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_id'=>$scene_id]]]))
      ->json());
  }


  /**
   * 创建永久二维码
   * @param string $scene_str 1到64字符
   */
  function QR_LIMIT_STR_SCENE(string $scene_str):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/qrcode/create')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['action_name'=>__FUNCTION__,'action_info'=>['scene'=>['scene_str'=>$scene_str]]]))
      ->json());
  }


  /**
   * 如果直接访问这个API，将会得到一张jpg图片，如果出错将返回404
   */
  function showqrcode(string $ticket):string{
    return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
  }

}
