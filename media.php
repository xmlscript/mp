<?php namespace mp; // vim: se fdm=marker:

use http\request;

class media{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

}
