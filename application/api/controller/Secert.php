<?php
/**
 * Created by PhpStorm.
 * User: sunman
 * Date: 2021/5/16
 * Time: 00:52
 */
namespace app\api\controller;


use app\api\model\Article;
use app\api\model\Category;
use think\Controller;
use think\Db;
use think\Request;

class Secert extends Controller
{

    /**
     * 获取类别
     * @return \think\response\Json
     */
    public function catelist(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $userId = $request->param('user_id',10001);
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $list = Db::name('category')->where(['user_id' => $userId])->select();

        return success('',['content' => $list]);
    }

    /**
     * 分组添加
     * @param Request $request
     * @return \think\response\Json
     */
    public function cateAdd(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $iconImg = $request->param('iconImg');
        $title = $request->param('title');
        $isSecert = $request->param('isSecert',0);
        $password = $request->param('password');
        $userId = $request->param('userId');
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $res = Category::where(['user_id' => $userId,'cate_name' => $title])->find();
        if ($res){
            return fail('已存在同名分组');
        }
        $data = [
            'user_id' => $userId,
            'cate_name' => $title,
            'iconImg' => $iconImg?$iconImg:'',
            'is_secert' => $isSecert,
            'create_at' => time(),
            'update_at' => time()
        ];
        if ($isSecert == 1){
            $data['password'] = $password;
        }
        $classfiyId = Db::name('category')->insert($data,false,true);
        return success('添加分组成功',['classfiyId' => $classfiyId]);
    }

    /**
     * 分组编辑
     * @param Request $request
     * @return \think\response\Json
     */
    public function cateEdit(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $iconImg = $request->param('iconImg');
        $title = $request->param('title');
        $isSecert = $request->param('isSecert',0);
        $password = $request->param('password');
        $userId = $request->param('userId');
        $classfiyId = $request->param('classfiyId');
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $res = Category::where(['user_id' => $userId,'cate_name' => $title])->where('id','!=',$classfiyId)->find();
        if ($res){
            return fail('已存在同名分组');
        }
        $data = [
//            'user_id' => $userId,
            'cate_name' => $title,
            'iconImg' => $iconImg,
            'is_secert' => $isSecert,
//            'create_at' => time(),
            'update_at' => time()
        ];
        if ($isSecert == 1){
            $data['password'] = $password;
        }
        Db::name('category')->where(['id' => $classfiyId])->update($data);
        return success('修改分组成功',['classfiyId' => $classfiyId]);
    }

    /**
     * 分组删除（软删除）
     * @param Request $request
     * @return \think\response\Json
     */
    public function cateDel(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $classfiyId = $request->param('classfiyId');
        $userId = $request->param('userId');
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $res = Category::destroy(['id' => $classfiyId,'user_id' => $userId]);
//        $admin = new Admin();
//        $admin->restore(['id' => '14']);
        return success('删除分组成功');
    }


    /**
     * 获取文章信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function articleList(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $classfiyId = $request->param('classfiyId',1);
        $userId = $request->param('userId');
        $check = checkToken($phone,$token,$uuid);
        if(!$check){
            return fail('请先登录！');
        }
        $list = Db::name('article')->where(['cate_id' => $classfiyId,'user_id' => $userId])->order('mark','desc')->select();
        return success('',['content' => $list]);
    }

    /**
     * 文章新增
     * @param Request $request
     * @return \think\response\Json
     */
    public function articleAdd(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $classfiyId = $request->param('classfiyId',1);//分类id
        $userId = $request->param('user_id');
        $content = $request->param('content');//文章主题
        $title = $request->param('title');//文章标题
        $mark = $request->param('mark',0);//是否星标
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $data = [
            'user_id' => $userId,
            'title'   => $title,
            'cate_id' => $classfiyId,
            'content' => $content,
            'mark'    => $mark
        ];
        $newId = Db::name('article')->insert($data,false,true);
        if ($newId){
            return success('添加成功',['articleId' => $newId]);
        }else{
            return fail('添加失败');
        }

    }

    public function articleEdit(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $articelId = $request->param('articleId',1);//分类id
        $userId = $request->param('user_id');
        $content = $request->param('content');//文章主题
        $title = $request->param('title');//文章标题
        $mark = $request->param('mark',0);//是否星标
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $data = [
            'title'   => $title,
            'content' => $content,
            'mark'    => $mark
        ];
        $res = Db::name('article')->where(['id' => $articelId,'user_id' => $userId])->update($data);
        if ($res){
            return success('修改成功',['articleId' => $articelId]);
        }else{
            return fail('修改失败');
        }
    }

    /**
     * 文章删除（软删除）
     * @param Request $request
     * @return \think\response\Json
     */
    public function articleDel(Request $request){
        $phone = $request->param('phone');
        $token = $request->param('token');
        $uuid = $request->param('uuid');
        $articleId = $request->param('articleId');
        $userId = $request->param('userId');
        $check = checkToken($phone,$token,$uuid);
//        if(!$check){
//            return fail('请先登录！');
//        }
        $res = Category::destroy(['id' => $articleId,'user_id' => $userId]);
//        $admin = new Admin();
//        $admin->restore(['id' => '14']);
        return success('删除文章成功');
    }
}