<?php namespace mp; // vim: se fdm=marker:

use http\request;

class datacube extends wx{

  /**
   * 获取用户增减数据，跨度7天
   * @param \DateTime $begin_date YYYY-mm-dd
   * @param \DateTime $end_date 最大值为昨日
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141082
   */
  function getusersummary(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取累计用户数据，跨度7天
   */
  function getusercumulate(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }



  /**
   * 获取图文群发每日数据，跨度1天
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141084
   */
  function getarticlesummary(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取图文群发总数据，跨度1天
   */
  function getarticletotal(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取图文统计数据，跨度3天
   */
  function getuserread(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取图文统计分时数据，跨度1天
   */
  function getuserreadhour(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取图文分享转发数据，跨度7天
   */
  function getusershare(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取图文分享转发分时数据，跨度1天
   */
  function getusersharehour(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }



  /**
   * 获取消息发送概况数据,7
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141085
   */
  function getupstreammsg(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息分送分时数据,1
   */
  function getupstreammsghour(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息发送周数据,30
   */
  function getupstreammsgweek(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息发送月数据,30
   */
  function getupstreammsgmonth(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息发送分布数据,15
   */
  function getupstreammsgdist(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息发送分布周数据,30
   */
  function getupstreammsgdistweek(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取消息发送分布月数据,30
   */
  function getupstreammsgdistmonth(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }



  /**
   * 获取接口分析数据,30
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141086
   */
  function getinterfacesummary(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }


  /**
   * 获取接口分析分时数据,1
   */
  function getinterfacesummaryhour(\DateTime $begin_date, \DateTime $end_date):array{
    return $this->check(request::url($this->host.'/cgi-bin/datacube/'.__FUNCTION__)
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['begin_date'=>$begin_date,'end_date'=>$end_date]))
      ->json())->list;
  }

}
