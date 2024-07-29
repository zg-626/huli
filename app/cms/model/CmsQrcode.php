<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\DictData;
use think\Model;

class CmsQrcode extends Model
{
    public function type()
    {
        return $this->hasOne(DictData::class, 'id', 'type')->bind(['type_name' => 'name']);
    }

    public function getvalidityTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    public function setvalidityTimeAttr($value)
    {
        return strtotime($value);
    }
}