<?php

declare (strict_types = 1);

namespace app\mxadmin\model;

use think\Model;

class AuthGroup extends Model
{
    public function groupaccess()
    {
        return $this->hasMany(AuthGroupAccess::class, 'group_id', 'id');
    }

}