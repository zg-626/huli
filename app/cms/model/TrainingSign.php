<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use think\Model;

class TrainingSign extends Model
{
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->bind(['nickname' => 'nickname']);
    }

    // 报名记录对应的学习班
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'id');
    }
}