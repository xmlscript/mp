<?php namespace mp; // vim: se fdm=marker:

use http\request;

class kf{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }


  function getkflist():array{
    return request::url($this->host.'/cgi-bin/customservice/getkflist')
      ->fetch(['access_token'=>$this->token()])
      ->json();
  }

}
