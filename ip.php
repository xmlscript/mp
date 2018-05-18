<?php namespace mp; // vim: se fdm=marker:

use http\request;

class ip implements \ArrayAccess, \Countable{
  
  function __construct(token $token){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140187
    foreach(token::check(request::url(token::HOST.'/cgi-bin/getcallbackip')
      ->fetch(['access_token'=>(string)$token])
      ->json())->ip_list as $k=>$v)
      $this->$k = $v;
  }


  function __toString():string{
    return join(PHP_EOL,(array)$this);
  }

  function count():int{
    return count((array)$this);
  }

  function offsetExists($offset){
    return isset($this->$offset);
  }

  function offsetSet($offset, $value){
    $this->$offset = $value;
  }

  function offsetGet($offset){
    return $this->$offset;
  }

  function offsetUnset($offset){
    unset($this->$offset);
  }

}
