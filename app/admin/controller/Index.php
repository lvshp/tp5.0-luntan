<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Parser;
use think\Session;
use think\config\driver\Json;
class Index extends Controller
{

    public function index()
    {
        $list = DB::name('article')->paginate(10);
        $page = $list->render();
        $list = iterator_to_array($list);
        $i = 0;
        $parser = new Parser;
        foreach($list as $k=>$v){
            $user = DB::name('user')->where(['id'=>$v['author']])->find();
            $list[$k]['nickname'] = $user['nickname'];
            $list[$k]['content'] = $parser->makeHtml(mb_substr($v['content'],0,15,'utf-8'));
        }
        return view('index',['list'=>$list,'page'=>$page]);
    }

    /*
     *文章列表 
     *  $id 文章id
     */
    public function article($id)
    {
        $article = DB::name('article')->where(['id'=>$id])->find();
        $article['view'] = $article['view']+1;
        DB::table('article')->where('id',$article['id'])->update(['view'=>$article['view']]);
        $article['time'] = date("Y-m-d H:i:s",$article['time']);
        $user = DB::name('user')->where(['id'=>$article['author']])->find();
        //评论内容
        $parser = new Parser; // markdown 转html
        $reply = DB::name('reply')->where(['reply'=>$article['id']])->order('time desc')->select();
        foreach($reply as $k=>$v){
            // 获取评论的用户
            $reply_user = DB::name('user')->where(['nickname'=>$v['author']])->find();
            //时间格式转换
            $reply[$k]['time'] = date('Y-m-d H:i:s',$reply[$k]['time']);
            $reply[$k]['content'] = $parser->makeHtml($v['content']);
            unset($reply_user['id']);
            $reply[$k]['nickname'] = $reply_user['nickname'];
            $reply[$k]['photo']    = $reply_user['photo'];
        }
        return view('article',['article'=>$article,'user'=>$user,'reply'=>$reply]);
    }

    //添加新文章
    public function add()
    {
        return view('add');
    }

    public function doadd()
    {
        $post =  input();
        $post['time'] = time();
        // $post['author'] = session('user.id');
        $post['author'] = "1";
        $res = DB::name('article')->insert($post);
        if($res){
            // 更新session里用户的发帖数
            $suser = Session::get('USER_INFO');
            $suser['article_num'] = $suser['article_num']+1;
            Session::set('USER_INFO',$suser);
            $this->redirect('/');
        }
    }
    // 发表评论
    public function ajax_reply()
    {
        $post = input();
        if ($post) {
            // 时间转换格式
            $post['time'] = time();
            $res = DB::name('reply')->insert($post);
            $id = DB::name('reply')->getLastInsID();
            //如果添加成功 获取id
            if($res){
                //更新session里用户的评论数
                $suser = Session::get('USER_INFO');
                $suser['reply_num'] = $suser['reply_num']+1;
                Session::set('USER_INFO',$suser);
                // 获取用户信息
                $user = DB::name('user')->where(['nickname'=>$post['author']])->find();
                // 获取刚添加评论的id 用作编辑删除
                $user['replyid'] = DB::name('reply')->getLastInsID();
                // 去掉用户密码
                unset($user['password']);
                unset($user['id']);
                $data = array_merge($post,$user);
                $data['time'] = date('Y-m-d H:i:s',$data['time']);
                $data['id'] = $id;
                $data['status'] = '1';
            }else{
                $data['status'] = '2';
            }
        }else{
            $data['status'] = '2';
        }
        echo json_encode($data);
    }

    // 编辑评论页
    public function editreply($id)
    {
        // 获取评论内容
        $model = new \app\admin\model\Reply();
        $data = $model->getReply($id);
        if(!$data){
            echo "<script> alert('服务器错误，请刷新页面重试');window.history.back(-1); </script>";
        }
        return view('editreply',['data'=>$data]);
    }
    
    // 修改评论
    public function doedit_reply()
    {
        // 获取提交的评论内容
        $data = input();
        
        // 修改数据库评论内容
        $model = new \app\admin\model\Reply();
        $reg = $model->editReply($data);
        if($reg == 1){
            echo "<script>alert('修改成功!');history.go(-2);location.reload();</script>"; 
        }else{
            echo "<script>alert('没有内容被修改!');history.go(-2);location.reload();</script>";
        }
    }

    // 删除评论
    public function delreply()
    {
        $id = input('id');
        $model = new \app\admin\model\Reply();
        $reg = $model->del($id);

        if($reg == 1){
            return Json(['status' => '1','msg' => '删除成功']);
        }else{
            return Json(['status' => '2','msg' => '删除失败']);
        }

    }
}
