<?php namespace mp; // vim: se fdm=marker:

use http\request;

class comment{
  
  function __construct(token $token){
    $this->token = $token;
  }


  /**
   * 打开已群发的文章里的评论功能
   * @param int $msg_data_id 群发接口返回的那个id
   * @param int $index=0 多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function open(int $msg_data_id, int $idx=0):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/open')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$idx]))
      -json())->errcode;
  }


  /**
   * 关闭已群发的文章里的评论功能
   * @param int $msg_data_id 群发接口返回的那个id
   * @param int $index=0 多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function close(int $msg_data_id, int $index=0):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/close')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index]))
      -json())->errcode;
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
  function list(int $msg_data_id, int $begin, int $count=50, int $type=0, int $index=0):\stdClass{
    return token::check(request::url(token::HOST.'/cgi-bin/comment/list')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index]))
      -json());
  }


  /**
   * 将某条评论标注为“精选”评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function markelect(int $msg_data_id, int $index, int $user_comment_id):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/markelect')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json())->errcode;
  }


  /**
   * 取消某条评论的“精选”资格
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function unmarkelect(int $msg_data_id, int $index, int $user_comment_id):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/unmarkelect')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json())->errcode;
  }


  /**
   * 删评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function delete(int $msg_data_id, int $index, int $user_comment_id):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/delete')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json())->errcode;
  }


  /**
   * 回复某个评论
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function reply_add(int $msg_data_id, int $user_comment_id, string $content, int $index=0):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/reply/add')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id,'content'=>$content]))
      -json())->errcode;
  }


  /**
   * 删除某个评论的回复内容
   * @see https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1494572718_WzHIY
   */
  function reply_delete(int $msg_data_id, int $user_comment_id, int $index=0):bool{
    return !token::check(request::url(token::HOST.'/cgi-bin/comment/reply/delete')
      ->query(['access_token'=>(string)$this->token])
      ->POST(json_encode(['msg_data_id'=>$msg_data_id,'index'=>$index,'user_comment_id'=>$user_comment_id]))
      -json())->errcode;
  }

}
