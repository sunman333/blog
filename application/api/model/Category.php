<?php
/**
 * Created by PhpStorm.
 * User: sunman
 * Date: 2021/5/6
 * Time: 22:26
 */

namespace app\api\model;

use think\Model;
use traits\model\SoftDelete;

class Category extends Model
{
    protected $table = 'category';
    protected $deleteTime = 'delete_at';

    use  SoftDelete;
}