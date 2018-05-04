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


  function getkflist():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
    return $this->check(request::url($this->host.'/cgi-bin/customservice/getkflist')
      ->fetch(['access_token'=>$this->token()])
      ->json())->kf_list;
  }


  /**
   * 下发输入状态，需要客服之前30秒内跟用户有过消息交互。(如果30s内客服对粉丝发过消息，这样算吗？)
   * 在输入状态中（持续15s），不可重复下发输入态。(重复设置Typing是多余且无效的)
   * 在输入状态中，如果向用户下发消息，会同时取消输入状态。（一旦发送消息，则Typing状态作废）
   */
  function typing(string $openid, bool $typing):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
    return $openid&&$this->check(request::url($this->host.'/cgi-bin/message/custom/typing')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['touser'=>$openid,'command'=>$typing?'Typing':'CancelTyping']))
      ->json());
  }


  /**
   * @param string $kf_account 完整客服帐号，格式为：帐号前缀@公众号微信号，帐号前缀最多10个字符，必须是英文、数字字符或者下划线，后缀为公众号微信号，长度不超过30个字符
   * @param string $nickname 限制16字
   * @todo 自动补全kf_account
   */
  function add(string $kf_account, string $nickname):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044813
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfaccount/add')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'nickname'=>$nickname]))
      ->json());
  }


  /**
   * @param string $password 文档说是pswmd5，一定要md5吗？？？
   */
  function add2(string $kf_account, string $nickname, string $password):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
    return $kf_account&&$this->check(request::url($this->host.'/customservice/kfaccount/add')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'nickname'=>$nickname,'password'=>md5($password)]))
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


  /**
   * 文档是胡扯！GET/POST前后矛盾，而且为什么要这么多参数？？？
   */
  function del2(string $kf_account,string $nickname, string $password):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140547
    return $kf_account&&$this->check(request::url($this->host.'/customservice/del')
      ->fetch(['access_token'=>$this->token])
      ->POST(json_encode(['kf_account'=>$kf_account,'nickname'=>$nickname,'password'=>md5($password)]))
      ->json());
  }


  function create(string $kf_account, string $openid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044820
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfsession/create')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'openid'=>$openid]))
      ->json());
  }


  function close(string $kf_account, string $openid):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044820
    return $kf_account&&$this->check(request::url($this->host.'/cgi-bin/customservice/kfsession/close')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['kf_account'=>$kf_account,'openid'=>$openid]))
      ->json());
  }


  function getsession(string $openid):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044820
    return $this->check(request::url($this->host.'/cgi-bin/customservice/kfsession/getsession')
      ->fetch(['access_token'=>$this->token,'openid'=>$openid])
      ->json());
  }


  function getsessionlist(string $kf_account):array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044820
    return $this->check(request::url($this->host.'/cgi-bin/customservice/kfsession/getsessionlist')
      ->fetch(['access_token'=>$this->token,'kf_account'=>$kf_account])
      ->json())->sessionlist;
  }


  function getwaitcase():array{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1458044820
    return $this->check(request::url($this->host.'/cgi-bin/customservice/kfsession/getwaitcase')
      ->fetch(['access_token'=>$this->token])
      ->json())->waitcaselist;
  }


  /**
   * @param \DateTime $starttime unix时间戳
   * @param \DateTime $endtime 时段最多24小时，而且不知道前后颠倒可以么？
   * @param int $msgid 消息id顺序从小到大，从1开始
   * @param int $number 每次获取条数，最多10000条
   */
  function getmsglist(\DateTime $starttime, \DateTime $endtime, int $msgid=1, int $number=10000):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1464937269_mUtmK
    return $this->check(request::url($this->host.'/cgi-bin/customservice/msgrecord/getmsglist')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['starttime'=>$starttime,'endtime'=>$endtime,'msgid'=>$msgid,'number'=>$number]))
      ->json());
  }

}
