<?php
/**
 * Created by PhpStorm.
 * User: sunman
 * Date: 2021/5/7
 * Time: 23:15
 */

namespace app\api\model;


use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{
    protected $table = 'article';
    protected $deleteTime = 'delete_time';

    use SoftDelete;
}