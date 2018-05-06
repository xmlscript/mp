<?php namespace mp; // vim: se fdm=marker:

use http\request;
use tmp\cache;

/**
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1443433542
 * POST cgi-bin/qrcode/create生成场景二维码将获得二维码ticket，官方支持GET /cgi-bin/showqrcode?ticket=TICKET直接获取图片
 *
 * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141115
 * jssdk需要的signature是由jsapi_ticket等四项推导出来的，而jsapi_ticket需要GET /cgi-bin/ticket/getticket?type=jsapi
 */
class ticket{

  private const HOST = 'https://api.weixin.qq.com';
  public $ticket;
  private static $expires_in;
  
  /**
   * @param string $type "jsapi"
   */
  final function __construct(token $token, string $type){
    $this->ticket = new cache($this->token->appid.$type, $this->secret, self::$expires_in[$type]??7200, function($type){
      $result = request::url(self::HOST.'/cgi-bin/ticket/getticket')
        ->fetch(['access_token'=>$this->token,'type'=>$type])
        ->json();
      if(isset($result->ticket,$result->expires_in)&&self::$expires_in=$result->expires_in)
        return $result->ticket;
      else
        error_log($result->errmsg);
    });
  }


  function __toString():string{
    return $this->ticket;
  }

}
