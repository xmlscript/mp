<?php namespace mp; // vim: se fdm=marker:

use http\request;

class quote{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }
  
  
  /**
   * 获取或重置调用次数限额，微信公众号平台限制每个接口不同的调用次数，超过后，每月允许重置n次
   * @todo api需要额外限制不能频繁调用，否则导致月底不可访问
   * @todo 忘记了，先看看能不能查询限额
   * @see 
   */
  function quota():bool{
    $response = request::get($this->host.'/cgi-bin/xxxxxxxxxxxxxxxx')
      ->query(['access_token'=>$this->token])
      ->POST();
  }

}
