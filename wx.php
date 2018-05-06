<?php namespace mp; // vim: se fdm=marker:

use http\request;

abstract class wx{

  protected const HOST = 'https://api.weixin.qq.com';
  protected $token;
  
  final function __construct(token $token){
    $this->token = $token;
  }

  final protected function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }

}
