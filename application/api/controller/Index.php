<?php
/**
 * Created by PhpStorm.
 * User: sunman
 * Date: 2021/5/16
 * Time: 01:37
 */

namespace app\api\controller;


use think\Controller;
use think\Request;

class Index extends Controller
{
    public function check($phone,$token,$uuid){
        $check = checkToken($phone,$token,$uuid);
        if (!$check){
            return fail('请先登录');
        }
    }
}