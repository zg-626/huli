<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use think\Model;

class Question extends Model
{
    /*public function type()
    {
        return $this->hasOne(DictData::class, 'id', 'type')->bind(['type_name' => 'name']);
    }*/

    // 获取试题类型
    public function getTypeNameAttr($value,$data)
    {
        $type = [1=>'单选题',2=>'多选题',3=>'判断题'];
        return $type[$data['type']];

    }
}