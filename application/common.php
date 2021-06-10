<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function success($message,$data = []){
    $array = [
        'code' => 200,
        'success' => true,
        'data' => $data,
        'msg' => $message,
        ];
    return json($array);
}

function fail($message){
    $array = [
        'code' => 4000,
        'success' => false,
        'data' => [],
        'errorMsg' => $message
    ];
    return json($array);
}

function checkLogin($phone,$password,$uuid){
    $check = \app\index\model\User::where(['phone' => $phone,'password' => md5($password),'uuid' => $uuid])->find();
    if($check){
        return true;
    }else{
        return false;
    }
}

function updateToken($phone,$uuid){
    \think\Db::name('user')->where(['phone' => $phone,'uuid' => $uuid])->update(['token' => '']);
}

function checkToken($phone,$token,$uuid){
    $check = \app\index\model\User::where(['phone' => $phone,'token' => $token,'uuid' => $uuid])->find();
    if($check){
        return true;
    }else{
        return false;
    }
}

?>