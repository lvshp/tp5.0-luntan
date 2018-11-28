<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Reply extends Model
{
    // 删除评论
    public function del($id)
    {
        $res = DB::table('reply')->where('id',$id)->delete();

        if($res){
            return 1;
        }else{
            return false;
        }
    }

    // 获取评论内容
    public function getReply($id)
    {

        $reply = DB::table('reply')->where('id',$id)->find();
        
        if($reply){
            return $reply;
        }else{
            return false;
        }

    }

    // 修改评论内容
    public function editReply($data)
    {
        $model = new Model();
        $res = DB::table('reply')->where('id',$data['id'])->update(['content'=>$data['content']]);
        if($res){
            return 1;
        }else{
            return false;
        }
    }

}