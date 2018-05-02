<?php namespace mp; // vim: se fdm=marker:

use http\request;

class kf{

  private $token;
  
  final function __construct(string $token, string $host='https://api.weixin.qq.com'){
    $this->token = $token;
  }

  private function check(\stdClass $json):\stdClass{
    if(isset($json->errcode,$json->errmsg)&&$json->errcode)
      throw new \RuntimeException($json->errmsg,$json->errcode);
    return $json;
  }


  function getonlinekflist():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044813
    return $this->check(request::url($this->host.'/cgi-bin/customservice/getonlinekflist')
      ->fetch(['access_token'=>$this->token()])
      ->json())->kf_online_list;
  }


  /**
   * @param string $kf_account 完整客服帐号，格式为：帐号前缀@公众号微信号，帐号前缀最多10个字符，必须是英文、数字字符或者下划线，后缀为公众号微信号，长度不超过30个字符
   * @param string $nickname 限制16字
   * @todo 自动补全kf_account
   */
  function add(string $kf_account, string $nickname):bool{
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfaccount/add')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'nickname'=>$nickname]))
      ->json());
  }


  /**
   * 新添加的客服帐号是不能直接使用的，只有客服人员用微信号绑定了客服账号后，方可登录Web客服进行操作。
   * 此接口发起一个绑定邀请到客服人员微信号，客服人员需要在微信客户端上用该微信号确认后帐号才可用。
   * 尚未绑定微信号的帐号可以进行绑定邀请操作，邀请未失效时不能对该帐号进行再次绑定微信号邀请。
   */
  function inviteworker(string $kf_account, string $invite_wx):bool{
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfaccount/inviteworker')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'invite_wx'=>$invite_wx]))
      ->json());
  }


  function update(string $kf_account, string $nickname):bool{
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfaccount/update')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'nickname'=>$nickname]))
      ->json());
  }


  function uploadheadimg(string $kf_account):bool{
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfaccount/uploadheadimg')
      ->query(['access_token'=>$this->token,'kf_account',$kf_account])
      ->upload()//FIXME form-data 中媒体文件标识，有filename、filelength、content-type 等信息，文件大小为5M 以内
      ->json());
  }


  function del(string $kf_account):bool{
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/del')
      ->fetch(['access_token'=>$this->token,'kf_account'=>$kf_account])
      ->json());
  }

}
