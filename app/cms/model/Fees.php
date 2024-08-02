<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\cms\controller\Msg;
use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use think\Model;

class Fees extends Model
{
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->bind(['nickname' => 'nickname']);
    }
}