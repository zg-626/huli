<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use think\Model;

class Invoices extends Model
{
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->bind(['nickname' => 'nickname']);
    }
}