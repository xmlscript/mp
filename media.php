<?php namespace mp; // vim: se fdm=marker:

use http\request;

class media extends wx{

  /**
   * 新增临时素材
   * @param string $type image, voice, video, thumb
   */
  function upload(string $type):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738726
    return $this->check(request::url(self::HOST.'/cgi-bin/media/upload')
      ->query(['access_token'=>$this->token,'type'=>$type])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//TODO 
      ->json());
  }


  /**
   * 获取临时素材
   */
  function get(string $media_id):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738727
    return $this->check(request::url(self::HOST.'/cgi-bin/media/get')
      ->fetch(['access_token'=>$this->token,'type'=>$type])
      ->json());
  }


  /**
   * 获取永久素材
   */
  function get_material(string $media_id):\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738730
    return $this->check(request::url(self::HOST.'/cgi-bin/material/get_material')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['media_id'=>$media_id]))
      ->json());
  }


  /**
   * 删除永久素材
   */
  function del_material(string $media_id):bool{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738731
    return $media_id&&$this->check(request::url(self::HOST.'/cgi-bin/material/del_material')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['media_id'=>$media_id]))
      ->json());
  }


  /**
   * 修改永久素材
   * @param int $index 注意，此时第一篇为0了（前后不一致）
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738732
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function update_material(string $media_id, int $index=0, array $arr):bool{
    return $media_id&&$this->check(request::url(self::HOST.'/cgi-bin/material/update_material')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['media_id'=>$media_id,'index'=>$index,'articles'=>$arr,'need_open_comment'=>(int)$need_open_comment,'only_fans_can_comment'=>(int)$only_fans_can_comment]))
      ->json());
  }


  function get_materialcount():\stdClass{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738733
    return $media_id&&$this->check(request::url(self::HOST.'/cgi-bin/material/get_materialcount')
      ->fetch(['access_token'=>$this->token])
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
    return $media_id&&$this->check(request::url(self::HOST.'/cgi-bin/material/batchget_material')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['type'=>$type,'offset'=>$offset,'count'=>$count,'need_open_comment'=>(int)$need_open_comment,'only_fans_can_comment'=>(int)$only_fans_can_comment]))
      ->json());
  }


  /**
   * 打开已群发的文章里的评论功能
   * @param int $msg_data_id 群发接口返回的那个id
   * @param int $index=0 多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function open(int $msg_data_id, int $index=0):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/open')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index]))
      -json());
  }


  /**
   * 关闭已群发的文章里的评论功能
   * @param int $msg_data_id 群发接口返回的那个id
   * @param int $index=0 多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function close(int $msg_data_id, int $index=0):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/close')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index]))
      -json());
  }


  /**
   * 查看某个文章的评论
   * @param int $msg_data_id 群发接口返回的那个id
   * @param int $index=0 多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
   * @param int $begin 从N开始？？？
   * @param int $count >=50将被拒绝
   * @param int $type 0全部 1普通评论 2精选评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function list(int $msg_data_id, int $begin, int $count, int $type, int $index=0):\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/comment/list')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index]))
      -json());
  }


  /**
   * 将某条评论标注为“精选”评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function markelect(int $msg_data_id, int $index, int $user_comment_id):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/markelect')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json());
  }


  /**
   * 取消某条评论的“精选”资格
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function unmarkelect(int $msg_data_id, int $index, int $user_comment_id):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/unmarkelect')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json());
  }


  /**
   * 删评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function delete(int $msg_data_id, int $index, int $user_comment_id):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/delete')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json());
  }


  /**
   * 回复某个评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function reply_add(int $msg_data_id, int $user_comment_id, string $content, int $index=0):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/reply/add')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id,'content'=>$content]))
      -json());
  }


  /**
   * 删除某个评论的回复内容
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function reply_delete(int $msg_data_id, int $user_comment_id, int $index=0):bool{
    return $msg_data_id&&$this->check(request::url(self::HOST.'/cgi-bin/comment/reply/delete')
      ->query(['access_token'=>$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json());
  }


  /**
   * 新增永久素材
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
  function add_news():\stdClass{
    return $this->check(request::url(self::HOST.'/cgi-bin/material/add_news')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST()
      ->json());
  }


  /**
   * 本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。
   * 图片仅支持jpg/png格式，大小必须在1MB以下。
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
   */
  function uploadimg():string{
    return $this->check(request::url(self::HOST.'/cgi-bin/media/uploadimg')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//TODO 
      ->json())->url;
  }


  /**
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444738729
   */
  function add_material(string $type):string{
    return $this->check(request::url(self::HOST.'/cgi-bin/material/add_material')
      ->query(['access_token'=>$this->token,'type'=>$type])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->upload()//TODO 
      ->json())->url;
  }


  function uploadnews(array ...$news):string{
    https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1481187827_i0l21
    return $this->check(request::url(self::HOST.'/cgi-bin/media/uploadnews')
      ->query(['access_token'=>$this->token])
      ->header('Content-Type','application/json;charset=UTF-8')
      ->POST(json_encode(['articles'=>$news]))
      ->json())->url;
  }

}
