<?php namespace mp; // vim: se fdm=marker:

use http\request;

class media{
  
  function __construct(token $token){
    $this->token = $token;
  }

  /**
   * 新增临时素材，三天后media_id失效
   * 应当从$_FILES变量里面推导type
   * @param string $type image, voice, video, thumb
   */
  function upload(string $type):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738726
    return token::check(request::url(token::HOST.'/cgi-bin/media/upload')
      ->query(['access_token'=>(string)$this->token,'type'=>$type])
      ->upload()//TODO 
      ->json())->media_id;
  }


  /**
   * 获取临时素材
   * 视频类型返回json，其他类型则直接得到文件
   */
  function get(string $media_id):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738727
    return (string)request::url(token::HOST.'/cgi-bin/media/get')
      ->query(['access_token'=>(string)$this->token,'media_id'=>$media_id]);
  }


  /**
   * 获取永久素材
   * 视频和news类型返回json，其他类型则直接得到文件
   */
  function get_material(string $media_id){
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738730
    return request::url(token::HOST.'/cgi-bin/material/get_material')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['media_id'=>$media_id]));
  }


  /**
   * 删除永久素材
   */
  function del_material(string $media_id):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738731
    return !request::url(token::HOST.'/cgi-bin/material/del_material')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['media_id'=>$media_id]))
      ->json()->errcode;
  }


  /**
   * 修改永久素材
   * @param int $index 注意，此时第一篇为0了（前后不一致）
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738732
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function update_material(string $media_id, int $index=0, array $arr):bool{
    return !request::url(token::HOST.'/cgi-bin/material/update_material')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['media_id'=>$media_id,'index'=>$index,'articles'=>$arr,'need_open_comment'=>(int)$need_open_comment,'only_fans_can_comment'=>(int)$only_fans_can_comment]))
      ->json()->errcode;
  }


  function get_materialcount():\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738733
    return token::check(request::url(token::HOST.'/cgi-bin/material/get_materialcount')
      ->fetch(['access_token'=>(string)$this->token])
      ->json());
  }


  /**
   * @param string $type image, video, voice, news
   * @param int $offset
   * @param int $count
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738734
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function batchget_material(string $type, int $offset=0, int $count=20):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/material/batchget_material')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['type'=>$type,'offset'=>$offset,'count'=>$count]))
      ->json());
  }


  /**
   * 新增永久素材里的news，因为news比较特殊
   * @param string title
   * @param string thumb_media_id 必须填永久素材图片的media_id
   * @param string author
   * @param string digest 多图文为""，单图文可以设置摘要文字，如果没有填写，则默认抓正文前60字
   * @param bool show_cover_pic
   * @param string content 净荷部分，html，但过滤js和外部img
   * @param string content_source_url "阅读原文"的url
   * @param bool need_open_comment 是否打开评论
   * @param bool only_fans_can_comment 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function add_news(article ...$article):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/material/add_news')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['articles'=>$article]))
      ->json());
  }


  /**
   * 本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。
   * 图片仅支持jpg/png格式，大小必须在1MB以下。
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
   */
  function uploadimg():string{
    return token::check(request::url(token::HOST.'/cgi-bin/media/uploadimg')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//TODO 
      ->json())->url;
  }


  /**
   * 上传其他非news类型的永久素材
   * @todo 应该让客户端自己直接与微信官服直接通信，而不是由库代理
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
   */
  function add_material(string $file):string{
    //TODO 首先检测文件类型，允许image, voice, video, thumb
    //TODO 图片不超过5mb，有可能被设置为自动添加水印
    //TODO voice支持mp3,wma,wav,amr，30mb之内，30sec之内，额外附带14字标题和分类
    //TODO 视频20mb之内，1sec到10h之内，视频格式：
    // mp4, flv, f4v, webm
    // m4v, mov, 3gp, 3g2
    // rm, rmvb
    // wmv, avi, asf
    // mpg, mpeg, mpe, ts
    // div, dv, divx
    // vob, dat, mkv, swf, lavf, cpk, dirac, ram, qt, fli, flc, mod
    // mp3, aac, ac3, wav, m4a, ogg
    //TODO 视频额外需要设置：标题，封面，分类， 介绍语
    //FIXME 什么是小视频？？？15sec那种吗？？？
    return token::check(request::url(token::HOST.'/cgi-bin/material/add_material')
      ->query(['access_token'=>(string)$this->token,'type'=>$type])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//FIXME 
      ->POST(json_encode(['title'=>'视频标题','introduction'=>'视频描述'))//TODO 视频素材需要额外信息
      ->json())->url;
  }


  function uploadnews(array ...$news):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return token::check(request::url(token::HOST.'/cgi-bin/media/uploadnews')
      ->query(['access_token'=>(string)$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['articles'=>$news]))
      ->json())->url;
  }

}
