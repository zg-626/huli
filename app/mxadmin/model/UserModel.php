<?php

declare (strict_types = 1);

namespace app\mxadmin\model;

use app\cms\model\MsgRead;
use app\cms\model\TrainingSign;
use app\common\service\FileService;
use think\Model;
use app\cms\model\CmsCategory;

class UserModel extends Model
{
    // 模型名
    protected $name = 'user';

    // 字段设置类型自动转换
    protected $type = [
        'login_time'  =>  'timestamp',
        'last_login_time'  =>  'timestamp',
    ];

    /**
     * 获取医院
     * @return \think\model\relation\HasOne
     */
    public function hospital()
    {
        return $this->hasOne(CmsCategory::class, 'id', 'd_id')->bind(['departmentname'=>'name']);
    }

    /**
     * @param $value
     * @return string
     */
    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    // 学历
    public function educationalType()
    {
        return $this->hasOne(DictData::class, 'id', 'educational_id')->bind(['educational_name' => 'name']);
    }

    // 职称
    public function professionalType()
    {
        return $this->hasOne(DictData::class, 'id', 'professional_id')->bind(['professional_name' => 'name']);
    }

    // 职务
    public function positionType()
    {
        return $this->hasOne(DictData::class, 'id', 'position_id')->bind(['position_name' => 'name']);
    }

    // 用户阅读记录关联
    public function msgReads()
    {
        return $this->hasMany(MsgRead::class, 'user_id', 'id');
    }

    // 用户报名记录关联
    public function trainingSigns()
    {
        return $this->hasMany(TrainingSign::class, 'user_id', 'id');
    }

    // 处理图片
    public function getHeadimgAttr($value)
    {
        if($value){
            return FileService::setFileUrl($value);
        }
    }



}