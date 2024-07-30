<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use think\Model;

class MsgRead extends Model
{
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->bind(['nickname' => 'nickname']);
    }

    // 阅读记录对应的通知
    public function msg()
    {
        return $this->belongsTo(Msg::class, 'm_id', 'id');
    }
}