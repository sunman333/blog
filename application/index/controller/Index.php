<?php
namespace app\index\controller;


use app\index\model\User;
use app\index\model\Verification;
use think\Db;
use think\Request;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }

    /**
     * 获取登录信息
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request){
        $phone = $request->param('phone');
        $password = $request->param('password');
        $uuid = $request->param('uuid');

        $check = checkLogin($phone,$password,$uuid);
        if(!$check){
            return fail('手机号与密码错误');
        }else{
            $now = time();
            $token = md5($phone.$uuid.$now);
            Db::name('user')->where(['phone' => $phone,'uuid' => $uuid])->update(['token' => $token,'times' => $now]);
            return success('',['token' => $token,'time' => $now]);
        }
    }

    public function loginOut(Request $request){
        $phone = $request->param('phone');
        $uuid = $request->param('uuid');
        updateToken($phone,$uuid);
        return success('退出成功');
    }

    /**
     * 注册接口
     * @param Request $request
     * @return \think\response\Json
     */
    public function register(Request $request){
        $phone = $request->param('phone');
        $password = $request->param('password');
        $uuid = $request->param('uuid');
        $email = $request->param('email');
        $verCode = $request->param('verification');
//        var_dump($request->param());exit;
        $res = (new User())->where(['phone' => $phone,'uuid' => $uuid])->find();
        if(!$password || $password == ''){
            return fail('密码不能为空');
        }
        if ($res){
            return fail('当前账户已存在，请直接登录');
        }else{
            $data = ['phone' => $phone,'uuid' => $uuid,'password' => md5($password),'create_time' => time(),'update_time' => time()];
            $res = (new Verification())->where(['uuid' => $uuid])->find();
            if ($res){
                $res = $res->toArray();
                if ($verCode != $res['verCode']){
                    $newVerCode = mt_rand(1000,9999);
                    Db::name('Verification')->where(['uuid' => $uuid])->update(['verCode' => $newVerCode]);
                    return fail('验证码错误');
                }
            }
            if ($email){
                $data['email'] = $email;
            }
            User::create($data);
            return success('注册成功');
        }
    }

    /**
     * 请求验证码接口
     * @param Request $request
     * @return \think\response\Json
     */
    public function verification(Request $request){
        $uuid = $request->param('uuid');
        $res = (new Verification())->where(['uuid' => $uuid])->find();
        $verCode = mt_rand(1000,9999);
        if($res){
//            Db::name('Verification')->where(['uuid' => $uuid])->update(['verCode' => $verCode]);
            $res = $res->toArray();
            return success('请求成功',['verCode' => $res['code']]);
        }else{
            Verification::create(['uuid' => $uuid,'verCode' => $verCode]);
        }
        return success('请求成功',['verCode' => $verCode]);
    }

    public function upload(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->validate(['size'=>15678,'ext'=>'jpg,png,gif'])->move( '../uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
}
