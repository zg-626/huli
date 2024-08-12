<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use think\Model;

class Paper extends Model
{
    /*public function type()
    {
        return $this->hasOne(DictData::class, 'id', 'type')->bind(['type_name' => 'name']);
    }*/

    // 关联题目
    public function questions()
    {
        return $this->hasMany(Question::class, 'paper_id', 'id');
    }

}